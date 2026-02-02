<?php

namespace App\Http\Controllers\Staff;

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

class POSController extends Controller
{
    public function pos() {
        $products = Product::where('is_active', true)
            ->where('stock_on_hand', '>', 0)
            ->orderBy('name', 'asc')
            ->get();
            
        return view('staff.pos', ['products' => $products]);
    }

    public function processSale(Request $request) {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|string',
            'cart_items'     => 'required|array|min:1',
            'payable_amount' => 'required|numeric|min:0',
            'total_amount'   => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        DB::beginTransaction();
        try {
            $user = Auth::guard('staff')->user();

            $sale = Sale::create([
                'reference_no'    => 'REC-' . strtoupper(Str::random(8)),
                'user_id'         => $user->id,
                'user_type'       => 'staff',
                'total_amount'    => $request->total_amount,
                'discount_amount' => $request->discount_amount ?? 0,
                'payable_amount'  => $request->payable_amount,
                'payment_method'  => $request->payment_method,
                'notes'           => $request->notes,
            ]);

            foreach ($request->cart_items as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                if($product->stock_on_hand < $item['quantity']) {
                    throw new \Exception("Insufficient stock for " . $product->name);
                }

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
            Log::error("Staff POS Error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function salesHistory() {
        // Staff sees their personal sales history
        $sales = Sale::where('user_id', Auth::guard('staff')->id())
                    ->where('user_type', 'staff')
                    ->with(['items.product'])
                    ->latest()
                    ->get();
        return view('staff.salesHistory', ['sales' => $sales]);
    }

    public function getSaleDetails($id) {
        try {
            $sale = Sale::with(['items.product'])->find($id);
            if (!$sale) return response()->json(['success' => false, 'message' => 'Not found'], 404);

            return response()->json([
                'success' => true,
                'sale' => [
                    'reference_no' => $sale->reference_no,
                    'total_amount' => $sale->total_amount,
                    'payable_amount' => $sale->payable_amount,
                    'payment_method' => $sale->payment_method,
                    'created_at' => $sale->created_at->format('d M, Y H:i'),
                    'items' => $sale->items->map(fn($item) => [
                        'product_name' => $item->product->name ?? 'Unknown',
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price
                    ])
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function notifySale($sale, $user) {
        try {
            $adminEmails = Admin::pluck('email')->toArray();
            if (!empty($adminEmails)) {
                Mail::to($adminEmails)->send(new SaleRecorded($sale, $user));
            }
        } catch (\Exception $e) { Log::error("Staff Sale mail failed: " . $e->getMessage()); }
    }
}