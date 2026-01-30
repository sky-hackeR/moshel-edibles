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

use App\Services\UnitConversion\UnitConverter;

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

class IngredientController extends Controller
{
    //
    public function ingredients(){
        $ingredients = Ingredient::with('baseUnit')->get();
        $units = Unit::active()->whereColumn('symbol', 'base_unit')->get();

        return view('admin.ingredients', [
            'ingredients' => $ingredients,
            'units' => $units,
        ]);
    }

    public function newIngredient(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:ingredients,name',
            'base_unit_id' => 'required|exists:units,id',
        ]);

        if ($validator->fails()) {
            alert()
                ->error('Validation Error', $validator->messages()->first())
                ->persistent('Close');

            return redirect()->back()->withInput();
        }

        $ingredient = Ingredient::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'base_unit_id' => $request->base_unit_id,
            'is_active' => $request->has('is_active'),
        ]);

        Inventory::create([
            'ingredient_id' => $ingredient->id,
            'quantity' => 0,
            'average_cost' => 0,
        ]);

        alert()
            ->success('Success', 'Ingredient added successfully')
            ->persistent('Close');

        return redirect()->back();
    }

    public function updateIngredient(Request $request){
        $ingredient = Ingredient::findOrFail($request->ingredient_id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:ingredients,name,' . $ingredient->id,
            'base_unit_id' => 'required|exists:units,id',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $ingredient->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'base_unit_id' => $request->base_unit_id,
            'is_active' => $request->has('is_active'),
        ]);

        alert()->success('Updated', 'Ingredient updated successfully')->persistent('Close');
        return redirect()->back();
    }

    public function deleteIngredient(Request $request){
        $validator = Validator::make($request->all(), [
            'ingredient_id' => 'required|exists:ingredients,id',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back();
        }

        $ingredient = Ingredient::findOrFail($request->ingredient_id);

        // Safety checks
        if ($ingredient->inventory()->exists()) {
            alert()->error('Forbidden', 'Ingredient has inventory records and cannot be deleted')->persistent('Close');
            return redirect()->back();
        }

        if ($ingredient->recipeItems()->exists()) {
            alert()->error('Forbidden', 'Ingredient is used in recipes and cannot be deleted')->persistent('Close');
            return redirect()->back();
        }

        $ingredient->delete();

        alert()->success('Deleted', 'Ingredient deleted successfully')->persistent('Close');
        return redirect()->back();
    }
}
