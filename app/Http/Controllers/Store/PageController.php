<?php

namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Controllers\Controller;


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
        $products = Product::where('is_active', true)
        ->with(['recipe.items.ingredient'])
        ->get();

        return view('store.products', [
            'products' => $products
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