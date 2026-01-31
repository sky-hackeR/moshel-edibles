<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;

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

        try {
            DB::beginTransaction();

            // Detect which guard is currently logged in
            if (Auth::guard('admin')->check()) {
                $userId = Auth::guard('admin')->id();
                $userType = 'admin';
            } else {
                $userId = Auth::guard('staff')->id();
                $userType = 'staff';
            }

            // Create Sale Record
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

            // Process Items
            foreach ($request->cart_items as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                // Reduce stock using the method in your Product model
                $product->reduceStock($item['quantity']);

                SaleItem::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal'   => $item['quantity'] * $item['price'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sale Completed Successfully!',
                'reference_no' => $sale->reference_no
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error("POS Error: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Transaction Failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function salesHistory(){
        // Eager load only items and products to keep it efficient
        $sales = Sale::with(['items.product'])->latest()->get();
        
        // Using explicit array instead of compact
        return view('admin.salesHistory', [
            'sales' => $sales
        ]);
    }

    public function getSaleDetails($id){
        try {
            $sale = Sale::with(['items.product'])->find($id);

            if (!$sale) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Transaction not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'sale' => [
                    'reference_no'    => $sale->reference_no,
                    'staff_name'      => $sale->seller_name, // Uses our accessor
                    'total_amount'    => $sale->total_amount,
                    'discount_amount' => $sale->discount_amount,
                    'payable_amount'  => $sale->payable_amount,
                    'payment_method'  => $sale->payment_method,
                    'notes'           => $sale->notes,
                    'created_at'      => $sale->created_at->format('d M, Y H:i'),
                    'items' => $sale->items->map(function($item) {
                        return [
                            'product_name' => $item->product->name ?? 'Unknown Item',
                            'quantity'     => $item->quantity,
                            'unit_price'   => $item->unit_price
                        ];
                    })
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => $e->getMessage()
            ], 500);
        }
    }
}