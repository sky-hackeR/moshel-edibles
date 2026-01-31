<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sale;
use App\Models\Production;
use App\Models\Product;
use App\Models\Admin;
use Illuminate\Support\Facades\Mail;
use App\Mail\Finance\DailyPerformance;
use App\Mail\Stock\LowStockAlert;
use Carbon\Carbon;

class SendDailyBusinessReport extends Command
{
    // The name of the command you'd run in terminal
    protected $signature = 'report:daily';

    protected $description = 'Send daily financial reports and low stock alerts to all admins';

    public function handle(){
        $today = Carbon::today();
        
        // 1. Calculate Stats
        $revenue = Sale::whereDate('created_at', $today)->sum('payable_amount');
        $cost    = Production::whereDate('produced_at', $today)->sum('total_cost');
        
        $stats = [
            'revenue' => $revenue,
            'cost'    => $cost,
            'profit'  => $revenue - $cost
        ];

        // 2. Get Low Stock INGREDIENTS (Matching your template's expectation)
        $thresholds = ['gram' => 1000, 'ml' => 1000, 'pcs' => 10];
        
        $lowStock = \App\Models\Inventory::with(['ingredient.baseUnit'])
            ->get()
            ->filter(function($inventory) use ($thresholds) {
                $ingredient = $inventory->ingredient;
                if (!$ingredient) return false;
                
                if ($ingredient->reorder_level > 0) {
                    return $inventory->quantity <= $ingredient->reorder_level;
                }

                $unitName = strtolower($ingredient->baseUnit->name ?? '');
                if (array_key_exists($unitName, $thresholds)) {
                    return $inventory->quantity <= $thresholds[$unitName];
                }

                return $inventory->quantity <= 0;
            });

        // 3. Get all Admin emails
        $adminEmails = Admin::pluck('email')->toArray();

        if (!empty($adminEmails)) {
            foreach ($adminEmails as $email) {
                // Send Financial Report
                Mail::to($email)->send(new DailyPerformance($stats));

                // Only send stock alert if there are actually low items
                if ($lowStock->count() > 0) {
                    Mail::to($email)->send(new LowStockAlert($lowStock));
                }
            }
        }

        $this->info('Daily reports and ingredient alerts sent successfully.');
    }
}