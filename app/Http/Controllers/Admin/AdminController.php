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
use App\Models\Staff;
use App\Models\Unit;
use App\Models\Ingredient;
use App\Models\Inventory;
use App\Models\StockIn;
use App\Models\StockInItem;
use App\Models\Recipe;
use App\Models\RecipeItem;
use App\Models\Product;

use SweetAlert;
use Alert;
use Log;
use Carbon\Carbon;

class AdminController extends Controller
{

    protected UnitConverter $converter;

    public function __construct(UnitConverter $converter){
        $this->converter = $converter;
    }

    public function index(){
        $setting = Setting::first();
    
        if (!$setting || empty($setting->favicon) || empty($setting->site_name) || empty($setting->logo) || empty($setting->description)) {
            return view('admin.siteSettings', [
                'setting' => $setting
            ]);
        }
    
        return view('admin.home');
    }

    //GLOBAL SITE SETTINGS LOGIC
    public function siteSettings(){
        $setting = Setting::first();
        return view('admin.siteSettings', [
            'setting' => $setting,
        ]);
    }

    public function unitManagement(){
        $units = Unit::all();
        return view('admin.unitManagement', [
            'units' => $units
        ]);
    }

    public function ingredients(){
        $ingredients = Ingredient::with('baseUnit')->get();
        $units = Unit::active()->whereColumn('symbol', 'base_unit')->get();

        return view('admin.ingredients', [
            'ingredients' => $ingredients,
            'units' => $units,
        ]);
    }

