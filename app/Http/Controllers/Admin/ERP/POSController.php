<?php

namespace App\Http\Controllers\Admin\ERP;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

// Models
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Admin;

// Mailables
use App\Mail\POS\SaleRecorded;
use App\Mail\POS\SaleVoided;

class POSController extends Controller
{
    public function pos(){
        $products = Product::where('is_active', true)->orderBy('name', 'asc')->get();
        return view('admin.erp.pos', [
            'products' => $products
        ]);
    }

    public function processSale(Request $request){
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|string',
            'cart_items' => 'required|array|min:1',
            'payable_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        DB::beginTransaction();
        try {
            if (Auth::guard('admin')->check()) {
                $user = Auth::guard('admin')->user();
                $userId = $user->id;
                $userType = 'admin';
            } else {
                $user = Auth::guard('staff')->user();
                $userId = $user->id;
                $userType = 'staff';
            }

            $sale = Sale::create([
                'reference_no'    => 'REC-' . strtoupper(Str::random(8)),
                'user_id'         => $userId,
                'user_type'       => $userType,
                'total_amount'    => $request->total_amount,
                'discount_amount' => $request->discount_amount ?? 0,
                'payable_amount'  => $request->payable_amount,
                'payment_method'  => $request->payment_method,
                'notes'           => $request->notes,
            ]);

            foreach ($request->cart_items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $product->reduceStock($item['quantity']);

                SaleItem::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal'   => $item['quantity'] * $item['price'],
                ]);
            }

            $this->notifySale($sale, $user);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Sale Completed Successfully!',
                'reference_no' => $sale->reference_no
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error("POS Error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
   

    public function salesHistory(){
        $sales = Sale::with(['items.product'])->latest()->get();
        return view('admin.erp.salesHistory', ['sales' => $sales]);
    }


    /**
     * Fetch Sale Details JSON for Modal Rendering
     * Route: GET /admin/sales/details/{id}
     */
    public function getSaleDetails($id) 
    {
        try {
            $sale = \App\Models\Sale::withTrashed()->findOrFail($id);

            // Manual dynamic fallback check to map string values to staff properties securely
            $staffName = 'Unknown Operator';
            if ($sale->user_type === 'admin') {
                $staffName = \DB::table('admins')->where('id', $sale->user_id)->value('name') ?? 'Admin';
            } else {
                $staffName = \DB::table('staff')->where('id', $sale->user_id)->value('name') ?? 'Staff';
            }

            // Fetch items directly from table database layer bypassing recursive mapping hooks
            $rawItems = \DB::table('sale_items')
                ->join('products', 'sale_items.product_id', '=', 'products.id')
                ->where('sale_items.sale_id', $sale->id)
                ->select('products.name as product_name', 'sale_items.quantity', 'sale_items.unit_price', 'sale_items.subtotal')
                ->get();

            return response()->json([
                'success' => true,
                'sale' => [
                    'reference_no'    => $sale->reference_no,
                    'created_at'      => $sale->created_at->format('d M, Y H:i'),
                    'staff_name'      => $staffName,
                    'total_amount'    => $sale->total_amount,
                    'discount_amount' => $sale->discount_amount,
                    'payable_amount'  => $sale->payable_amount,
                    'payment_method'  => $sale->payment_method,
                    'items'           => $rawItems
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction items could not be found: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Delete a Sale Record (Voiding)
     * Route: POST /admin/sales/void
     */
    public function voidSale(\Illuminate\Http\Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'sale_id' => 'required',
            'reason'  => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back();
        }

        $sale = \App\Models\Sale::withTrashed()->findOrFail($request->sale_id);
        $user = \Auth::guard('admin')->user() ?? \Auth::guard('staff')->user() ?? \Auth::user();
        
        $ref    = $sale->reference_no;
        $amt    = $sale->payable_amount;
        $reason = $request->reason;

        \DB::beginTransaction();
        try {
            $items = \DB::table('sale_items')
                ->where('sale_id', $sale->id)
                ->whereNull('deleted_at')
                ->get();

            foreach ($items as $item) {
                \DB::table('products')
                    ->where('id', $item->product_id)
                    ->increment('stock_on_hand', $item->quantity);
            }

            \DB::table('sale_items')
                ->where('sale_id', $sale->id)
                ->update(['deleted_at' => now()]);

            $sale->delete();

            if (method_exists($this, 'notifyVoid')) {
                $this->notifyVoid($ref, $amt, $user, $reason);
            }

            \DB::commit();

            alert()->success('Voided Successfully', 'Transaction has been voided and stock restored.')->persistent('Close');

        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error("Void Sale Failed for Ref " . $ref . ": " . $e->getMessage());

            alert()->error('Error', 'Could not void transaction: ' . $e->getMessage())->persistent('Close');
        }

        return redirect()->back();
    }

    private function notifySale($sale, $user) {
        try {
            $admins = Admin::all();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new SaleRecorded($sale, $user));
            }
        } catch (\Exception $e) { Log::error("Sale mail failed: " . $e->getMessage()); }
    }

    private function notifyVoid($ref, $amt, $user, $reason) {
        try {
            $admins = Admin::all();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new SaleVoided($ref, $amt, $user, $reason));
            }
        } catch (\Exception $e) { Log::error("Void mail failed: " . $e->getMessage()); }
    }
}