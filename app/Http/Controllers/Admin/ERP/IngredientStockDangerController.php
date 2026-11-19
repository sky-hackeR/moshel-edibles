<?php

namespace App\Http\Controllers\Admin\ERP;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Ingredient;
use App\Models\Inventory;
use App\Mail\Inventory\IngredientStockCleared;
use App\Services\Audit\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Alert;

class IngredientStockDangerController extends Controller
{
    /**
     * Display ingredient inventory levels in the Danger Zone with standard DataTable.
     */
    public function index(Request $request)
    {
        $ingredients = Ingredient::with(['baseUnit', 'inventory', 'recipeItems'])
            ->orderBy('name', 'asc')
            ->get();

        // Calculate warehouse stock metrics
        $totalIngredients = $ingredients->count();
        $inStockCount = $ingredients->filter(function ($ing) {
            return $ing->inventory && (float) $ing->inventory->quantity > 0;
        })->count();

        $depletedCount = $totalIngredients - $inStockCount;

        $lowStockCount = $ingredients->filter(function ($ing) {
            $qty = $ing->inventory ? (float) $ing->inventory->quantity : 0;
            return $qty > 0 && $qty <= $ing->effective_reorder_level;
        })->count();

        $totalUnits = (float) $ingredients->sum(function ($ing) {
            return $ing->inventory ? (float) $ing->inventory->quantity : 0;
        });

        $totalValuation = (float) $ingredients->sum(function ($ing) {
            return $ing->inventory ? ((float) $ing->inventory->quantity * (float) $ing->inventory->average_cost) : 0;
        });

        $stats = [
            'total_ingredients' => $totalIngredients,
            'in_stock_count'    => $inStockCount,
            'depleted_count'    => $depletedCount,
            'low_stock_count'   => $lowStockCount,
            'total_units'       => $totalUnits,
            'total_valuation'   => $totalValuation,
        ];

        return view('admin.erp.ingredients.danger', compact('ingredients', 'stats'));
    }

