<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;
use AlAminFirdows\LaravelMultiAuth\Traits\LogsoutGuard;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, LogsoutGuard {
        LogsoutGuard::logout insteadof AuthenticatesUsers;
    }

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo = '/account';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('customer.guest', ['except' => 'logout']);
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('store.auth.login');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('customer');
    }

    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password') + ['status' => 'active'];
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $email = $request->input($this->username());
        $customer = Customer::where($this->username(), $email)->first();

        if (!$customer) {
            return redirect()->back()
                ->withInput($request->only($this->username()))
                ->with('auth_modal', 'login')
                ->with('customer_not_found', true)
                ->withErrors([$this->username() => 'No customer account was found for this email address.']);
        }

        if (
            Hash::check($request->input('password', ''), $customer->password) &&
            ($customer->status !== 'active' || !$customer->email_verified_at)
        ) {
            return redirect()->back()
                ->withInput($request->only($this->username()))
                ->with('auth_modal', 'login')
                ->with('verification_pending', $customer->email)
                ->withErrors([$this->username() => 'Your password is correct, but this account needs email verification before you can sign in.']);
        }

        return redirect()->back()
            ->withInput($request->only($this->username()))
            ->with('auth_modal', 'login')
            ->withErrors([$this->username() => trans('auth.failed')]);
    }
}
