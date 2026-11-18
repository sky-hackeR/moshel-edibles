<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthViewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_redesigned_login_page()
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertViewIs('admin.auth.login');
        $response->assertSee('Admin Control');
        $response->assertSee('Admin Identifier');
        $response->assertSee('Security Password');
        $response->assertSee('AUTHENTICATE');
        $response->assertSee('Keep me logged in');
        $response->assertSee('Recovery?');
        $response->assertSee('Return to Storefront');
    }

    public function test_admin_can_view_redesigned_password_request_page()
    {
        $response = $this->get('/admin/password/reset');

        $response->assertStatus(200);
        $response->assertViewIs('admin.auth.passwords.email');
        $response->assertSee('Reset Password');
        $response->assertSee('Email Address');
        $response->assertSee('SEND RESET LINK');
        $response->assertSee('Back to Sign In');
    }

    public function test_admin_can_view_redesigned_password_reset_page()
    {
        $response = $this->get('/admin/password/reset/sample-token-12345?email=admin@test.com');

        $response->assertStatus(200);
        $response->assertViewIs('admin.auth.passwords.reset');
        $response->assertSee('Set New Password');
        $response->assertSee('Confirm Admin Email');
        $response->assertSee('New Password');
        $response->assertSee('Confirm New Password');
        $response->assertSee('UPDATE PASSWORD');
    }
}
