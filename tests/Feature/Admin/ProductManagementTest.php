<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\Unit;
use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\Product\PriceUpdated;
use App\Mail\Product\DeletionAttempt as ProductDeletionMail;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): Admin
    {
        return Admin::create([
            'name' => 'Product Director',
            'email' => 'product-director-' . uniqid() . '@example.test',
            'password' => bcrypt('secret123'),
        ]);
    }

    public function test_guest_cannot_access_product_endpoints()
    {
        $this->get('/admin/products')->assertRedirect('/admin');
        $this->post('/admin/newProduct', [])->assertRedirect('/admin');
        $this->post('/admin/updateProduct', [])->assertRedirect('/admin');
        $this->post('/admin/deleteProduct', [])->assertRedirect('/admin');
    }

    public function test_admin_can_view_redesigned_products_page_and_table()
    {
        $admin = $this->createAdmin();

        $product = Product::create([
            'name' => 'Artisan Baguette',
            'slug' => 'artisan-baguette',
            'sales_unit' => 'Pcs',
            'selling_price' => 1500.00,
            'stock_on_hand' => 45,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/products');

        $response->assertStatus(200);
        $response->assertSee('Product Management');
        $response->assertSee('Commercial Products Directory');
        $response->assertSee('Cataloged Products');
        $response->assertSee('Stock on Hand');
        $response->assertSee('Retail Valuation');
        $response->assertSee('Recipe Formulations');
        $response->assertSee('id="datatable-buttons"', false);
        $response->assertSee('Artisan Baguette');
        $response->assertSee('1,500.00');
        $response->assertSee('67,500.00'); // 45 * 1500
        $response->assertSee('No Recipe');
    }

    public function test_admin_can_create_new_commercial_product()
    {
        $admin = $this->createAdmin();

        $payload = [
            'name' => 'Butter Brioche Loaf',
            'sales_unit' => 'Loaf',
            'selling_price' => 2200.00,
            'is_active' => 'on',
        ];

        $response = $this->actingAs($admin, 'admin')->post('/admin/newProduct', $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', [
            'name' => 'Butter Brioche Loaf',
            'slug' => 'butter-brioche-loaf',
            'sales_unit' => 'Loaf',
            'selling_price' => 2200.00,
            'is_active' => 1,
            'stock_on_hand' => 0,
        ]);
    }

    public function test_admin_can_update_product_and_dispatches_price_notification()
    {
        Mail::fake();

        $admin = $this->createAdmin();

        $product = Product::create([
            'name' => 'Wheat Bread',
            'slug' => 'wheat-bread',
            'sales_unit' => 'Loaf',
            'selling_price' => 1000.00,
            'stock_on_hand' => 20,
            'is_active' => true,
        ]);

        $payload = [
            'product_id' => $product->id,
            'name' => 'Wheat Bread Deluxe',
            'sales_unit' => 'Loaf',
            'selling_price' => 1400.00, // Price changed from 1000 to 1400
            'is_active' => 'on',
        ];

        $response = $this->actingAs($admin, 'admin')->post('/admin/updateProduct', $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Wheat Bread Deluxe',
            'selling_price' => 1400.00,
        ]);

        Mail::assertSent(PriceUpdated::class);
    }

    public function test_product_with_recipe_cannot_be_deleted()
    {
        Mail::fake();

        $admin = $this->createAdmin();

        $product = Product::create([
            'name' => 'Croissant',
            'slug' => 'croissant',
            'sales_unit' => 'Pcs',
            'selling_price' => 800.00,
            'stock_on_hand' => 10,
            'is_active' => true,
        ]);

        Recipe::create([
            'name' => 'Croissant Formulation',
            'product_id' => $product->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin, 'admin')->post('/admin/deleteProduct', [
            'product_id' => $product->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', ['id' => $product->id]);

        Mail::assertSent(ProductDeletionMail::class);
    }

    public function test_admin_can_delete_product_without_recipe()
    {
        $admin = $this->createAdmin();

        $product = Product::create([
            'name' => 'Plain Donut',
            'slug' => 'plain-donut',
            'sales_unit' => 'Pcs',
            'selling_price' => 500.00,
            'stock_on_hand' => 0,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin, 'admin')->post('/admin/deleteProduct', [
            'product_id' => $product->id,
        ]);

        $response->assertRedirect();
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }
}
