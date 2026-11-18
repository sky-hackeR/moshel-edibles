<?php

namespace App\Mail\Product;

use App\Mail\QueuedMailable;

class StockCleared extends QueuedMailable
{
    public $product;
    public $productName;
    public $previousStock;
    public $newStock;
    public $salesUnit;
    public $user;
    public $reason;
    public $notes;
    public $accountingType; // 'operational' or 'write_off'
    public $ipAddress;
    public $timestamp;
    public $isBulk;
    public $affectedProductsCount;
    public $totalUnitsCleared;

    public function __construct(
        $product,
        $previousStock,
        $user,
        $reason,
        $notes = null,
        $accountingType = 'operational',
        $isBulk = false,
        $affectedProductsCount = 1,
        $totalUnitsCleared = 0
    ) {
        $this->product = $product;
        $this->productName = $product ? $product->name : 'All Catalog Products';
        $this->previousStock = $previousStock;
        $this->newStock = 0;
        $this->salesUnit = $product ? $product->sales_unit : 'Units';
        $this->user = $user;
        $this->reason = $reason;
        $this->notes = $notes;
        $this->accountingType = in_array($accountingType, ['operational', 'write_off'], true) ? $accountingType : 'operational';
        $this->ipAddress = request()->ip();
        $this->timestamp = now()->timezone(config('app.timezone', 'Africa/Lagos'))->format('M d, Y h:i:s A T');
        $this->isBulk = $isBulk;
        $this->affectedProductsCount = $affectedProductsCount;
        $this->totalUnitsCleared = $totalUnitsCleared ?: $previousStock;
    }

    public function build()
    {
        $scopePrefix = $this->isBulk ? '[FULL RESET]' : '[STOCK CLEARED]';
        $subject = "{$scopePrefix} Inventory Depleted: {$this->productName}";

        return $this->mailer('admin')
            ->from(config('mail.mailers.admin.from.address', 'moshedib@moshedibles.com'), config('mail.mailers.admin.from.name', 'Moshel Admin'))
            ->subject($subject)
            ->view('mail.product.stockCleared');
    }
}
