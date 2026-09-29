<?php

namespace App\Services\Store;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    private const SESSION_KEY = 'store_cart';

    public function add(Product $product, int $quantity = 1): void
    {
        $cart = session()->get(self::SESSION_KEY, []);
        $productId = (string) $product->id;
        $cart[$productId] = min(($cart[$productId] ?? 0) + $quantity, (int) $product->stock_on_hand);

        session()->put(self::SESSION_KEY, array_filter($cart, function ($itemQuantity) {
            return $itemQuantity > 0;
        }));
    }

    public function update(array $quantities): void
    {
        $cart = session()->get(self::SESSION_KEY, []);
        foreach ($quantities as $productId => $quantity) {
            $product = Product::find($productId);
            if (!$product || !$product->is_active || $quantity < 1) {
                unset($cart[$productId]);
                continue;
            }

            $cart[(string) $productId] = min((int) $quantity, (int) $product->stock_on_hand);
        }

        session()->put(self::SESSION_KEY, array_filter($cart, function ($itemQuantity) {
            return $itemQuantity > 0;
        }));
    }

    public function remove(int $productId): void
    {
        $cart = session()->get(self::SESSION_KEY, []);
        unset($cart[(string) $productId]);
        session()->put(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public function items(): Collection
    {
        $cart = session()->get(self::SESSION_KEY, []);
        if (empty($cart)) {
            return collect();
        }

        $products = Product::whereIn('id', array_keys($cart))
            ->where('is_active', true)
            ->with('storeProduct')
            ->get()
            ->keyBy('id');

        return collect($cart)->map(function ($quantity, $productId) use ($products) {
            $product = $products->get((int) $productId);
            if (!$product) {
                return null;
            }

            $quantity = min((int) $quantity, (int) $product->stock_on_hand);
            return [
                'product' => $product,
                'quantity' => $quantity,
                'unit_price' => (float) $product->selling_price,
                'subtotal' => round((float) $product->selling_price * $quantity, 2),
            ];
        })->filter(function ($item) {
            return $item !== null && $item['quantity'] > 0;
        })->values();
    }

    public function total(): float
    {
        return round($this->items()->sum('subtotal'), 2);
    }

    public function count(): int
    {
        return (int) $this->items()->sum('quantity');
    }
}