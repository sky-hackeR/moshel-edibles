<?php

namespace App\Mail\Product;

use App\Mail\QueuedMailable;

class DeletionAttempt extends QueuedMailable
{
    public $product;
    public $user;
    public $reason;

    public function __construct($product, $user, $reason)
    {
        $this->product = $product;
        $this->user = $user;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Action Blocked: Product Deletion Attempt')
            ->view('mail.product.deletionAttempt');
    }
}