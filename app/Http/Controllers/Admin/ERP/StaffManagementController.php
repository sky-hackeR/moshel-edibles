<?php

namespace App\Http\Controllers\Admin\ERP;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StaffManagementController extends Controller
{
    public function staffList()
    {
        $staffs = Staff::orderBy('name', 'asc')->get();

        return view('admin.erp.staffs', [
            'staffs' => $staffs,
        ]);
    }

    public function newStaff(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:staff,email',
        ]);

        if ($validator->fails()) {
            alert()->error('Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $staff = new Staff();
        $staff->fill([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make(Str::random(64)),
        ]);

        if ($staff->save()) {
            try {
                $resetStatus = Password::broker('staff')->sendResetLink(['email' => $staff->email]);
                if ($resetStatus !== Password::RESET_LINK_SENT) {
                    throw new \RuntimeException('Staff password setup email could not be queued.');
                }
                alert()->success('Success', 'Staff account created and a secure password setup link was sent')->persistent('Close');
            } catch (\Exception $e) {
                Log::error("Staff Email failed: " . $e->getMessage());
                alert()->success('Success', 'Staff created, but welcome email failed.')->persistent('Close');
            }
        } else {
            alert()->error('Oops!', 'Something went wrong while creating the staff')->persistent('Close');
        }

        return redirect()->back();
    }

    public function deleteStaff(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'staff_id' => 'required|exists:staff,id',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back();
        }

        $staff = Staff::findOrFail($request->staff_id);

        DB::beginTransaction();
        try {
            $staff->delete();
            DB::commit();

            alert()->success('Deleted', 'Staff account removed successfully')->persistent('Close');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Staff Deletion Failed: " . $e->getMessage());
            alert()->error('Error', 'Failed to remove staff account.')->persistent('Close');
        }

        return redirect()->back();
    }
}
