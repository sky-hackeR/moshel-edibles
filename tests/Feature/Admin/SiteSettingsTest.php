<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\SiteInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class SiteSettingsTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): Admin
    {
        return Admin::create([
            'name' => 'Settings Admin',
            'email' => 'settings-admin-' . uniqid() . '@example.test',
            'password' => bcrypt('secret123'),
        ]);
    }

    public function test_guest_cannot_access_site_settings()
    {
        $response = $this->get(route('siteSettings'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_redesigned_site_settings_page()
    {
        $admin = $this->createAdmin();

        SiteInfo::create([
            'site_name' => 'Moshel Artisan Bakehouse',
            'description' => 'Gourmet baked goods and confectionery products.',
            'logo' => 'uploads/siteInfo/logo.png',
            'favicon' => 'uploads/siteInfo/favicon.png',
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('siteSettings'));

        $response->assertStatus(200);
        $response->assertSee('System');
        $response->assertSee('Brand Configuration');
        $response->assertSee('Moshel Artisan Bakehouse');
        $response->assertSee('Customer & Browser Experience Preview', false);
        $response->assertSee('Asset Audit & Storage Matrix', false);
    }

    public function test_admin_can_update_site_name_and_description()
    {
        $admin = $this->createAdmin();

        $setting = SiteInfo::create([
            'site_name' => 'Old Bakery Name',
            'description' => 'Old description text',
        ]);

        $response = $this->actingAs($admin, 'admin')->post(route('updateSiteInfo'), [
            'site_info_id' => $setting->id,
            'site_name' => 'Moshel Premium Confectionery',
            'description' => 'Finest artisan treats and catering services.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('site_infos', [
            'id' => $setting->id,
            'site_name' => 'Moshel Premium Confectionery',
            'description' => 'Finest artisan treats and catering services.',
        ]);
    }

    public function test_admin_can_upload_branding_logo_and_favicon()
    {
        $admin = $this->createAdmin();

        $setting = SiteInfo::create([
            'site_name' => 'Moshel Test Brand',
            'description' => 'Test description',
        ]);

        $fakeLogo = UploadedFile::fake()->image('test_logo.png', 200, 50);
        $fakeFavicon = UploadedFile::fake()->image('test_favicon.png', 32, 32);

        $response = $this->actingAs($admin, 'admin')->post(route('updateSiteInfo'), [
            'site_info_id' => $setting->id,
            'site_name' => 'Moshel Test Brand',
            'logo' => $fakeLogo,
            'favicon' => $fakeFavicon,
        ]);

        $response->assertRedirect();

        $fresh = $setting->fresh();
        $this->assertNotNull($fresh->logo);
        $this->assertNotNull($fresh->favicon);
        $this->assertStringContainsString('uploads/siteInfo/', $fresh->logo);
        $this->assertStringContainsString('uploads/siteInfo/', $fresh->favicon);

        // Clean up test upload files
        $testingDir = public_path('uploads/siteInfo/testing');
        if (File::isDirectory($testingDir)) {
            File::deleteDirectory($testingDir);
        }
    }
}
