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

class ProductController extends Controller
{
    public function products(){
        $products = Product::latest()->get();
        return view('admin.products', [
            'products' => $products,
        ]);
    }

    public function newProduct(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:products,name',
            'sales_unit' => 'required|string|max:20', // Added
            'selling_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        Product::create([
            'name'          => $request->name,
            'slug'          => Str::slug($request->name),
            'sales_unit'    => $request->sales_unit, // Added
            'selling_price' => $request->selling_price,
            'is_active'     => $request->has('is_active'),
            'stock_on_hand' => 0,
        ]);

        alert()->success('Success', 'Product added successfully')->persistent('Close');
        return redirect()->back();
    }

    public function updateProduct(Request $request){
        $product = Product::findOrFail($request->product_id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:products,name,' . $product->id,
            'sales_unit' => 'required|string|max:20', // Added
            'selling_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $product->update([
            'name'          => $request->name,
            'slug'          => Str::slug($request->name),
            'sales_unit'    => $request->sales_unit, // Added
            'selling_price' => $request->selling_price,
            'is_active'     => $request->has('is_active'),
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
            alert()->error('Forbidden', 'Product has a recipe attached and cannot be deleted')->persistent('Close');
            return redirect()->back();
        }

        $product->delete();

        alert()->success('Deleted', 'Product deleted successfully')->persistent('Close');
        return redirect()->back();
    }
}