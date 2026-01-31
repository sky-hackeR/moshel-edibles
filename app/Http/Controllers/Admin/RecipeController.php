<?php

namespace App\Http\Controllers\Admin;

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
use Illuminate\Http\Request;

// Mailables
use App\Mail\Recipe\RecipeUpdated;
use App\Mail\Recipe\RecipeDeleted;

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

class RecipeController extends Controller
{
    public function recipes(){
        $recipes = Recipe::with(['product', 'items.ingredient', 'items.unit'])->latest()->get();
        $products = Product::where('is_active', true)->orderBy('name', 'asc')->get();
        $ingredients = Ingredient::where('is_active', true)->orderBy('name', 'asc')->get();
        $units = Unit::all();
        return view('admin.recipes', [
            'recipes'     => $recipes,
            'products'    => $products,
            'ingredients' => $ingredients,
            'units'       => $units,
        ]);
    }  

    public function newRecipe(Request $request, UnitConverter $converter){
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id|unique:recipes,product_id',
            'name'       => 'required',
            'items'      => 'required|array|min:1',
            'items.*.ingredient_id' => 'required|exists:ingredients,id',
            'items.*.unit_id'       => 'required|exists:units,id',
            'items.*.quantity'      => 'required|numeric|min:0.001',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        DB::transaction(function () use ($request, $converter) {
            $recipe = Recipe::create([
                'product_id' => $request->product_id,
                'name'       => $request->name,
                'note'       => $request->note,
                'is_active'  => true,
            ]);

            foreach ($request->items as $item) {
                $unit = Unit::findOrFail($item['unit_id']);

                $baseQty = $converter->toBase(
                    (float) $item['quantity'],
                    $unit->symbol,
                    $unit->unit_type
                );

                RecipeItem::create([
                    'recipe_id'     => $recipe->id,
                    'ingredient_id' => $item['ingredient_id'],
                    'unit_id'       => $item['unit_id'],
                    'quantity'      => $item['quantity'],
                    'base_quantity' => $baseQty,
                ]);
            }
        });

        alert()->success('Success', 'Recipe created successfully')->persistent('Close');
        return redirect()->back();
    }

    public function deleteRecipe(Request $request){
        $validator = Validator::make($request->all(), [
            'recipe_id' => 'required|exists:recipes,id',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back();
        }

        $recipe = Recipe::findOrFail($request->recipe_id);
        $user = Auth::user();
        $recipeName = $recipe->name;

        DB::beginTransaction();
        try {
            $recipe->items()->delete();
            $recipe->delete();

            // Notify Admins
            $admins = Admin::all();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new RecipeDeleted($recipeName, $user));
            }

            DB::commit();
            alert()->success('Deleted', 'Recipe deleted successfully')->persistent('Close');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Recipe Deletion Failed: " . $e->getMessage());
            alert()->error('Error', 'Failed to delete recipe.')->persistent('Close');
        }

        return redirect()->back();
    }

    public function updateRecipe(Request $request){
        $recipe = Recipe::findOrFail($request->recipe_id);

        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'items' => 'required|array|min:1',
            'items.*.ingredient_id' => 'required|exists:ingredients,id',
            'items.*.unit_id'       => 'required|exists:units,id',
            'items.*.quantity'      => 'required|numeric|min:0.001',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $recipe->update([
                'name'      => $request->name,
                'note'      => $request->note,
                'is_active' => $request->has('is_active'),
            ]);

            $recipe->items()->delete();

            foreach ($request->items as $item) {
                $unit = Unit::findOrFail($item['unit_id']);

                RecipeItem::create([
                    'recipe_id'     => $recipe->id,
                    'ingredient_id' => $item['ingredient_id'],
                    'unit_id'       => $item['unit_id'],
                    'quantity'      => $item['quantity'],
                    'base_quantity' => $item['quantity'] * $unit->multiplier,
                ]);
            }

            // Notify Admins
            $admins = Admin::all();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new RecipeUpdated($recipe, Auth::user()));
            }

            DB::commit();
            alert()->success('Updated', 'Recipe updated successfully')->persistent('Close');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Recipe Update Failed: " . $e->getMessage());
            alert()->error('Error', 'Update failed: ' . $e->getMessage())->persistent('Close');
        }

        return redirect()->back();
    }
}