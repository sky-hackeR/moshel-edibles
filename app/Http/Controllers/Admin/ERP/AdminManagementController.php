<?php

namespace App\Http\Controllers\Admin\ERP;

use App\Http\Controllers\Controller;
use App\Mail\Admin\AdminCreated as SecurityAlertMail;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminManagementController extends Controller
{
    public function adminList()
    {
        $admins = Admin::orderBy('name', 'asc')->get();

        return view('admin.erp.admins', [
            'admins' => $admins,
        ]);
    }

    public function newAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:admins,email',
        ]);

        if ($validator->fails()) {
            alert()->error('Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $admin = new Admin();
        $admin->fill([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make(Str::random(64)),
        ]);

        if ($admin->save()) {
            $setupEmailSent = false;
            try {
                $resetStatus = Password::broker('admins')->sendResetLink(['email' => $admin->email]);
                if ($resetStatus === Password::RESET_LINK_SENT) {
                    $setupEmailSent = true;
                } else {
                    Log::error("Admin password setup email failed: Password broker returned [{$resetStatus}].");
                }
            } catch (\Throwable $e) {
                Log::error("Admin password setup email failed: " . $e->getMessage());
            }

            try {
                if (Auth::user() && Auth::user()->email) {
                    Mail::to(Auth::user()->email)->send(new SecurityAlertMail($admin, Auth::user()));
                }
            } catch (\Throwable $e) {
                Log::error("Admin creation security alert email failed: " . $e->getMessage());
            }

            if ($setupEmailSent) {
                alert()->success('Success', 'Admin created and a secure password setup link was sent')->persistent('Close');
            } else {
                alert()->success('Success', 'Admin created, but email notifications failed. Check logs.')->persistent('Close');
            }
        } else {
            alert()->error('Oops!', 'Something went wrong while creating the admin')->persistent('Close');
        }

        return redirect()->back();
    }

    public function deleteAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'admin_id' => 'required|exists:admins,id',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back();
        }

        // Prevent self-deletion
        if ((int)$request->admin_id === (int)Auth::user()->id) {
            alert()->error('Action Denied', 'You cannot delete your own administrator account.')->persistent('Close');
            return redirect()->back();
        }

        $admin = Admin::findOrFail($request->admin_id);

        DB::beginTransaction();
        try {
            $admin->delete();
            DB::commit();

            alert()->success('Deleted', 'Administrator account removed successfully')->persistent('Close');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Admin Deletion Failed: " . $e->getMessage());
            alert()->error('Error', 'Failed to remove administrator account.')->persistent('Close');
        }

        return redirect()->back();
    }
}
