<?php
namespace App\Mail\Product;
use Illuminate\Mail\Mailable;

class PriceUpdated extends Mailable {
    public $product;
    public $oldPrice;
    public $user;

    public function __construct($product, $oldPrice, $user) {
        $this->product = $product;
        $this->oldPrice = $oldPrice;
        $this->user = $user;
    }

    public function build() {
        return $this->subject('Price Update: ' . $this->product->name)->view('mail.product.priceUpdated');
    }
}