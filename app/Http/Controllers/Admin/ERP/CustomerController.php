<?php

namespace App\Http\Controllers\Admin\ERP;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function customers()
    {
        $latestSale = function ($column) {
            return Sale::select($column)
                ->whereColumn('customer_id', 'customers.id')
                ->latest('created_at')
                ->latest('id')
                ->limit(1);
        };

        $customers = Customer::withCount([
            'sales',
            'sales as paid_orders_count' => function ($query) {
                $query->where('payment_status', 'paid');
            },
            'sales as pending_orders_count' => function ($query) {
                $query->where('payment_status', 'pending');
            },
            'sales as attention_orders_count' => function ($query) {
                $query->whereIn('payment_status', ['failed', 'review']);
            },
        ])
            ->addSelect([
                'confirmed_order_total' => Sale::selectRaw('COALESCE(SUM(payable_amount), 0)')
                    ->whereColumn('customer_id', 'customers.id')
                    ->where('payment_status', 'paid'),
                'latest_order_reference' => $latestSale('reference_no'),
                'latest_order_payment_status' => $latestSale('payment_status'),
                'latest_order_status' => $latestSale('order_status'),
                'latest_order_total' => $latestSale('payable_amount'),
                'latest_order_date' => $latestSale('created_at'),
                'latest_delivery_address' => $latestSale('delivery_address'),
                'latest_delivery_phone' => $latestSale('delivery_phone'),
            ])
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.erp.customers', [
            'customers' => $customers,
        ]);
    }

    public function customerOrders(Customer $customer)
    {
        $orders = $customer->sales()
            ->with('items.product.storeProduct')
            ->latest('created_at')
            ->latest('id')
            ->get();

        return view('admin.erp.customerOrders', compact('customer', 'orders'));
    }

    public function deleteCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back();
        }

        $customer = Customer::findOrFail($request->customer_id);
        try {
            $customer->delete();
            alert()->success('Deleted', 'Customer account removed successfully.')->persistent('Close');
        } catch (\Throwable $exception) {
            Log::error('Customer deletion failed: ' . $exception->getMessage());
            alert()->error('Error', 'Customer account could not be removed.')->persistent('Close');
        }

        return redirect()->back();
    }
}