    /**
     * Zero out all stock for a single specified ingredient.
     */
    public function clearIngredientStock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ingredient_id'   => 'required|exists:ingredients,id',
            'reason'          => 'required|string|max:255',
            'accounting_type' => 'nullable|in:operational,write_off',
            'notes'           => 'nullable|string|max:1000',
            'confirm_deplete' => 'required|accepted',
        ], [
            'confirm_deplete.accepted' => 'You must check the confirmation box acknowledging that stock will immediately become 0.',
            'reason.required'          => 'An operational reason is required for security and auditing purposes.',
        ]);

        if ($validator->fails()) {
            alert()->error('Action Blocked', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $ingredient = Ingredient::with(['baseUnit'])->lockForUpdate()->findOrFail($request->ingredient_id);
            $inventory = Inventory::lockForUpdate()->firstOrCreate(
                ['ingredient_id' => $ingredient->id],
                ['quantity' => 0, 'average_cost' => 0]
            );

            $previousStock = (float) $inventory->quantity;
            $unitSymbol = $ingredient->baseUnit ? ($ingredient->baseUnit->symbol ?: $ingredient->baseUnit->name) : 'Units';

            if ($previousStock <= 0) {
                DB::rollBack();
                alert()->info('Already Zero', "{$ingredient->name} already has 0 stock on hand.")->persistent('Close');
                return redirect()->back();
            }

            $averageCost = (float) $inventory->average_cost;
            $totalLostValue = $previousStock * $averageCost;

            // Zero out stock
            $inventory->update([
                'quantity' => 0,
            ]);

            $currentUser = Auth::user();
            $reason = $request->input('reason');
            $notes = $request->input('notes');
            $accountingType = $request->input('accounting_type', 'operational');

            $typeLabel = $accountingType === 'write_off' ? 'Loss & Spoilage Write-Off' : 'Operational Adjustment';

            // Record in Audit Trail
            AuditLogger::log(
                'Inventory',
                'Zeroed Out Ingredient Stock',
                "Admin {$currentUser->name} cleared warehouse stock for raw material '{$ingredient->name}' from {$previousStock} {$unitSymbol} to 0 ({$typeLabel}). Reason: {$reason}",
                [
                    'ingredient_id'        => $ingredient->id,
                    'ingredient_name'      => $ingredient->name,
                    'previous_stock'       => $previousStock,
                    'new_stock'            => 0,
                    'base_unit'            => $unitSymbol,
                    'average_cost'         => $averageCost,
                    'total_lost_value'     => $totalLostValue,
                    'accounting_type'      => $accountingType,
                    'affects_sales_income' => ($accountingType === 'write_off'),
                    'reason'               => $reason,
                    'notes'                => $notes,
                    'cleared_by'           => $currentUser->email,
                ],
                $request
            );

            DB::commit();

            // Dispatch notification after database transaction is fully committed
            $this->notifyAdmins(new IngredientStockCleared(
                $ingredient,
                $previousStock,
                $currentUser,
                $reason,
                $notes,
                $accountingType,
                false,
                1,
                $previousStock,
                $averageCost,
                $totalLostValue
            ));

            alert()->warning(
                'Stock Zeroed Out',
                "Stock for raw material {$ingredient->name} has been cleared ({$previousStock} {$unitSymbol} → 0). Depletion mode: {$typeLabel}."
            )->persistent('Close');

            return redirect()->back();

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Failed to clear ingredient stock: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            alert()->error('Operation Failed', 'Could not clear stock: ' . $e->getMessage())->persistent('Close');
            return redirect()->back();
        }
    }

    /**
     * Emergency full warehouse raw materials reset: Zero out stock across ALL ingredients.
     */
    public function clearAllStock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'confirmation_text' => 'required|in:CONFIRM EMERGENCY RESET',
            'reason'            => 'required|string|max:255',
            'accounting_type'   => 'nullable|in:operational,write_off',
            'notes'             => 'nullable|string|max:1000',
        ], [
            'confirmation_text.in' => 'You must type "CONFIRM EMERGENCY RESET" exactly to authorize this warehouse raw materials wipeout.',
            'reason.required'      => 'A formal administrative justification is mandatory.',
        ]);

        if ($validator->fails()) {
            alert()->error('Security Check Failed', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $inStockInventories = Inventory::where('quantity', '>', 0)->lockForUpdate()->get();
            $affectedCount = $inStockInventories->count();
            $totalUnitsCleared = (float) $inStockInventories->sum('quantity');
            $totalLostValue = (float) $inStockInventories->sum(function ($inv) {
                return (float) $inv->quantity * (float) $inv->average_cost;
            });

            if ($affectedCount === 0) {
                DB::rollBack();
                alert()->info('Nothing to Clear', 'All raw materials in the warehouse are already at 0 stock.')->persistent('Close');
                return redirect()->back();
            }

            // Zero out stock for all inventories
            Inventory::where('quantity', '>', 0)->update([
                'quantity' => 0,
            ]);

            $currentUser = Auth::user();
            $reason = $request->input('reason');
            $notes = $request->input('notes');
            $accountingType = $request->input('accounting_type', 'operational');

            $typeLabel = $accountingType === 'write_off' ? 'Loss & Spoilage Write-Off' : 'Operational Adjustment';

            // Record in Audit Trail
            AuditLogger::log(
                'Inventory',
                'Full Raw Materials Wipeout (Danger Zone)',
                "Admin {$currentUser->name} executed emergency stock wipeout across {$affectedCount} raw materials ({$totalUnitsCleared} units cleared, {$typeLabel}). Reason: {$reason}",
                [
                    'affected_ingredients_count' => $affectedCount,
                    'total_units_cleared'        => $totalUnitsCleared,
                    'total_lost_value'           => $totalLostValue,
                    'accounting_type'            => $accountingType,
                    'affects_sales_income'       => ($accountingType === 'write_off'),
                    'reason'                     => $reason,
                    'notes'                      => $notes,
                    'cleared_by'                 => $currentUser->email,
                ],
                $request
            );

            DB::commit();

            // Dispatch emergency security notification after DB commit
            $this->notifyAdmins(new IngredientStockCleared(
                null,
                $totalUnitsCleared,
                $currentUser,
                $reason,
                $notes,
                $accountingType,
                true,
                $affectedCount,
                $totalUnitsCleared,
                0,
                $totalLostValue
            ));

            alert()->warning(
                'Full Warehouse Reset Complete',
                "Emergency reset executed: {$totalUnitsCleared} total units across {$affectedCount} raw materials have been wiped to 0 ({$typeLabel})."
            )->persistent('Close');

            return redirect()->back();

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Emergency ingredient stock clear failed: " . $e->getMessage());

            alert()->error('Emergency Action Failed', 'Could not execute raw materials wipeout: ' . $e->getMessage())->persistent('Close');
            return redirect()->back();
        }
    }

    /**
     * Dispatch email notification to all registered administrators in a single SMTP envelope.
     */
    protected function notifyAdmins(IngredientStockCleared $mailable): void
    {
        try {
            $adminEmails = Admin::pluck('email')->filter()->unique()->values()->all();

            // Ensure the master system admin mailbox is included
            $masterAdminMail = config('mail.mailers.admin.from.address', 'moshedib@moshedibles.com');
            if ($masterAdminMail && !in_array($masterAdminMail, $adminEmails, true)) {
                $adminEmails[] = $masterAdminMail;
            }

            if (empty($adminEmails)) return;

            $primary = array_shift($adminEmails);
            $mail = Mail::mailer('admin')->to($primary);
            if (!empty($adminEmails)) {
                $mail->bcc($adminEmails);
            }
            $mail->send($mailable);
        } catch (\Throwable $e) {
            Log::error("Failed resolving admins for IngredientStockCleared notification: " . $e->getMessage());
        }
    }
}
