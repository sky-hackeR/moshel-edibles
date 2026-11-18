<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminErrorPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_hitting_non_existent_admin_route_gets_standalone_admin_404_view()
    {
        $response = $this->get('/admin/non-existent-admin-route-xyz');

        $response->assertStatus(404);
        $response->assertViewIs('admin.errors.404');
        $response->assertSee('Administrative Resource Not Found');
        $response->assertSee('Admin Sign In');
        // Ensure sidebar layout is NOT rendered
        $response->assertDontSee('id="layout-wrapper"', false);
        $response->assertDontSee('id="sidebar-menu"', false);
    }

    public function test_authenticated_admin_hitting_non_existent_route_sees_dashboard_shortcut()
    {
        $admin = Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@moshel.test',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/non-existent-admin-route-abc');

        $response->assertStatus(404);
        $response->assertViewIs('admin.errors.404');
        $response->assertSee('Administrative Resource Not Found');
        $response->assertSee('Admin Dashboard');
        $response->assertSee('Inventory Center');
        $response->assertDontSee('id="layout-wrapper"', false);
    }

    public function test_admin_500_error_view_renders_cleanly()
    {
        $view = view('admin.errors.500')->render();

        $this->assertStringContainsString('500', $view);
        $this->assertStringContainsString('Internal System Disruption', $view);
        $this->assertStringContainsString('Retry Operation', $view);
        $this->assertStringNotContainsString('id="layout-wrapper"', $view);
    }

    public function test_storefront_500_error_view_renders_cleanly_using_store_layout()
    {
        $view = view('errors.500')->render();

        $this->assertStringContainsString('Internal', $view);
        $this->assertStringContainsString('Server Error', $view);
        $this->assertStringContainsString('Retry Page', $view);
    }
}