    public function inventory(){
        $inventories = Inventory::with(['ingredient.baseUnit'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('admin.inventory', [
            'inventories' => $inventories,
        ]);
    }


    public function stockIn(){
        $stockIns = StockIn::with([
                'items',
                'items.ingredient',
                'items.unit',
            ])
            ->latest()
            ->get();

        $stats = [
            // Number of purchases today
            'today' => StockIn::whereDate('created_at', today())->count(),

            // Total money spent this month
            'month' => StockInItem::whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->sum('total_price'),

            // Items restocked today
            'items' => StockInItem::whereDate('created_at', today())->count(),

            // Last purchase date
            'last'  => StockIn::latest()->value('purchase_date'),
        ];

        return view('admin.stockIn', [
            'ingredients' => Ingredient::where('is_active', true)->get(),
            'units'       => Unit::where('use_for_purchase', true)->get(),
            'stockIns'    => $stockIns,
            'stats'       => $stats,
        ]);
    }

    public function products(){
        $products = Product::latest()->get();
        return view('admin.products', [
            'products' => $products,
        ]);
    }

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


    public function updateSiteInfo(Request $request){
        $validator = Validator::make($request->all(), [
            'logo' => 'nullable|image',
            'favicon' => 'nullable|image',
            'description' => 'nullable|string',
            'site_name' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            alert()->error('Error', $validator->messages()->all()[0])->persistent('Close');
            return redirect()->back();
        }
    
        $siteInfo = new Setting;
        if(!empty($request->site_info_id) && !$siteInfo = Setting::find($request->site_info_id)){
            alert()->error('Oops', 'Invalid Site Information')->persistent('Close');
            return redirect()->back();
        }
    
        if (!empty($request->site_name)) {
            $siteInfo->site_name = $request->site_name;
        }
    
        if (!empty($request->description)) {
            $siteInfo->description = $request->description;
        }
    
        // Save logo
        $logoUrl = null;
        if ($request->hasFile('logo')) {
            $logoUrl = 'uploads/siteInfo/' .'logo'.'.'.$request->file('logo')->getClientOriginalExtension();
            $logo = $request->file('logo')->move('uploads/siteInfo', $logoUrl);
            $siteInfo->logo = $logoUrl;
        }
    
        // Save favicon
        $faviconUrl = null;
        if ($request->hasFile('favicon')) {
            $faviconUrl = 'uploads/siteInfo/' .'favicon'.'.'.$request->file('favicon')->getClientOriginalExtension();
            $favicon = $request->file('favicon')->move('uploads/siteInfo', $faviconUrl);
            $siteInfo->favicon = $faviconUrl;
        }
    
        if($siteInfo->save()){
            alert()->success('Changes Saved', 'Site information changes saved successfully')->persistent('Close');
            return redirect()->back();
        }
    
        alert()->error('Oops!', 'Something went wrong')->persistent('Close');
        return redirect()->back();
    }

    public function newUnit(Request $request){
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:50',
            'symbol'    => 'required|string|max:10',
            'unit_type' => 'required|in:mass,volume,count',
            'base_unit' => 'required|string|max:10',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        Unit::create([
            'name'             => $request->name,
            'symbol'           => strtolower($request->symbol),
            'unit_type'        => $request->unit_type,
            'base_unit'        => $request->base_unit,
            'use_for_purchase' => $request->has('use_for_purchase'),
            'use_for_recipe'   => $request->has('use_for_recipe'),
            'is_active'        => true,
        ]);

        alert()->success('Success', 'Unit added successfully')->persistent('Close');
        return redirect()->back();
    }

    public function updateUnit(Request $request){
        $validator = Validator::make($request->all(), [
            'unit_id'   => 'required|exists:units,id',
            'name'      => 'required|string|max:50',
            'symbol'    => 'required|string|max:10',
            'unit_type' => 'required|in:mass,volume,count',
            'base_unit' => 'required|string|max:10',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back();
        }

        $unit = Unit::findOrFail($request->unit_id);

        $unit->fill([
            'name'             => $request->name,
            'symbol'           => strtolower($request->symbol),
            'unit_type'        => $request->unit_type,
            'base_unit'        => $request->base_unit,
            'use_for_purchase' => $request->has('use_for_purchase'),
            'use_for_recipe'   => $request->has('use_for_recipe'),
            'is_active'        => $request->has('is_active'),
        ]);

        if ($unit->isDirty()) {
            $unit->save();
            alert()->success('Updated', 'Unit updated successfully')->persistent('Close');
        } else {
            alert()->info('No Changes', 'No changes were made')->persistent('Close');
        }

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

        // Protect base units
        if (in_array($unit->symbol, ['g', 'ml', 'piece'])) {
            alert()->error('Forbidden', 'Base units cannot be deleted')->persistent('Close');
            return redirect()->back();
        }

        $unit->delete();

        alert()->success('Deleted', 'Unit deleted successfully')->persistent('Close');
        return redirect()->back();
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


    public function newStockIn(Request $request, UnitConverter $converter){
        $validator = Validator::make($request->all(), [
            'purchase_date'          => 'required|date',
            'items'                  => 'required|array|min:1',
            'items.*.ingredient_id'  => 'required|exists:ingredients,id',
            'items.*.unit_id'        => 'required|exists:units,id',
            'items.*.quantity'       => 'required|numeric|min:0.001',
            'items.*.total_price'    => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first());
            return back()->withInput();
        }

        DB::transaction(function () use ($request, $converter) {

            $stockIn = StockIn::create([
                'reference'     => 'STK-' . strtoupper(Str::random(8)),
                'purchase_date' => $request->purchase_date,
                'supplier'      => $request->supplier,
                'note'          => $request->note,
                'created_by'    => auth()->id(),
            ]);

            foreach ($request->items as $item) {

                $ingredient = Ingredient::findOrFail($item['ingredient_id']);
                $unit       = Unit::findOrFail($item['unit_id']);

                $baseQty = $converter->toBase(
                    (float) $item['quantity'],
                    $unit->symbol,
                    $unit->unit_type
                );

                $unitPrice = $item['total_price'] / $item['quantity'];

                StockInItem::create([
                    'stock_in_id'   => $stockIn->id,
                    'ingredient_id' => $ingredient->id,
                    'unit_id'       => $unit->id,
                    'quantity'      => $item['quantity'],
                    'unit_price'    => $unitPrice,
                    'total_price'   => $item['total_price'],
                    'base_quantity' => $baseQty,
                ]);

                Inventory::firstOrCreate(
                    ['ingredient_id' => $ingredient->id],
                    ['quantity' => 0]
                )->increment('quantity', $baseQty);
            }
        });

        alert()->success('Success', 'Stock added successfully');
        return back();
    }


    public function newProduct(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:products,name',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        Product::create([
            'name'      => $request->name,
            'slug'      => Str::slug($request->name),
            'is_active' => $request->has('is_active'),
        ]);

        alert()->success('Success', 'Product added successfully')->persistent('Close');
        return redirect()->back();
    }

    public function updateProduct(Request $request){
        $product = Product::findOrFail($request->product_id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:products,name,' . $product->id,
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $product->update([
            'name'      => $request->name,
            'slug'      => Str::slug($request->name),
            'is_active' => $request->has('is_active'),
        ]);

        alert()->success('Updated', 'Product updated successfully')->persistent('Close');
        return redirect()->back();
    }

    public function deleteProduct(Request $request){
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back();
        }

        $product = Product::findOrFail($request->product_id);

        if ($product->recipe) {
            alert()->error(
                'Forbidden',
                'Product has a recipe attached and cannot be deleted'
            )->persistent('Close');

            return redirect()->back();
        }

        $product->delete();

        alert()->success('Deleted', 'Product deleted successfully')->persistent('Close');
        return redirect()->back();
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

        $recipe->items()->delete();
        $recipe->delete();

        alert()->success('Deleted', 'Recipe deleted successfully')->persistent('Close');
        return redirect()->back();
    }


    public function updateRecipe(Request $request){
        $recipe = Recipe::findOrFail($request->recipe_id);

        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'items' => 'required|array|min:1',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back();
        }

        DB::transaction(function () use ($request, $recipe) {

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
        });

        alert()->success('Updated', 'Recipe updated successfully')->persistent('Close');
        return redirect()->back();
    }



    
}