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
        $products = Product::with(['storeProduct.primaryImage'])
            ->where('is_active', true)
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
                'order_status'    => 'completed',
                'payment_status'  => 'paid',
                'paid_at'         => now(),
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

            DB::commit();

            $this->notifySale($sale, $user);

            return response()->json([
                'success' => true, 
                'message' => 'Sale Completed Successfully!', 
                'reference_no' => $sale->reference_no,
                'sale' => [
                    'reference_no'    => $sale->reference_no,
                    'created_at'      => $sale->created_at->format('d M, Y H:i'),
                    'seller_name'     => $user->name ?? 'Staff',
                    'total_amount'    => (float) $sale->total_amount,
                    'discount_amount' => (float) $sale->discount_amount,
                    'payable_amount'  => (float) $sale->payable_amount,
                    'payment_method'  => $sale->payment_method,
                    'notes'           => $sale->notes,
                ]
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

    public function getSaleDetails(string $reference) {
        try {
            $sale = Sale::with(['items.product', 'staff', 'admin'])
                ->where('reference_no', $reference)
                ->first();
        
            if (!$sale) return response()->json(['success' => false, 'message' => 'Not found'], 404);

            return response()->json([
                'success' => true,
                'sale' => [
                    'reference_no'    => $sale->reference_no,
                    'total_amount'    => (float) $sale->total_amount,
                    'discount_amount' => (float) ($sale->discount_amount ?? 0),
                    'shipping_fee'    => (float) ($sale->shipping_fee ?? 0),
                    'payable_amount'  => (float) $sale->payable_amount,
                    'payment_method'  => $sale->payment_method,
                    'payment_status'  => $sale->payment_status,
                    'order_status'    => $sale->order_status,
                    'notes'           => $sale->notes,
                    'staff_name'      => $sale->user_type === 'customer' ? 'Online Merchant' : $sale->seller_name,
                    'merchant_name'   => $sale->user_type === 'customer' ? 'Online Merchant' : $sale->seller_name,
                    'created_at'      => $sale->created_at->format('d M, Y H:i'),
                    'items'           => $sale->items->map(fn($item) => [
                        'product_name' => $item->product->name ?? 'Unknown',
                        'quantity'     => $item->quantity,
                        'unit_price'   => (float) $item->unit_price,
                        'subtotal'     => (float) ($item->subtotal ?? ($item->quantity * $item->unit_price))
                    ])
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function notifySale($sale, $user) {
        try {
            $adminEmails = Admin::pluck('email')->filter()->unique()->values()->all();
            if (empty($adminEmails)) return;

            $mailable = new SaleRecorded($sale, $user);
            $primary = array_shift($adminEmails);

            $mail = Mail::to($primary);
            if (!empty($adminEmails)) {
                $mail->bcc($adminEmails);
            }
            $mail->send($mailable);
        } catch (\Throwable $e) { Log::error("Staff Sale mail failed: " . $e->getMessage()); }
    }
}