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

    public function test_preview_iframe_inherits_secure_scheme_when_rendered_on_https()
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin, 'admin')->get('https://localhost/admin/siteSettings');

        $response->assertStatus(200);
        $response->assertSee('<iframe src="https://localhost"', false);
        $response->assertDontSee('<iframe src="http://', false);
    }

    public function test_replacing_existing_custom_logo_and_favicon_deletes_old_files_and_preserves_new_files()
    {
        $admin = $this->createAdmin();

        $testingDir = public_path('uploads/siteInfo/testing');
        if (!File::isDirectory($testingDir)) {
            File::makeDirectory($testingDir, 0755, true);
        }

        // 1. Initial upload
        $setting = SiteInfo::create([
            'site_name'   => 'Moshel Initial Brand',
            'description' => 'Initial description',
        ]);

        $initialLogo = UploadedFile::fake()->image('initial_logo.png', 200, 50);
        $initialFavicon = UploadedFile::fake()->image('initial_favicon.png', 32, 32);

        $this->actingAs($admin, 'admin')->post(route('updateSiteInfo'), [
            'site_info_id' => $setting->id,
            'logo'         => $initialLogo,
            'favicon'      => $initialFavicon,
        ])->assertRedirect();

        $firstSetting = $setting->fresh();
        $oldLogoPath = public_path($firstSetting->logo);
        $oldFaviconPath = public_path($firstSetting->favicon);

        $this->assertFileExists($oldLogoPath);
        $this->assertFileExists($oldFaviconPath);

        // 2. Replacement upload
        $replacementLogo = UploadedFile::fake()->image('replacement_logo.png', 250, 60);
        $replacementFavicon = UploadedFile::fake()->image('replacement_favicon.png', 32, 32);

        $this->actingAs($admin, 'admin')->post(route('updateSiteInfo'), [
            'site_info_id' => $setting->id,
            'logo'         => $replacementLogo,
            'favicon'      => $replacementFavicon,
        ])->assertRedirect();

        $secondSetting = $setting->fresh();
        $newLogoPath = public_path($secondSetting->logo);
        $newFaviconPath = public_path($secondSetting->favicon);

        // Assert paths changed
        $this->assertNotEquals($oldLogoPath, $newLogoPath);
        $this->assertNotEquals($oldFaviconPath, $newFaviconPath);

        // Assert old files were unlinked
        $this->assertFileDoesNotExist($oldLogoPath);
        $this->assertFileDoesNotExist($oldFaviconPath);

        // Assert new files exist
        $this->assertFileExists($newLogoPath);
        $this->assertFileExists($newFaviconPath);

        // Assert cache-busting naming convention
        $this->assertMatchesRegularExpression('/logo_\d+_[A-Za-z0-9]+\.png$/', $secondSetting->logo);
        $this->assertMatchesRegularExpression('/favicon_\d+_[A-Za-z0-9]+\.png$/', $secondSetting->favicon);
    }

    public function test_template_default_images_are_protected_from_deletion_during_replacement()
    {
        $admin = $this->createAdmin();

        $templateDir = public_path('frontAssets/testing');
        if (!File::isDirectory($templateDir)) {
            File::makeDirectory($templateDir, 0755, true);
        }

        $templateLogo = $templateDir . '/default_brand_logo.png';
        $templateFavicon = $templateDir . '/default_brand_favicon.png';
        File::put($templateLogo, 'fake template logo binary content');
        File::put($templateFavicon, 'fake template favicon binary content');

        $this->assertFileExists($templateLogo);
        $this->assertFileExists($templateFavicon);

        $setting = SiteInfo::create([
            'site_name' => 'Template Default Brand',
            'logo'      => 'frontAssets/testing/default_brand_logo.png',
            'favicon'   => 'frontAssets/testing/default_brand_favicon.png',
        ]);

        $customLogo = UploadedFile::fake()->image('new_custom_logo.png', 200, 50);
        $customFavicon = UploadedFile::fake()->image('new_custom_favicon.png', 32, 32);

        $this->actingAs($admin, 'admin')->post(route('updateSiteInfo'), [
            'site_info_id' => $setting->id,
            'logo'         => $customLogo,
            'favicon'      => $customFavicon,
        ])->assertRedirect();

        $fresh = $setting->fresh();

        // New files saved under testing directory
        $this->assertStringContainsString('uploads/siteInfo/testing/logo_', $fresh->logo);
        $this->assertStringContainsString('uploads/siteInfo/testing/favicon_', $fresh->favicon);
        $this->assertFileExists(public_path($fresh->logo));
        $this->assertFileExists(public_path($fresh->favicon));

        // CRITICAL: The template defaults containing 'frontAssets' were NOT deleted
        $this->assertFileExists($templateLogo);
        $this->assertFileExists($templateFavicon);
    }

    public function test_unrelated_files_are_never_deleted_during_branding_replacement()
    {
        $admin = $this->createAdmin();

        $testingDir = public_path('uploads/siteInfo/testing');
        if (!File::isDirectory($testingDir)) {
            File::makeDirectory($testingDir, 0755, true);
        }

        $unrelatedFile = $testingDir . '/unrelated_document.txt';
        File::put($unrelatedFile, 'Important company policy text');
        $this->assertFileExists($unrelatedFile);

        $setting = SiteInfo::create([
            'site_name' => 'Brand Testing',
        ]);

        $newLogo = UploadedFile::fake()->image('first_logo.png', 200, 50);
        $this->actingAs($admin, 'admin')->post(route('updateSiteInfo'), [
            'site_info_id' => $setting->id,
            'logo'         => $newLogo,
        ])->assertRedirect();

        // Unrelated file must still exist
        $this->assertFileExists($unrelatedFile);
    }

    public function test_failed_branding_replacement_does_not_destroy_previously_valid_asset()
    {
        $admin = $this->createAdmin();

        $setting = SiteInfo::create([
            'site_name' => 'Stable Brand',
        ]);

        // Upload initial valid logo
        $validLogo = UploadedFile::fake()->image('valid_logo.png', 200, 50);
        $this->actingAs($admin, 'admin')->post(route('updateSiteInfo'), [
            'site_info_id' => $setting->id,
            'logo'         => $validLogo,
        ])->assertRedirect();

        $savedLogo = $setting->fresh()->logo;
        $savedLogoPath = public_path($savedLogo);
        $this->assertFileExists($savedLogoPath);

        // Attempt replacement with invalid file (e.g. text file instead of image)
        $invalidFile = UploadedFile::fake()->create('malicious_or_corrupt.txt', 10, 'text/plain');

        $this->actingAs($admin, 'admin')->post(route('updateSiteInfo'), [
            'site_info_id' => $setting->id,
            'logo'         => $invalidFile,
        ])->assertRedirect();

        // Original database field and physical file must be completely intact
        $this->assertSame($savedLogo, $setting->fresh()->logo);
        $this->assertFileExists($savedLogoPath);
    }

    public function test_missing_old_asset_file_is_handled_safely_during_replacement()
    {
        $admin = $this->createAdmin();

        // Point to a file that does not actually exist on disk
        $setting = SiteInfo::create([
            'site_name' => 'Ghost Asset Brand',
            'logo'      => 'uploads/siteInfo/testing/ghost_file_404.png',
        ]);

        $this->assertFileDoesNotExist(public_path($setting->logo));

        $validLogo = UploadedFile::fake()->image('new_logo_after_ghost.png', 200, 50);

        // Must succeed without throwing file_exists / unlink exceptions
        $response = $this->actingAs($admin, 'admin')->post(route('updateSiteInfo'), [
            'site_info_id' => $setting->id,
            'logo'         => $validLogo,
        ]);

        $response->assertRedirect();

        $fresh = $setting->fresh();
        $this->assertFileExists(public_path($fresh->logo));
        $this->assertStringContainsString('uploads/siteInfo/testing/logo_', $fresh->logo);
    }

    protected function tearDown(): void
    {
        // Clean up test upload files
        $testingDir = public_path('uploads/siteInfo/testing');
        if (File::isDirectory($testingDir)) {
            File::deleteDirectory($testingDir);
        }

        $templateTestDir = public_path('frontAssets/testing');
        if (File::isDirectory($templateTestDir)) {
            File::deleteDirectory($templateTestDir);
        }

        parent::tearDown();
    }
}
