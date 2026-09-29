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
        $publishedProducts = StoreProduct::where('is_published', true)
            ->whereHas('product', function ($query) {
                $query->where('is_active', true);
            })
            ->with([
                'product',
                'images',
                'primaryImage',
            ])
            ->orderByDesc('is_featured')
            ->latest()
            ->get();

        $featuredProducts = $publishedProducts->where('is_featured', true);

        if ($featuredProducts->isEmpty()) {
            $featuredProducts = $publishedProducts->take(4);
        }

        $leadProduct = $featuredProducts->first() ?: $publishedProducts->first();
        $activeProducts = Product::where('is_active', true)->get();

        return view('store.welcome', [
            'featuredProducts' => $featuredProducts,
            'allProducts'      => $publishedProducts,
            'leadProduct'      => $leadProduct,
            'activeProducts'   => $activeProducts,
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