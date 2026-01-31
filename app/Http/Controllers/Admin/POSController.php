<?php

namespace App\Http\Controllers\Admin;

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
        return view('admin.pos', [
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

    /**
     * Delete a Sale Record (Voiding)
     * This is a high-security action.
     */
    public function voidSale(Request $request) {
        $sale = Sale::findOrFail($request->sale_id);
        $user = Auth::user();
        $ref = $sale->reference_no;
        $amt = $sale->payable_amount;
        $reason = $request->reason;

        DB::beginTransaction();
        try {
            // Restore stock before deleting
            foreach ($sale->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock_on_hand', $item->quantity);
                }
            }

            $sale->items()->delete();
            $sale->delete();

            // Send Security Alert
            $this->notifyVoid($ref, $amt, $user, $reason);

            DB::commit();
            alert()->success('Voided', 'Transaction has been voided and stock restored.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Void Sale Failed: " . $e->getMessage());
            alert()->error('Error', 'Could not void transaction.');
            return redirect()->back();
        }
    }

    public function salesHistory(){
        $sales = Sale::with(['items.product'])->latest()->get();
        return view('admin.salesHistory', ['sales' => $sales]);
    }

    public function getSaleDetails($id){
        try {
            $sale = Sale::with(['items.product'])->find($id);
            if (!$sale) return response()->json(['success' => false, 'message' => 'Not found'], 404);

            return response()->json([
                'success' => true,
                'sale' => [
                    'reference_no'    => $sale->reference_no,
                    'staff_name'      => $sale->seller_name,
                    'total_amount'    => $sale->total_amount,
                    'discount_amount' => $sale->discount_amount,
                    'payable_amount'  => $sale->payable_amount,
                    'payment_method'  => $sale->payment_method,
                    'notes'           => $sale->notes,
                    'created_at'      => $sale->created_at->format('d M, Y H:i'),
                    'items' => $sale->items->map(fn($item) => [
                        'product_name' => $item->product->name ?? 'Unknown',
                        'quantity'     => $item->quantity,
                        'unit_price'   => $item->unit_price
                    ])
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
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