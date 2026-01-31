<?php

namespace App\Mail\Stock;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LowStockAlert extends Mailable
{
    use Queueable, SerializesModels;

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