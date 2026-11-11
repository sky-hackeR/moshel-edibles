<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Inventory;
use App\Models\Admin;
use App\Mail\Stock\LowStockAlert;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CheckInventoryLevels extends Command
{
    /**
     * The name you use to run this in terminal: php artisan inventory:check
     */
    protected $signature = 'inventory:check';

    /**
     * The description shown when you run php artisan list
     */
    protected $description = 'Scan inventory for low stock items and email admins';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * This is what runs when the command is triggered
     */
    public function handle()
    {
        $this->info('Starting inventory scan...');

        try {
            $lowStockItems = Inventory::with(['ingredient.baseUnit'])
                ->get()
                ->filter(function($inventory) {
                    $ingredient = $inventory->ingredient;
                    if (!$ingredient) return false;

                    return $inventory->quantity <= $ingredient->effective_reorder_level;
                });

            if ($lowStockItems->isNotEmpty()) {
                $adminEmails = Admin::pluck('email')->toArray();
                
                if (!empty($adminEmails)) {
                    Mail::to($adminEmails)->send(new LowStockAlert($lowStockItems));
                    $this->info('Low stock detected! Emails sent to admins.');
                }
            } else {
                $this->comment('Inventory levels are healthy.');
            }

        } catch (\Exception $e) {
            $this->error('Error during inventory check: ' . $e->getMessage());
            Log::error("Console Command Inventory Check failed: " . $e->getMessage());
        }

        return 0;
    }
}