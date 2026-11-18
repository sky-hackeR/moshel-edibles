<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Unit;
use App\Models\Ingredient;
use App\Models\Inventory;
use App\Models\Recipe;
use App\Models\RecipeItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\Inventory\IngredientCreated;
use App\Mail\Inventory\DeletionAttempt;

class IngredientManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): Admin
    {
        return Admin::create([
            'name' => 'Ingredient Admin',
            'email' => 'ingredient-admin-' . uniqid() . '@example.test',
            'password' => bcrypt('secret123'),
        ]);
    }

    private function seedBaseUnit(): Unit
    {
        return Unit::create([
            'name' => 'Gram',
            'symbol' => 'g',
            'unit_type' => 'mass',
            'base_multiplier' => 1.0,
            'base_unit' => 'g',
            'is_active' => true,
            'use_for_purchase' => true,
            'use_for_recipe' => true,
        ]);
    }

    public function test_guest_cannot_access_ingredients()
    {
        $response = $this->get('/admin/ingredients');
        $response->assertRedirect('/admin');
    }

    public function test_admin_can_view_redesigned_ingredients_page()
    {
        $unit = $this->seedBaseUnit();
        $admin = $this->createAdmin();

        $ingredient = Ingredient::create([
            'name' => 'Premium White Flour',
            'slug' => 'premium-white-flour',
            'base_unit_id' => $unit->id,
            'reorder_level' => 5000,
            'is_active' => true,
        ]);

        Inventory::create([
            'ingredient_id' => $ingredient->id,
            'quantity' => 12500,
            'average_cost' => 1.5,
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/ingredients');

        $response->assertStatus(200);
        $response->assertSee('Ingredient Management', false);
        $response->assertSee('Raw Materials Catalog');
        $response->assertSee('Premium White Flour');
        $response->assertSee('12,500.000 g');
        $response->assertSee('5,000.000 g');
        $response->assertSee('addIngredient');
        $response->assertSee('editIngredient' . $ingredient->id);
        $response->assertSee('deleteIngredient' . $ingredient->id);
        $response->assertViewHas('ingredients');
        $response->assertViewHas('units');
    }

    public function test_admin_can_create_new_ingredient()
    {
        Mail::fake();
        $unit = $this->seedBaseUnit();
        $admin = $this->createAdmin();

        $payload = [
            'name' => 'Refined Cane Sugar',
            'base_unit_id' => $unit->id,
            'reorder_level' => 2000,
            'is_active' => 'on',
        ];

        $response = $this->actingAs($admin, 'admin')->post('/admin/newIngredient', $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('ingredients', [
            'name' => 'Refined Cane Sugar',
            'slug' => 'refined-cane-sugar',
            'base_unit_id' => $unit->id,
            'reorder_level' => 2000,
            'is_active' => true,
        ]);

        // Asserts inventory row is initialized
        $ingredient = Ingredient::where('name', 'Refined Cane Sugar')->first();
        $this->assertNotNull($ingredient);
        $this->assertDatabaseHas('inventories', [
            'ingredient_id' => $ingredient->id,
            'quantity' => 0,
        ]);

        Mail::assertSent(IngredientCreated::class);
    }

    public function test_admin_can_update_existing_ingredient()
    {
        $unit = $this->seedBaseUnit();
        $admin = $this->createAdmin();

        $ingredient = Ingredient::create([
            'name' => 'Old Butter Name',
            'slug' => 'old-butter-name',
            'base_unit_id' => $unit->id,
            'reorder_level' => 1000,
            'is_active' => true,
        ]);

        $payload = [
            'ingredient_id' => $ingredient->id,
            'name' => 'Golden Cream Butter',
            'base_unit_id' => $unit->id,
            'reorder_level' => 3500,
            'is_active' => 'on',
        ];

        $response = $this->actingAs($admin, 'admin')->post('/admin/updateIngredient', $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('ingredients', [
            'id' => $ingredient->id,
            'name' => 'Golden Cream Butter',
            'reorder_level' => 3500,
        ]);
    }

    public function test_ingredient_with_inventory_records_cannot_be_deleted()
    {
        Mail::fake();
        $unit = $this->seedBaseUnit();
        $admin = $this->createAdmin();

        $ingredient = Ingredient::create([
            'name' => 'Active Stock Flour',
            'slug' => 'active-stock-flour',
            'base_unit_id' => $unit->id,
            'is_active' => true,
        ]);

        Inventory::create([
            'ingredient_id' => $ingredient->id,
            'quantity' => 500,
            'average_cost' => 2.0,
        ]);

        $response = $this->actingAs($admin, 'admin')->post('/admin/deleteIngredient', [
            'ingredient_id' => $ingredient->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ingredients', [
            'id' => $ingredient->id,
        ]);

        Mail::assertSent(DeletionAttempt::class);
    }

    public function test_admin_can_delete_unused_ingredient_without_inventory_or_recipes()
    {
        $unit = $this->seedBaseUnit();
        $admin = $this->createAdmin();

        $ingredient = Ingredient::create([
            'name' => 'Orphan Vanilla Extract',
            'slug' => 'orphan-vanilla-extract',
            'base_unit_id' => $unit->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin, 'admin')->post('/admin/deleteIngredient', [
            'ingredient_id' => $ingredient->id,
        ]);

        $response->assertRedirect();
        $this->assertSoftDeleted('ingredients', [
            'id' => $ingredient->id,
        ]);
    }
}
