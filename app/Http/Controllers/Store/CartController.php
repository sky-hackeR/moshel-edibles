<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Store\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(CartService $cart)
    {
        return view('store.cart.index', [
            'items' => $cart->items(),
            'total' => $cart->total(),
        ]);
    }

    public function add(Request $request, CartService $cart)
    {
        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $product = Product::where('is_active', true)->findOrFail($data['product_id']);
        if ($product->stock_on_hand < $data['quantity']) {
            return back()->with('error', 'The requested quantity is not currently available.');
        }

        $cart->add($product, $data['quantity']);
        return redirect()->route('store.cart')->with('success', 'Product added to your cart.');
    }

    public function update(Request $request, CartService $cart)
    {
        $data = $request->validate(['quantities' => 'required|array']);
        $cart->update($data['quantities']);
        return redirect()->route('store.cart')->with('success', 'Cart updated.');
    }

    public function remove(int $product, CartService $cart)
    {
        $cart->remove($product);
        return redirect()->route('store.cart')->with('success', 'Product removed from your cart.');
    }
}