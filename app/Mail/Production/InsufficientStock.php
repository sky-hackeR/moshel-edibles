<?php

namespace App\Mail\Production;

use App\Mail\QueuedMailable;

class InsufficientStock extends QueuedMailable
{
    public $product;
    public $ingredientName;
    public $needed;

    public function __construct($product, $ingredientName, $needed)
    {
        $this->product = $product;
        $this->ingredientName = $ingredientName;
        $this->needed = $needed;
    }

    public function build()
    {
        return $this->subject('⚠️ Production Blocked: Insufficient Stock')
                    ->view('mail.production.insufficientStock');
    }
}