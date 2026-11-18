<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\AuditLog;
use App\Models\Ingredient;
use App\Models\Inventory;
use App\Models\Staff;
use App\Models\Unit;
use App\Mail\Inventory\IngredientStockCleared;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class IngredientStockDangerTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(string $email = 'admin@example.test', string $name = 'Super Admin'): Admin
    {
        return Admin::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make('secret-password'),
        ]);
    }

    private function createStaff(string $email = 'staff@example.test', string $name = 'Production Staff'): Staff
    {
        return Staff::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make('secret-password'),
        ]);
    }

    private function createUnit(string $name = 'Kilogram', string $symbol = 'kg', string $type = 'mass'): Unit
    {
        return Unit::firstOrCreate([
            'symbol' => $symbol,
            'unit_type' => $type,
        ], [
            'name' => $name,
            'base_unit' => $symbol,
            'operator' => '*',
            'operation_value' => 1,
            'is_active' => true,
        ]);
    }

    private function createIngredient(string $name = 'Baking Flour', float $quantity = 50.0, float $averageCost = 1200.0, ?Unit $unit = null): Ingredient
    {
        $unit = $unit ?: $this->createUnit();

        $ingredient = Ingredient::create([
            'name' => $name,
            'slug' => Str::slug($name),
            'base_unit_id' => $unit->id,
            'reorder_level' => 10.0,
            'is_active' => true,
        ]);

        Inventory::create([
            'ingredient_id' => $ingredient->id,
            'quantity' => $quantity,
            'average_cost' => $averageCost,
        ]);

        return $ingredient;
    }

    public function test_guests_cannot_access_ingredient_stock_danger_zone()
    {
        $response = $this->get(route('admin.ingredients.stock'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_staff_cannot_access_ingredient_stock_danger_zone()
    {
        $staff = $this->createStaff();

        $response = $this->actingAs($staff, 'staff')->get(route('admin.ingredients.stock'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_ingredient_stock_danger_zone()
    {
        $admin = $this->createAdmin();
        $unit = $this->createUnit('Gram', 'g', 'mass');
        $ingredient = $this->createIngredient('Vanilla Extract', 250.0, 45.0, $unit);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.ingredients.stock'));

        $response->assertStatus(200);
        $response->assertSee('Raw Materials Stock Control — Danger Zone');
        $response->assertSee('datatable-buttons');
        $response->assertSee('Vanilla Extract');
        $response->assertSee('250 g');
        $response->assertSee('₦45.00');
    }

    public function test_admin_can_clear_single_ingredient_stock_with_email_and_audit_log()
    {
        Mail::fake();

        $admin = $this->createAdmin('executive@moshedibles.com', 'Warehouse Executive');
        $unit = $this->createUnit('Kilogram', 'kg', 'mass');
        $ingredient = $this->createIngredient('Granulated Sugar', 80.0, 1500.0, $unit);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.ingredients.clearStock'), [
            'ingredient_id' => $ingredient->id,
            'reason' => 'Batch Spoilage / Contamination / Expiration / Quality Issue',
            'notes' => 'Sugar bag torn and contaminated by moisture.',
            'confirm_deplete' => '1',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        // 1. Verify inventory is zeroed in database
        $this->assertEquals(0, $ingredient->inventory->fresh()->quantity);

        // 2. Verify admin notification was sent
        Mail::assertSent(IngredientStockCleared::class, function ($mail) use ($ingredient, $admin) {
            return $mail->ingredientName === $ingredient->name
                && $mail->previousStock == 80.0
                && $mail->newStock === 0
                && $mail->user->id === $admin->id
                && $mail->totalLostValue == (80.0 * 1500.0);
        });

        // 3. Verify audit log entry
        $log = AuditLog::where('category', 'Inventory')
            ->where('action', 'Zeroed Out Ingredient Stock')
            ->first();

        $this->assertNotNull($log);
        $this->assertEquals('Granulated Sugar', $log->details['ingredient_name']);
        $this->assertEquals(80.0, $log->details['previous_stock']);
        $this->assertEquals(0, $log->details['new_stock']);
        $this->assertEquals('operational', $log->details['accounting_type']);
        $this->assertFalse($log->details['affects_sales_income']);
    }

    public function test_admin_can_choose_write_off_accounting_mode()
    {
        Mail::fake();

        $admin = $this->createAdmin();
        $ingredient = $this->createIngredient('Cocoa Powder', 20.0, 3000.0);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.ingredients.clearStock'), [
            'ingredient_id' => $ingredient->id,
            'reason' => 'Stock Write-off / Damaged Packaging / Spillage',
            'accounting_type' => 'write_off',
            'notes' => 'Damaged in transit from supplier.',
            'confirm_deplete' => '1',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertEquals(0, $ingredient->inventory->fresh()->quantity);

        $log = AuditLog::where('category', 'Inventory')
            ->where('action', 'Zeroed Out Ingredient Stock')
            ->latest('id')
            ->first();

        $this->assertEquals('write_off', $log->details['accounting_type']);
        $this->assertTrue($log->details['affects_sales_income']);
    }

    public function test_all_admins_receive_ingredient_stock_cleared_notifications()
    {
        Mail::fake();

        $admin1 = $this->createAdmin('admin1@moshedibles.com', 'Admin 1');
        $admin2 = $this->createAdmin('admin2@moshedibles.com', 'Admin 2');
        $ingredient = $this->createIngredient('Unsalted Butter', 30.0, 4500.0);

        $this->actingAs($admin1, 'admin')->post(route('admin.ingredients.clearStock'), [
            'ingredient_id' => $ingredient->id,
            'reason' => 'Physical Stock Count Discrepancy / Audit Correction',
            'confirm_deplete' => '1',
        ]);

        Mail::assertSent(IngredientStockCleared::class, function ($mail) {
            return $mail->hasTo('admin1@moshedibles.com')
                && $mail->hasBcc('admin2@moshedibles.com');
        });
    }

    public function test_admin_cannot_clear_already_zero_stock()
    {
        Mail::fake();

        $admin = $this->createAdmin();
        $ingredient = $this->createIngredient('Active Dry Yeast', 0.0, 800.0);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.ingredients.clearStock'), [
            'ingredient_id' => $ingredient->id,
            'reason' => 'Administrative Inventory Reset',
            'confirm_deplete' => '1',
        ]);

        $response->assertRedirect();
        Mail::assertNothingSent();
        $this->assertEquals(0, AuditLog::where('action', 'Zeroed Out Ingredient Stock')->count());
    }

    public function test_admin_can_execute_emergency_full_raw_material_reset()
    {
        Mail::fake();

        $admin = $this->createAdmin('super@moshedibles.com', 'Chief Executive');
        $ing1 = $this->createIngredient('Ingredient Alpha', 10.0, 1000.0);
        $ing2 = $this->createIngredient('Ingredient Beta', 20.0, 2000.0);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.ingredients.clearAllStock'), [
            'confirmation_text' => 'CONFIRM EMERGENCY RESET',
            'reason' => 'Annual physical inventory reset authorization.',
            'notes' => 'Audited by external accounting team.',
            'accounting_type' => 'write_off',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertEquals(0, $ing1->inventory->fresh()->quantity);
        $this->assertEquals(0, $ing2->inventory->fresh()->quantity);

        Mail::assertSent(IngredientStockCleared::class, function ($mail) {
            return $mail->isBulk === true
                && $mail->affectedIngredientsCount === 2
                && $mail->totalUnitsCleared == 30.0
                && $mail->totalLostValue == (10.0 * 1000.0 + 20.0 * 2000.0);
        });

        $log = AuditLog::where('category', 'Inventory')
            ->where('action', 'Full Raw Materials Wipeout (Danger Zone)')->first();
        $this->assertNotNull($log);
        $this->assertEquals(2, $log->details['affected_ingredients_count']);
        $this->assertEquals(30.0, $log->details['total_units_cleared']);
        $this->assertEquals(50000.0, $log->details['total_lost_value']);
    }

    public function test_emergency_reset_fails_without_exact_confirmation_phrase()
    {
        Mail::fake();

        $admin = $this->createAdmin();
        $this->createIngredient('Flour Special', 100.0, 500.0);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.ingredients.clearAllStock'), [
            'confirmation_text' => 'RESET', // WRONG PHRASE
            'reason' => 'Testing validation failure.',
        ]);

        $response->assertSessionHasErrors(['confirmation_text']);
        $this->assertEquals(100.0, Inventory::first()->quantity);
        Mail::assertNothingSent();
    }

    public function test_ingredients_page_contains_danger_zone_link()
    {
        $admin = $this->createAdmin();
        $this->createIngredient('Cocoa', 50.0);

        $response = $this->actingAs($admin, 'admin')->get(route('ingredients'));

        $response->assertStatus(200);
        $response->assertSee(route('admin.ingredients.stock'));
        $response->assertSee('Stock Danger Zone');
    }
}
