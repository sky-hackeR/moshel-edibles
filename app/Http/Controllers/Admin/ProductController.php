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
use App\Mail\Product\PriceUpdated;
use App\Mail\Product\DeletionAttempt as ProductDeletionMail;

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
            'sales_unit' => 'required|string|max:20',
            'selling_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        Product::create([
            'name'          => $request->name,
            'slug'          => Str::slug($request->name),
            'sales_unit'    => $request->sales_unit,
            'selling_price' => $request->selling_price,
            'is_active'     => $request->has('is_active'),
            'stock_on_hand' => 0,
        ]);

        alert()->success('Success', 'Product added successfully')->persistent('Close');
        return redirect()->back();
    }

    public function updateProduct(Request $request){
        $product = Product::findOrFail($request->product_id);
        $oldPrice = $product->selling_price;

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:products,name,' . $product->id,
            'sales_unit' => 'required|string|max:20',
            'selling_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $product->update([
            'name'          => $request->name,
            'slug'          => Str::slug($request->name),
            'sales_unit'    => $request->sales_unit,
            'selling_price' => $request->selling_price,
            'is_active'     => $request->has('is_active'),
        ]);

        // Notify if price changed
        if ($oldPrice != $request->selling_price) {
            try {
                $recipients = Admin::all(); // Could also include Staff/Sales managers
                foreach($recipients as $recipient) {
                    Mail::to($recipient->email)->send(new PriceUpdated($product, $oldPrice, Auth::user()));
                }
            } catch (\Exception $e) {
                Log::error("Price update mail failed: " . $e->getMessage());
            }
        }

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
            $reason = "Product has a recipe attached.";
            $this->sendSecurityAlert($product, Auth::user(), $reason);

            alert()->error('Forbidden', $reason)->persistent('Close');
            return redirect()->back();
        }

        $product->delete();
        alert()->success('Deleted', 'Product deleted successfully')->persistent('Close');
        return redirect()->back();
    }

    private function sendSecurityAlert($product, $user, $reason) {
        try {
            $admin = Admin::first();
            if ($admin) {
                // You can reuse the logic of DeletionAttempt here
                Mail::to($admin->email)->send(new ProductDeletionMail($product, $user, $reason));
            }
        } catch (\Exception $e) {
            Log::error("Security Alert Mail failed: " . $e->getMessage());
        }
    }
}