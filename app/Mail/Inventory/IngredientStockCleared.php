<?php

namespace App\Mail\Inventory;

use App\Mail\QueuedMailable;

class IngredientStockCleared extends QueuedMailable
{
    public $ingredient;
    public $ingredientName;
    public $previousStock;
    public $newStock;
    public $baseUnit;
    public $averageCost;
    public $totalLostValue;
    public $user;
    public $reason;
    public $notes;
    public $accountingType; // 'operational' or 'write_off'
    public $ipAddress;
    public $timestamp;
    public $isBulk;
    public $affectedIngredientsCount;
    public $totalUnitsCleared;

    public function __construct(
        $ingredient,
        $previousStock,
        $user,
        $reason,
        $notes = null,
        $accountingType = 'operational',
        $isBulk = false,
        $affectedIngredientsCount = 1,
        $totalUnitsCleared = 0,
        $averageCost = 0,
        $totalLostValue = 0
    ) {
        $this->ingredient = $ingredient;
        $this->ingredientName = $ingredient ? $ingredient->name : 'All Catalog Raw Materials';
        $this->previousStock = (float) $previousStock;
        $this->newStock = 0;
        $this->baseUnit = $ingredient && $ingredient->baseUnit ? ($ingredient->baseUnit->symbol ?: $ingredient->baseUnit->name) : 'Units';
        $this->averageCost = (float) $averageCost;
        $this->totalLostValue = (float) ($totalLostValue ?: ($this->previousStock * $this->averageCost));
        $this->user = $user;
        $this->reason = $reason;
        $this->notes = $notes;
        $this->accountingType = in_array($accountingType, ['operational', 'write_off'], true) ? $accountingType : 'operational';
        $this->ipAddress = request() ? request()->ip() : 'CLI/System';
        $this->timestamp = now()->timezone(config('app.timezone', 'Africa/Lagos'))->format('M d, Y h:i:s A T');
        $this->isBulk = $isBulk;
        $this->affectedIngredientsCount = $affectedIngredientsCount;
        $this->totalUnitsCleared = (float) ($totalUnitsCleared ?: $previousStock);
    }

    public function build()
    {
        $scopePrefix = $this->isBulk ? '[FULL RESET]' : '[INGREDIENT CLEARED]';
        $subject = "{$scopePrefix} Raw Material Stock Depleted: {$this->ingredientName}";

        return $this->mailer('admin')
            ->from(config('mail.mailers.admin.from.address', 'moshedib@moshedibles.com'), config('mail.mailers.admin.from.name', 'Moshel Admin'))
            ->subject($subject)
            ->view('mail.inventory.ingredientStockCleared');
    }
}
