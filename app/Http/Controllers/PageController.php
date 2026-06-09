<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

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

        return view('welcome', [
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

        return view('products', [
            'products' => $products
        ]);
    }

    /**
     * Show the about us page.
     */
    public function about()
    {
        return view('about');   
    }
}