<?php

namespace App\Http\Controllers\Admin\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StoreProduct;

class StoreController extends Controller
{
    //
    // public function home(){
    //     return view('admin.store.home');
    // }

    public function home(){
        $products = Product::with([
            'storeProduct.primaryImage',
        ])
            ->latest()
            ->get();

        $totalErpProducts = Product::count();

        $totalStoreProducts = StoreProduct::count();

        $publishedProducts = StoreProduct::where('is_published', true)
            ->count();

        $draftProducts = StoreProduct::where('is_published', false)
            ->count();

        $notListedProducts = Product::whereDoesntHave('storeProduct')
            ->count();

        $featuredProducts = StoreProduct::where('is_featured', true)
            ->count();

        return view('admin.store.home', [
            'products' => $products,
            'totalErpProducts' => $totalErpProducts,
            'totalStoreProducts' => $totalStoreProducts,
            'publishedProducts' => $publishedProducts,
            'draftProducts' => $draftProducts,
            'notListedProducts' => $notListedProducts,
            'featuredProducts' => $featuredProducts,
        ]);
    }


    /**
     * Add an ERP product to the storefront.
     */
    public function addProduct(Product $product)
    {
        StoreProduct::firstOrCreate(
            [
                'product_id' => $product->id,
            ],
            [
                'is_published' => false,
                'is_featured' => false,
            ]
        );

        alert()
            ->success('Product Added', "{$product->name} has been added to the storefront.")
            ->persistent('Close');

        return redirect()->back();
    }


    /**
     * Show the storefront product editor.
     */
    public function editProduct(Product $product)
    {
        $storeProduct = $product->storeProduct;

        if (!$storeProduct) {
            alert()
                ->error('Not Listed', 'This product has not been added to the storefront.')
                ->persistent('Close');

            return redirect()->back();
        }

        $storeProduct->load('images');

        return view('admin.store.product.edit', compact(
            'product',
            'storeProduct'
        ));
    }
}
