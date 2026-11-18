<?php

namespace Tests\Feature\Staff;

use App\Models\Staff;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffErrorPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_hitting_non_existent_staff_route_gets_standalone_staff_404_view()
    {
        $response = $this->get('/staff/non-existent-route-xyz');

        $response->assertStatus(404);
        $response->assertViewIs('staff.errors.404');
        $response->assertSee('Workstation Page Not Found');
        $response->assertSee('Staff Sign In');
        // Ensure sidebar layout is NOT rendered
        $response->assertDontSee('id="layout-wrapper"', false);
        $response->assertDontSee('id="sidebar-menu"', false);
    }

    public function test_authenticated_staff_hitting_non_existent_route_sees_dashboard_shortcut()
    {
        $staff = Staff::create([
            'name' => 'Baker John',
            'email' => 'baker@moshel.test',
            'phone' => '08012345678',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($staff, 'staff')->get('/staff/non-existent-route-abc');

        $response->assertStatus(404);
        $response->assertViewIs('staff.errors.404');
        $response->assertSee('Workstation Page Not Found');
        $response->assertSee('Staff Dashboard');
        $response->assertSee('POS Terminal');
        $response->assertDontSee('id="layout-wrapper"', false);
    }

    public function test_staff_500_error_view_renders_cleanly()
    {
        $view = view('staff.errors.500')->render();

        $this->assertStringContainsString('500', $view);
        $this->assertStringContainsString('Internal Workstation Disruption', $view);
        $this->assertStringContainsString('Retry Action', $view);
        $this->assertStringNotContainsString('id="layout-wrapper"', $view);
    }
}
