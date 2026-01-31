<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

// Models
use App\Models\SiteInfo as Setting;
use App\Models\Admin;
use App\Models\Unit;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\Product;

// Mailables
use App\Mail\Unit\UnitModified;

use SweetAlert;
use Alert;
use Log;
use Carbon\Carbon;

class UnitController extends Controller
{
    public function unitManagement(){
        $units = Unit::all();
        return view('admin.unitManagement', [
            'units' => $units
        ]);
    }

    public function newUnit(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'            => 'required|string|max:50',
            'symbol'          => 'required|string|max:10',
            'unit_type'       => 'required|in:mass,volume,count',
            'base_multiplier' => 'required|numeric|min:0.0001',
        ]);

        if ($validator->fails()) {
            alert()->error('Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $baseMapping = ['mass' => 'g', 'volume' => 'ml', 'count' => 'pcs'];
        $baseUnit = $baseMapping[$request->unit_type];

        $unit = Unit::create([
            'name'             => $request->name,
            'symbol'           => strtolower($request->symbol),
            'unit_type'        => $request->unit_type,
            'base_multiplier'  => $request->base_multiplier,
            'base_unit'        => $baseUnit,
            'use_for_purchase' => $request->has('use_for_purchase'),
            'use_for_recipe'   => $request->has('use_for_recipe'),
            'is_active'        => true,
        ]);

        $this->notifyUnitChange($unit, 'Created');

        alert()->success('Success', 'Unit added successfully');
        return redirect()->back();
    }

    public function updateUnit(Request $request) {
        $validator = Validator::make($request->all(), [
            'unit_id' => 'required|exists:units,id',
            'name'    => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            alert()->error('Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back();
        }

        $unit = Unit::findOrFail($request->unit_id);
        $isBaseUnit = in_array($unit->symbol, ['g', 'ml', 'pcs']);
        $baseMapping = ['mass' => 'g', 'volume' => 'ml', 'count' => 'pcs'];

        if ($isBaseUnit) {
            $unit->update([
                'name' => $request->name,
                'is_active' => true,
            ]);
        } else {
            $unit->update([
                'name'             => $request->name,
                'symbol'           => strtolower($request->symbol),
                'unit_type'        => $request->unit_type,
                'base_multiplier'  => $request->base_multiplier,
                'base_unit'        => $baseMapping[$request->unit_type],
                'use_for_purchase' => $request->has('use_for_purchase'),
                'use_for_recipe'   => $request->has('use_for_recipe'),
                'is_active'        => $request->has('is_active'),
            ]);
        }

        $this->notifyUnitChange($unit, 'Updated');

        alert()->success('Updated', 'Unit details updated');
        return redirect()->back();
    }


    public function deleteUnit(Request $request){
        $validator = Validator::make($request->all(), [
            'unit_id' => 'required|exists:units,id',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back();
        }

        $unit = Unit::findOrFail($request->unit_id);

        if ($unit->base_multiplier == 1.0 && in_array($unit->symbol, ['g', 'ml', 'pcs'])) {
            alert()->error('Forbidden', 'Core base units cannot be deleted')->persistent('Close');
            return redirect()->back();
        }

        $this->notifyUnitChange($unit, 'Deleted');
        $unit->delete();

        alert()->success('Deleted', 'Unit deleted successfully')->persistent('Close');
        return redirect()->back();
    }

    /**
     * Helper to notify admins about unit changes
     */
    private function notifyUnitChange($unit, $action) {
        try {
            $admins = Admin::all();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new UnitModified($unit, $action, Auth::user()));
            }
        } catch (\Exception $e) {
            Log::error("Unit Notification Failed: " . $e->getMessage());
        }
    }
}