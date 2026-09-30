<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index()
    {
        $orders = Sale::where('customer_id', auth('customer')->id())
            ->with('items.product.storeProduct')
            ->latest()
            ->get();
        return view('store.account.index', compact('orders'));
    }

    public function showOrder(Sale $sale)
    {
        abort_unless(
            $sale->customer_id === auth('customer')->id() && $sale->user_type === 'customer',
            404
        );

        $order = $sale->load('items.product.storeProduct');

        return view('store.account.order', compact('order'));
    }

    public function profile()
    {
        return view('store.account.profile', ['customer' => auth('customer')->user()]);
    }

    public function updateProfile(Request $request)
    {
        $customer = auth('customer')->user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:1000',
        ];

        if ($request->filled('password')) {
            $rules['current_password'] = [
                'required',
                function ($attribute, $value, $fail) use ($customer) {
                    if (!Hash::check($value, $customer->password)) {
                        $fail('The current password entered is incorrect.');
                    }
                }
            ];
            $rules['password'] = 'required|min:6|confirmed';
        }

        $data = $request->validate($rules);

        $emailChanged = $customer->email !== $data['email'];

        $customer->name = $data['name'];
        $customer->email = $data['email'];
        $customer->phone = !empty($data['phone']) ? $data['phone'] : null;
        $customer->address = !empty($data['address']) ? $data['address'] : null;

        if (!empty($data['password'])) {
            $customer->password = Hash::make($data['password']);
        }

        if ($emailChanged) {
            $customer->email_verified_at = null;
            $customer->status = 'inactive';
        }

        $customer->save();

        if ($emailChanged) {
            $customer->sendEmailVerificationNotification();
            auth('customer')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('store.welcome')
                ->with('auth_modal', 'login')
                ->with('verification_sent', true)
                ->with('verification_email', $customer->email);
        }

        return redirect()->route('customer.account.profile')->with('success', 'Your profile details have been saved successfully.');
    }

    public function addresses()
    {
        $customer = auth('customer')->user();
        $pastAddresses = Sale::where('customer_id', $customer->id)
            ->whereNotNull('delivery_address')
            ->where('delivery_address', '!=', '')
            ->latest()
            ->get(['delivery_address', 'delivery_phone', 'created_at'])
            ->unique('delivery_address');

        return view('store.account.addresses', compact('customer', 'pastAddresses'));
    }
}