<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function resend(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
        ]);

        $customer = Customer::where('email', $data['email'])
            ->where(function ($query) {
                $query->whereNull('email_verified_at')
                    ->orWhere('status', '!=', 'active');
            })
            ->first();

        if ($customer) {
            $customer->sendEmailVerificationNotification();
        }

        return back()
            ->withInput(['email' => $data['email']])
            ->with('auth_modal', 'login')
            ->with('verification_sent', (bool) $customer)
            ->with('verification_email', $data['email']);
    }

    public function verify(Customer $customer, string $hash)
    {
        abort_unless(hash_equals(sha1($customer->email), $hash), 403);

        $customer->forceFill([
            'email_verified_at' => $customer->email_verified_at ?: now(),
            'status' => 'active',
        ])->save();

        return redirect()->route('store.welcome')
            ->with('auth_modal', 'login')
            ->with('status', 'Your email is verified and your account is active. You can now sign in.');
    }
}