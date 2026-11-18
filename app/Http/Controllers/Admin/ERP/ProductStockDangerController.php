<?php

namespace App\Http\Controllers\Admin\ERP;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Product;
use App\Mail\Product\StockCleared;
use App\Services\Audit\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Alert;

class ProductStockDangerController extends Controller
{
    /**
     * Display product inventory levels in the Danger Zone.
     */
    public function index(Request $request)
    {
        $products = Product::with(['recipe', 'storeProduct.primaryImage'])
            ->orderBy('name', 'asc')
            ->get();

        // Calculate warehouse stock metrics
        $stats = [
            'total_products'  => $products->count(),
            'in_stock_count'  => $products->where('stock_on_hand', '>', 0)->count(),
            'depleted_count'  => $products->where('stock_on_hand', '<=', 0)->count(),
            'low_stock_count' => $products->filter(function ($p) {
                return (float) $p->stock_on_hand > 0 && (float) $p->stock_on_hand <= 10;
            })->count(),
            'total_units'     => (float) $products->sum('stock_on_hand'),
            'total_valuation' => (float) $products->sum(function ($p) {
                return (float) $p->stock_on_hand * (float) $p->selling_price;
            }),
        ];

        return view('admin.erp.productStockDanger', compact('products', 'stats'));
    }

    /**
     * Zero out all stock for a single specified product.
     */
    public function clearProductStock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id'      => 'required|exists:products,id',
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
            $product = Product::lockForUpdate()->findOrFail($request->product_id);
            $previousStock = (float) $product->stock_on_hand;

            if ($previousStock <= 0) {
                DB::rollBack();
                alert()->info('Already Zero', "{$product->name} already has 0 stock on hand.")->persistent('Close');
                return redirect()->back();
            }

            // Zero out stock
            $product->update([
                'stock_on_hand' => 0,
            ]);

            $currentUser = Auth::user();
            $reason = $request->input('reason');
            $notes = $request->input('notes');
            $accountingType = $request->input('accounting_type', 'operational');

            $typeLabel = $accountingType === 'write_off' ? 'Loss & Spoilage Write-Off' : 'Operational Adjustment';

            // Record in Audit Trail
            AuditLogger::log(
                'Inventory',
                'Zeroed Out Product Stock',
                "Admin {$currentUser->name} cleared stock for '{$product->name}' from {$previousStock} {$product->sales_unit} to 0 ({$typeLabel}). Reason: {$reason}",
                [
                    'product_id'           => $product->id,
                    'product_name'         => $product->name,
                    'previous_stock'       => $previousStock,
                    'new_stock'            => 0,
                    'sales_unit'           => $product->sales_unit,
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
            $this->notifyAdmins(new StockCleared(
                $product,
                $previousStock,
                $currentUser,
                $reason,
                $notes,
                $accountingType,
                false
            ));

            alert()->warning(
                'Stock Zeroed Out',
                "Stock for {$product->name} has been cleared ({$previousStock} {$product->sales_unit} → 0). Depletion mode: {$typeLabel}."
            )->persistent('Close');

            return redirect()->back();

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Failed to clear product stock: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            alert()->error('Operation Failed', 'Could not clear stock: ' . $e->getMessage())->persistent('Close');
            return redirect()->back();
        }
    }

    /**
     * Emergency full catalog reset: Zero out stock across ALL products.
     */
    public function clearAllStock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'confirmation_text' => 'required|in:CONFIRM EMERGENCY RESET',
            'reason'            => 'required|string|max:255',
            'accounting_type'   => 'nullable|in:operational,write_off',
            'notes'             => 'nullable|string|max:1000',
        ], [
            'confirmation_text.in' => 'You must type "CONFIRM EMERGENCY RESET" exactly to authorize this catalog-wide wipeout.',
            'reason.required'      => 'A formal administrative justification is mandatory.',
        ]);

        if ($validator->fails()) {
            alert()->error('Security Check Failed', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $inStockProducts = Product::where('stock_on_hand', '>', 0)->lockForUpdate()->get();
            $affectedCount = $inStockProducts->count();
            $totalUnitsCleared = (float) $inStockProducts->sum('stock_on_hand');

            if ($affectedCount === 0) {
                DB::rollBack();
                alert()->info('Nothing to Clear', 'All products in the catalog are already at 0 stock.')->persistent('Close');
                return redirect()->back();
            }

            // Zero out stock for all products
            Product::where('stock_on_hand', '>', 0)->update([
                'stock_on_hand' => 0,
            ]);

            $currentUser = Auth::user();
            $reason = $request->input('reason');
            $notes = $request->input('notes');
            $accountingType = $request->input('accounting_type', 'operational');

            $typeLabel = $accountingType === 'write_off' ? 'Loss & Spoilage Write-Off' : 'Operational Adjustment';

            // Record in Audit Trail
            AuditLogger::log(
                'Inventory',
                'Full Catalog Stock Wipeout (Danger Zone)',
                "Admin {$currentUser->name} executed emergency stock wipeout across {$affectedCount} products ({$totalUnitsCleared} units cleared, {$typeLabel}). Reason: {$reason}",
                [
                    'affected_products_count' => $affectedCount,
                    'total_units_cleared'     => $totalUnitsCleared,
                    'accounting_type'         => $accountingType,
                    'affects_sales_income'    => ($accountingType === 'write_off'),
                    'reason'                  => $reason,
                    'notes'                   => $notes,
                    'cleared_by'              => $currentUser->email,
                ],
                $request
            );

            DB::commit();

            // Dispatch emergency security notification after DB commit
            $this->notifyAdmins(new StockCleared(
                null,
                $totalUnitsCleared,
                $currentUser,
                $reason,
                $notes,
                $accountingType,
                true,
                $affectedCount,
                $totalUnitsCleared
            ));

            alert()->warning(
                'Full Catalog Reset Complete',
                "Emergency reset executed: {$totalUnitsCleared} total units across {$affectedCount} products have been wiped to 0 ({$typeLabel})."
            )->persistent('Close');

            return redirect()->back();

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Emergency catalog stock clear failed: " . $e->getMessage());

            alert()->error('Emergency Action Failed', 'Could not execute catalog stock wipeout: ' . $e->getMessage())->persistent('Close');
            return redirect()->back();
        }
    }

    /**
     * Dispatch email notification to all registered administrators in a single SMTP envelope.
     */
    protected function notifyAdmins(StockCleared $mailable): void
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
            Log::error("Failed resolving admins for StockCleared notification: " . $e->getMessage());
        }
    }
}
