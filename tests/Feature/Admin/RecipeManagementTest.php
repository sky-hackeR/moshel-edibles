<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\RecipeItem;
use App\Models\Unit;
use App\Models\Ingredient;
use App\Models\Inventory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\Recipe\RecipeUpdated;
use App\Mail\Recipe\RecipeDeleted;

class RecipeManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): Admin
    {
        return Admin::create([
            'name' => 'Executive Chef',
            'email' => 'chef-' . uniqid() . '@example.test',
            'password' => bcrypt('secret123'),
        ]);
    }

    private function seedUnits(): array
    {
        $kg = Unit::create([
            'name' => 'Kilogram',
            'symbol' => 'kg',
            'unit_type' => 'mass',
            'base_multiplier' => 1000.0,
            'base_unit' => 'g',
            'is_active' => true,
            'use_for_purchase' => true,
            'use_for_recipe' => true,
        ]);

        $gram = Unit::create([
            'name' => 'Gram',
            'symbol' => 'g',
            'unit_type' => 'mass',
            'base_multiplier' => 1.0,
            'base_unit' => 'g',
            'is_active' => true,
            'use_for_purchase' => true,
            'use_for_recipe' => true,
        ]);

        return [$kg, $gram];
    }

    public function test_guest_cannot_access_recipe_endpoints()
    {
        $this->get('/admin/recipes')->assertRedirect('/admin');
        $this->post('/admin/newRecipe', [])->assertRedirect('/admin');
        $this->post('/admin/updateRecipe', [])->assertRedirect('/admin');
        $this->post('/admin/deleteRecipe', [])->assertRedirect('/admin');
    }

    public function test_admin_can_view_redesigned_recipes_page_and_table()
    {
        $admin = $this->createAdmin();
        [$kg, $gram] = $this->seedUnits();

        $product = Product::create([
            'name' => 'Country Sourdough',
            'slug' => 'country-sourdough',
            'sales_unit' => 'Loaf',
            'selling_price' => 2500.00,
            'stock_on_hand' => 12,
            'is_active' => true,
        ]);

        $flour = Ingredient::create([
            'name' => 'Rye Flour',
            'slug' => 'rye-flour',
            'base_unit_id' => $gram->id,
            'reorder_level' => 3000,
            'is_active' => true,
        ]);

        Inventory::create([
            'ingredient_id' => $flour->id,
            'quantity' => 15000,
            'average_cost' => 1.20,
        ]);

        $recipe = Recipe::create([
            'product_id' => $product->id,
            'name' => 'Artisan Fermentation Formula',
            'note' => '24 hour slow cold retard',
            'is_active' => true,
        ]);

        RecipeItem::create([
            'recipe_id' => $recipe->id,
            'ingredient_id' => $flour->id,
            'unit_id' => $gram->id,
            'quantity' => 600,
            'base_quantity' => 600,
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/recipes');

        $response->assertStatus(200);
        $response->assertSee('Recipe Management');
        $response->assertSee('Master Recipe Formulations');
        $response->assertSee('Recipe Formulations');
        $response->assertSee('Unformulated Products');
        $response->assertSee('Raw Materials Linked');
        $response->assertSee('id="datatable-buttons"', false);
        $response->assertSee('Country Sourdough');
        $response->assertSee('Artisan Fermentation Formula');
        $response->assertSee('₦720.00'); // 600 * 1.20 est cost
        $response->assertSee('Active');
    }

    public function test_admin_can_create_new_recipe_with_items()
    {
        $admin = $this->createAdmin();
        [$kg, $gram] = $this->seedUnits();

        $product = Product::create([
            'name' => 'Brioche Bun',
            'slug' => 'brioche-bun',
            'sales_unit' => 'Pcs',
            'selling_price' => 800.00,
            'is_active' => true,
        ]);

        $flour = Ingredient::create([
            'name' => 'White Flour',
            'slug' => 'white-flour',
            'base_unit_id' => $gram->id,
            'reorder_level' => 2000,
            'is_active' => true,
        ]);

        $payload = [
            'product_id' => $product->id,
            'name' => 'Golden Brioche Formula',
            'note' => 'Use high fat butter',
            'items' => [
                [
                    'ingredient_id' => $flour->id,
                    'quantity' => 0.5,
                    'unit_id' => $kg->id, // 0.5 kg = 500 g base
                ],
            ],
        ];

        $response = $this->actingAs($admin, 'admin')->post('/admin/newRecipe', $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('recipes', [
            'product_id' => $product->id,
            'name' => 'Golden Brioche Formula',
        ]);

        $recipe = Recipe::where('product_id', $product->id)->first();
        $this->assertDatabaseHas('recipe_items', [
            'recipe_id' => $recipe->id,
            'ingredient_id' => $flour->id,
            'quantity' => 0.5,
            'base_quantity' => 500,
        ]);
    }

    public function test_admin_can_update_recipe_and_dispatches_notification()
    {
        Mail::fake();

        $admin = $this->createAdmin();
        [$kg, $gram] = $this->seedUnits();

        $product = Product::create([
            'name' => 'Chocolate Muffin',
            'slug' => 'chocolate-muffin',
            'sales_unit' => 'Pcs',
            'selling_price' => 1200.00,
            'is_active' => true,
        ]);

        $cocoa = Ingredient::create([
            'name' => 'Cocoa Powder',
            'slug' => 'cocoa-powder',
            'base_unit_id' => $gram->id,
            'reorder_level' => 1000,
            'is_active' => true,
        ]);

        $recipe = Recipe::create([
            'product_id' => $product->id,
            'name' => 'Standard Muffin Recipe',
            'is_active' => true,
        ]);

        RecipeItem::create([
            'recipe_id' => $recipe->id,
            'ingredient_id' => $cocoa->id,
            'unit_id' => $gram->id,
            'quantity' => 100,
            'base_quantity' => 100,
        ]);

        $payload = [
            'recipe_id' => $recipe->id,
            'name' => 'Double Chocolate Muffin Recipe',
            'note' => 'Added extra cocoa',
            'is_active' => 'on',
            'items' => [
                [
                    'ingredient_id' => $cocoa->id,
                    'quantity' => 200,
                    'unit_id' => $gram->id,
                ],
            ],
        ];

        $response = $this->actingAs($admin, 'admin')->post('/admin/updateRecipe', $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('recipes', [
            'id' => $recipe->id,
            'name' => 'Double Chocolate Muffin Recipe',
        ]);

        $this->assertDatabaseHas('recipe_items', [
            'recipe_id' => $recipe->id,
            'quantity' => 200,
            'base_quantity' => 200,
        ]);

        Mail::assertSent(RecipeUpdated::class);
    }

    public function test_admin_can_delete_recipe_and_dispatches_notification()
    {
        Mail::fake();

        $admin = $this->createAdmin();
        [$kg, $gram] = $this->seedUnits();

        $product = Product::create([
            'name' => 'Seasonal Tart',
            'slug' => 'seasonal-tart',
            'sales_unit' => 'Pcs',
            'selling_price' => 1800.00,
            'is_active' => true,
        ]);

        $recipe = Recipe::create([
            'product_id' => $product->id,
            'name' => 'Autumn Apple Formula',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin, 'admin')->post('/admin/deleteRecipe', [
            'recipe_id' => $recipe->id,
        ]);

        $response->assertRedirect();
        $this->assertSoftDeleted('recipes', ['id' => $recipe->id]);

        Mail::assertSent(RecipeDeleted::class);
    }
}
