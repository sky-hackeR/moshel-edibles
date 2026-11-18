<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Product;
use App\Models\StoreProduct;
use App\Models\StoreProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class StoreManagementTest extends TestCase
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

    private function createProduct(string $name = 'Artisan Sourdough', float $price = 2500.0): Product
    {
        return Product::create([
            'name' => $name,
            'slug' => Str::slug($name . '-' . Str::random(5)),
            'selling_price' => $price,
            'sales_unit' => 'Loaf',
            'stock_on_hand' => 45,
            'is_active' => true,
        ]);
    }

    public function test_guest_cannot_access_store_management()
    {
        $response = $this->get('/admin/store');
        $response->assertRedirect('/admin');
    }

    public function test_admin_can_view_redesigned_store_management_page_and_kpis()
    {
        $admin = $this->createAdmin();

        $product1 = $this->createProduct('Croissant Supreme', 1800.0);
        $product2 = $this->createProduct('Brioche Bun', 1200.0);

        // List product 1 on store
        $storeProduct1 = StoreProduct::create([
            'product_id' => $product1->id,
            'store_title' => 'Golden Butter Croissant',
            'is_published' => true,
            'is_featured' => true,
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/store');

        $response->assertOk();
        $response->assertSee('Storefront Merchandising');
        $response->assertSee('Master ERP Catalog');
        $response->assertSee('Active Online');
        $response->assertSee('Featured Showcase');
        $response->assertSee('Golden Butter Croissant');
        $response->assertSee('Brioche Bun');
        $response->assertSee('₦1,800.00');
        $response->assertSee('₦1,200.00');
        $response->assertSee('Manage');
        $response->assertSee('Add to Store');
    }

    public function test_admin_can_add_unlisted_product_to_store()
    {
        $admin = $this->createAdmin();
        $product = $this->createProduct('Baguette Traditionnelle', 1500.0);

        $this->assertDatabaseMissing('store_products', [
            'product_id' => $product->id,
        ]);

        $response = $this->actingAs($admin, 'admin')->post("/admin/store/products/{$product->slug}/add");

        $response->assertRedirect();
        $this->assertDatabaseHas('store_products', [
            'product_id' => $product->id,
            'is_published' => false,
            'is_featured' => false,
        ]);
    }

    public function test_admin_can_view_redesigned_product_edit_page()
    {
        $admin = $this->createAdmin();
        $product = $this->createProduct('Pain au Chocolat', 2200.0);

        $storeProduct = StoreProduct::create([
            'product_id' => $product->id,
            'store_title' => 'Belgian Chocolate Croissant',
            'short_description' => 'Flaky layers filled with dark chocolate.',
            'description' => 'Made with pure European butter and rich 70% chocolate batons.',
            'meta_title' => 'Belgian Chocolate Croissant | Moshel Edibles',
            'meta_description' => 'Order fresh Belgian chocolate croissants online in Lagos.',
            'is_published' => true,
            'is_featured' => false,
        ]);

        $response = $this->actingAs($admin, 'admin')->get("/admin/store/products/{$product->slug}/edit");

        $response->assertOk();
        $response->assertSee('Belgian Chocolate Croissant');
        $response->assertSee('Store Information');
        $response->assertSee('Search Engine Information');
        $response->assertSee('ERP Product');
        $response->assertSee('Product Images');
        $response->assertSee('₦2,200.00');
        $response->assertSee('Flaky layers filled with dark chocolate.');
        $response->assertSee('cdn.jsdelivr.net/npm/hugerte@1/hugerte.min.js');
    }

    public function test_admin_can_update_store_product_information()
    {
        $admin = $this->createAdmin();
        $product = $this->createProduct('Focaccia Rosemary', 3000.0);

        $storeProduct = StoreProduct::create([
            'product_id' => $product->id,
            'store_title' => 'Old Title',
        ]);

        $response = $this->actingAs($admin, 'admin')->put("/admin/store/products/{$product->slug}/update", [
            'store_title' => 'Artisanal Rosemary & Sea Salt Focaccia',
            'short_description' => 'Infused with extra virgin olive oil and fresh organic rosemary.',
            'description' => 'Slow fermented for 24 hours to develop deep complex flavors.',
            'meta_title' => 'Rosemary Focaccia | Moshel Edibles',
            'meta_description' => 'Buy artisan rosemary focaccia bread freshly baked in Lagos.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('store_products', [
            'id' => $storeProduct->id,
            'store_title' => 'Artisanal Rosemary & Sea Salt Focaccia',
            'short_description' => 'Infused with extra virgin olive oil and fresh organic rosemary.',
            'meta_title' => 'Rosemary Focaccia | Moshel Edibles',
        ]);
    }

    public function test_admin_can_update_publication_and_featured_status()
    {
        $admin = $this->createAdmin();
        $product = $this->createProduct('Danish Pastry', 1600.0);

        $storeProduct = StoreProduct::create([
            'product_id' => $product->id,
            'is_published' => false,
            'is_featured' => false,
        ]);

        $response = $this->actingAs($admin, 'admin')->patch("/admin/store/products/{$product->slug}/status", [
            'is_published' => '1',
            'is_featured' => '1',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('store_products', [
            'id' => $storeProduct->id,
            'is_published' => true,
            'is_featured' => true,
        ]);
    }

    public function test_admin_can_manage_product_images()
    {
        $admin = $this->createAdmin();
        $product = $this->createProduct('Cinnamon Roll', 1400.0);

        $storeProduct = StoreProduct::create([
            'product_id' => $product->id,
            'store_title' => 'Glazed Cinnamon Roll',
            'is_published' => true,
        ]);

        // Upload first image
        $tempDir = public_path("uploads/store/products/{$product->slug}");
        File::ensureDirectoryExists($tempDir);

        $file = UploadedFile::fake()->image('cinnamon.jpg', 600, 600);

        $uploadResponse = $this->actingAs($admin, 'admin')->post("/admin/store/products/{$product->slug}/images/add", [
            'image' => $file,
            'alt_text' => 'Cinnamon roll with vanilla glaze',
            'is_primary' => '1',
        ]);

        $uploadResponse->assertRedirect();

        $image = StoreProductImage::where('store_product_id', $storeProduct->id)->first();
        $this->assertNotNull($image);
        $this->assertTrue($image->is_primary);
        $this->assertEquals('Cinnamon roll with vanilla glaze', $image->alt_text);

        // Add second image
        $file2 = UploadedFile::fake()->image('cinnamon2.jpg', 600, 600);
        $this->actingAs($admin, 'admin')->post("/admin/store/products/{$product->slug}/images/add", [
            'image' => $file2,
            'alt_text' => 'Side view',
            'is_primary' => '0',
        ]);

        $image2 = StoreProductImage::where('store_product_id', $storeProduct->id)
            ->where('id', '!=', $image->id)
            ->first();
        $this->assertNotNull($image2);
        $this->assertFalse($image2->is_primary);

        // Promote image 2 to primary
        $promoteResponse = $this->actingAs($admin, 'admin')->patch(
            "/admin/store/products/{$product->slug}/images/{$image2->uuid}/primary"
        );
        $promoteResponse->assertRedirect();

        $this->assertTrue($image2->fresh()->is_primary);
        $this->assertFalse($image->fresh()->is_primary);

        // Delete image
        $deleteResponse = $this->actingAs($admin, 'admin')->delete(
            "/admin/store/products/{$product->slug}/images/{$image2->uuid}/delete"
        );
        $deleteResponse->assertRedirect();
        $this->assertSoftDeleted('store_product_images', ['id' => $image2->id]);

        // Cleanup created physical test files
        if (File::isDirectory($tempDir)) {
            File::deleteDirectory($tempDir);
        }
    }
}
