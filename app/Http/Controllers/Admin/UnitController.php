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


use App\Models\SiteInfo as Setting;
use App\Models\Admin;
use App\Models\Staff;
use App\Models\Unit;
use App\Models\Ingredient;
use App\Models\Inventory;
use App\Models\StockIn;
use App\Models\StockInItem;
use App\Models\Recipe;
use App\Models\RecipeItem;
use App\Models\Product;
use App\Models\Production;
use App\Models\ProductionItem;


use SweetAlert;
use Alert;
use Log;
use Carbon\Carbon;

class UnitController extends Controller
{
    //
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

        Unit::create([
            'name'             => $request->name,
            'symbol'           => strtolower($request->symbol),
            'unit_type'        => $request->unit_type,
            'base_multiplier'  => $request->base_multiplier,
            'base_unit'        => $baseUnit,
            'use_for_purchase' => $request->has('use_for_purchase'),
            'use_for_recipe'   => $request->has('use_for_recipe'),
            'is_active'        => true,
        ]);

        alert()->success('Success', 'Unit added successfully');
        return redirect()->back();
    }

    public function updateUnit(Request $request) {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'name'    => 'required|string|max:50',
        ]);

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

        alert()->success('Updated', 'Unit details updated');
        return redirect()->back();
    }

    // public function deleteUnit(Request $request) {
    //     $unit = Unit::findOrFail($request->unit_id);

    //     if (in_array($unit->symbol, ['g', 'ml', 'pcs'])) {
    //         alert()->error('Restricted', 'System base units cannot be deleted.')->persistent('Close');
    //         return redirect()->back();
    //     }

    //     $unit->delete();
    //     alert()->success('Deleted', 'Unit removed successfully');
    //     return redirect()->back();
    // }

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

        $unit->delete();

        alert()->success('Deleted', 'Unit deleted successfully')->persistent('Close');
        return redirect()->back();
    }

}
