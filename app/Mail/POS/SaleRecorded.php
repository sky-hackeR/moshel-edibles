<?php

namespace App\Mail\POS;

use App\Mail\QueuedMailable;

class SaleRecorded extends QueuedMailable
{
    public $sale;
    public $seller;

    public function __construct($sale, $seller)
    {
        $this->sale = $sale;
        $this->seller = $seller;
    }

    public function build()
    {
        $subject = ($this->sale->discount_amount > 0) 
            ? '⚠️ Sale Recorded with Discount: ' . $this->sale->reference_no 
            : 'New Sale Recorded: ' . $this->sale->reference_no;

        return $this->subject($subject)->view('mail.pos.saleRecorded');
    }
}