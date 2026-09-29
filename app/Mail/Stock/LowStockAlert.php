<?php

namespace App\Mail\Stock;

use App\Mail\QueuedMailable;

class LowStockAlert extends QueuedMailable
{
    public $lowStockItems;

    public function __construct($lowStockItems)
    {
        $this->lowStockItems = $lowStockItems;
    }

    public function build()
    {
        return $this->subject('⚠️ Inventory Alert: Low Stock Detected')
                    ->view('mail.stock.lowStockAlert');
    }
}