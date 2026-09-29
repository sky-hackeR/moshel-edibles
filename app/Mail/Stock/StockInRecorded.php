<?php

namespace App\Mail\Stock;

use App\Mail\QueuedMailable;

class StockInRecorded extends QueuedMailable
{
    public $stockIn;
    public $totalSpent;
    public $user;

    public function __construct($stockIn, $totalSpent, $user)
    {
        $this->stockIn = $stockIn;
        $this->totalSpent = $totalSpent;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Stock Purchase Recorded: ' . $this->stockIn->reference)
                    ->view('mail.stock.stockInRecorded');
    }
}