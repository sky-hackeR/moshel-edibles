<?php

namespace App\Mail\Store;

use App\Mail\QueuedMailable;

class OrderReceived extends QueuedMailable
{
    public $sale;

    public function __construct($sale)
    {
        $this->sale = $sale;
    }

    public function build()
    {
        return $this->subject('New storefront order: ' . $this->sale->reference_no)
            ->view('mail.store.orderReceived');
    }
}