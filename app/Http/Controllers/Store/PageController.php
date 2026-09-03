<?php

namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\StoreProduct;

class PageController extends Controller
{
    /**
     * Show the public marketing landing page.
     */
    public function index()
    {
        $featuredProducts = Product::where('is_active', true)
            ->take(3)
            ->get();

        return view('store.welcome', [
            'featuredProducts' => $featuredProducts
        ]);
    }


    /**
     * Show the public products catalog list view.
     */
    public function products()
    {
        $products = StoreProduct::where('is_published', true)
            ->with([
                'product.recipe.items.ingredient',
                'images',
            ])
            ->latest()
            ->paginate(9);

        return view('store.products', [
            'products' => $products,
        ]);
    }

    // public function productDetails($slug){
        
    //     $storeProduct = StoreProduct::whereHas('product', function ($query) use ($slug) {
    //     $query->where('slug', $slug);
    //     })->with(['product.recipe.items.ingredient', 'images'])->firstOrFail();

    //     return view('store.productDetails', [
    //         'product' => $storeProduct,
    //     ]);
    // }

    public function productDetails($slug){
        $storeProduct = StoreProduct::whereHas('product', function ($query) use ($slug) {
            $query->where('slug', $slug);
        })->with([
            'product',
            'product.recipe.items.ingredient', 
            'images' => function ($query) {
                $query->orderBy('is_primary', 'desc');
            }
        ])->firstOrFail();

        $relatedProducts = StoreProduct::where('id', '!=', $storeProduct->id)
        ->with(['product', 'images'])
        ->take(4)
        ->get();

        return view('store.productDetails', [
            'storeProduct' => $storeProduct,
            'relatedProducts' => $relatedProducts,
        ]);
    }


    /**
     * Show the about us page.
     */
    public function about()
    {
        return view('store.about');   
    }
}