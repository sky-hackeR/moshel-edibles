<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Staff;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UnitManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): Admin
    {
        return Admin::create([
            'name' => 'Unit Admin',
            'email' => 'unit-admin-' . uniqid() . '@example.test',
            'password' => bcrypt('secret123'),
        ]);
    }

    private function createStaff(): Staff
    {
        return Staff::create([
            'name' => 'Staff Member',
            'email' => 'staff-' . uniqid() . '@example.test',
            'password' => bcrypt('secret123'),
        ]);
    }

    private function seedBaseUnits()
    {
        Unit::create([
            'name' => 'Gram',
            'symbol' => 'g',
            'unit_type' => 'mass',
            'base_multiplier' => 1.0,
            'base_unit' => 'g',
            'is_active' => true,
            'use_for_purchase' => true,
            'use_for_recipe' => true,
        ]);

        Unit::create([
            'name' => 'Millilitre',
            'symbol' => 'ml',
            'unit_type' => 'volume',
            'base_multiplier' => 1.0,
            'base_unit' => 'ml',
            'is_active' => true,
            'use_for_purchase' => true,
            'use_for_recipe' => true,
        ]);

        Unit::create([
            'name' => 'Piece',
            'symbol' => 'pcs',
            'unit_type' => 'count',
            'base_multiplier' => 1.0,
            'base_unit' => 'pcs',
            'is_active' => true,
            'use_for_purchase' => true,
            'use_for_recipe' => true,
        ]);
    }

    public function test_guest_cannot_access_unit_management()
    {
        $response = $this->get('/admin/unitManagement');
        $response->assertRedirect('/admin');
    }

    public function test_admin_can_view_redesigned_unit_management_page()
    {
        $this->seedBaseUnits();
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin, 'admin')->get('/admin/unitManagement');

        $response->assertStatus(200);
        $response->assertSee('Unit Management & Conversion Engine', false);
        $response->assertSee('Unit Conversion Calculator');
        $response->assertSee('floatingCalculatorWidget');
        $response->assertSee('addUnit');
        $response->assertSee('Core Base');
        $response->assertViewHas('units');
    }

    public function test_admin_can_create_new_custom_unit()
    {
        $this->seedBaseUnits();
        $admin = $this->createAdmin();

        $payload = [
            'name' => '50kg Flour Bag',
            'symbol' => 'bag_50kg',
            'unit_type' => 'mass',
            'base_multiplier' => 50000,
            'use_for_purchase' => 'on',
            'use_for_recipe' => 'on',
        ];

        $response = $this->actingAs($admin, 'admin')->post('/admin/newUnit', $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('units', [
            'name' => '50kg Flour Bag',
            'symbol' => 'bag_50kg',
            'unit_type' => 'mass',
            'base_multiplier' => 50000,
            'base_unit' => 'g',
            'use_for_purchase' => true,
            'use_for_recipe' => true,
        ]);
    }

    public function test_admin_can_update_existing_custom_unit()
    {
        $this->seedBaseUnits();
        $admin = $this->createAdmin();

        $unit = Unit::create([
            'name' => 'Old Bag',
            'symbol' => 'bag',
            'unit_type' => 'mass',
            'base_multiplier' => 25000,
            'base_unit' => 'g',
            'is_active' => true,
            'use_for_purchase' => true,
            'use_for_recipe' => true,
        ]);

        $payload = [
            'unit_id' => $unit->id,
            'name' => 'Updated 25kg Bag',
            'symbol' => 'bag_25',
            'unit_type' => 'mass',
            'base_multiplier' => 25000,
            'use_for_purchase' => 'on',
            'is_active' => 'on',
        ];

        $response = $this->actingAs($admin, 'admin')->post('/admin/updateUnit', $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('units', [
            'id' => $unit->id,
            'name' => 'Updated 25kg Bag',
            'symbol' => 'bag_25',
        ]);
    }

    public function test_base_units_cannot_be_deleted()
    {
        $this->seedBaseUnits();
        $admin = $this->createAdmin();

        $baseUnit = Unit::where('symbol', 'g')->first();

        $response = $this->actingAs($admin, 'admin')->post('/admin/deleteUnit', [
            'unit_id' => $baseUnit->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('units', [
            'id' => $baseUnit->id,
            'symbol' => 'g',
            'deleted_at' => null,
        ]);
    }

    public function test_custom_unit_can_be_deleted()
    {
        $this->seedBaseUnits();
        $admin = $this->createAdmin();

        $customUnit = Unit::create([
            'name' => 'Temporary Pack',
            'symbol' => 'temp_pk',
            'unit_type' => 'count',
            'base_multiplier' => 6,
            'base_unit' => 'pcs',
            'is_active' => true,
            'use_for_purchase' => true,
            'use_for_recipe' => true,
        ]);

        $response = $this->actingAs($admin, 'admin')->post('/admin/deleteUnit', [
            'unit_id' => $customUnit->id,
        ]);

        $response->assertRedirect();
        $this->assertSoftDeleted('units', [
            'id' => $customUnit->id,
        ]);
    }
}
