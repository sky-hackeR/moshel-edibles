<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Staff;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class ProfileManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): Admin
    {
        return Admin::create([
            'name' => 'Alexander Pierce',
            'email' => 'alexander-' . uniqid() . '@example.test',
            'password' => Hash::make('oldpassword123'),
        ]);
    }

    private function createStaff(): Staff
    {
        return Staff::create([
            'name' => 'Jane Cashier',
            'email' => 'jane-' . uniqid() . '@example.test',
            'password' => Hash::make('oldpassword123'),
        ]);
    }

    public function test_guest_cannot_access_admin_or_staff_profile()
    {
        $this->get('/admin/profile')->assertRedirect('/admin');
        $this->get('/staff/profile')->assertRedirect('/staff');
    }

    public function test_admin_can_view_redesigned_profile_page()
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin, 'admin')->get('/admin/profile');

        $response->assertStatus(200);
        $response->assertSee('Admin Profile & Security', false);
        $response->assertSee('Personal Information');
        $response->assertSee('Password & Security', false);
        $response->assertSee($admin->name);
        $response->assertSee($admin->email);
        $response->assertSee('Super Admin');
        $response->assertViewHas('admin');
    }

    public function test_admin_can_update_profile_details()
    {
        $admin = $this->createAdmin();

        $payload = [
            'name' => 'Alexander Pierce Updated',
            'email' => 'alex.updated@example.test',
        ];

        $response = $this->actingAs($admin, 'admin')->post('/admin/updateProfile', $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Profile updated successfully!');

        $admin->refresh();
        $this->assertEquals('Alexander Pierce Updated', $admin->name);
        $this->assertEquals('alex.updated@example.test', $admin->email);
    }

    public function test_admin_can_update_password()
    {
        $admin = $this->createAdmin();

        $payload = [
            'current_password' => 'oldpassword123',
            'new_password' => 'BrandNewPass2026!',
            'new_password_confirmation' => 'BrandNewPass2026!',
        ];

        $response = $this->actingAs($admin, 'admin')->post('/admin/updatePassword', $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Password changed successfully!');

        $admin->refresh();
        $this->assertTrue(Hash::check('BrandNewPass2026!', $admin->password));
    }

    public function test_staff_can_view_redesigned_profile_page()
    {
        $staff = $this->createStaff();

        $response = $this->actingAs($staff, 'staff')->get('/staff/profile');

        $response->assertStatus(200);
        $response->assertSee('Staff Profile & Security', false);
        $response->assertSee('Personal Information');
        $response->assertSee('Password & Security', false);
        $response->assertSee($staff->name);
        $response->assertSee($staff->email);
        $response->assertSee('Floor Operations');
        $response->assertViewHas('staff');
    }

    public function test_staff_can_update_profile_details()
    {
        $staff = $this->createStaff();

        $payload = [
            'name' => 'Jane Cashier Updated',
            'email' => 'jane.updated@example.test',
        ];

        $response = $this->actingAs($staff, 'staff')->post('/staff/updateProfile', $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Profile updated successfully!');

        $staff->refresh();
        $this->assertEquals('Jane Cashier Updated', $staff->name);
        $this->assertEquals('jane.updated@example.test', $staff->email);
    }

    public function test_staff_can_update_password()
    {
        $staff = $this->createStaff();

        $payload = [
            'current_password' => 'oldpassword123',
            'new_password' => 'BrandNewStaffPass2026!',
            'new_password_confirmation' => 'BrandNewStaffPass2026!',
        ];

        $response = $this->actingAs($staff, 'staff')->post('/staff/updatePassword', $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Password updated successfully!');

        $staff->refresh();
        $this->assertTrue(Hash::check('BrandNewStaffPass2026!', $staff->password));
    }
}
