<?php

namespace App\Mail\Production;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InsufficientStock extends Mailable
{
    use Queueable, SerializesModels;

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