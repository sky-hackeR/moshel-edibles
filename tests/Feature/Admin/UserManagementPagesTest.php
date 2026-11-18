<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Staff;
use App\Notifications\AdminResetPassword;
use App\Notifications\StaffResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class UserManagementPagesTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(string $name = 'Super Admin', string $email = 'superadmin@example.test'): Admin
    {
        return Admin::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt('password123'),
        ]);
    }

    public function test_guest_cannot_access_admin_list()
    {
        $response = $this->get(route('admins'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_guest_cannot_access_staff_list()
    {
        $response = $this->get(route('staffs'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_redesigned_admin_list_page()
    {
        $admin1 = $this->createAdmin('Alice Adeleke', 'alice@example.test');
        $admin2 = $this->createAdmin('Bob Balogun', 'bob@example.test');

        $response = $this->actingAs($admin1, 'admin')->get(route('admins'));

        $response->assertStatus(200);
        $response->assertSee('System Administrators');
        $response->assertSee('Total Administrators');
        $response->assertSee('Alice Adeleke');
        $response->assertSee('Bob Balogun');
        $response->assertSee('Enrol New Administrator');
        $response->assertSee('Administrator Directory');
    }

    public function test_admin_can_view_redesigned_staff_list_page()
    {
        $admin = $this->createAdmin('Master Admin', 'master@example.test');
        $staff1 = Staff::create([
            'name' => 'Charlie Cashier',
            'email' => 'charlie@example.test',
            'password' => bcrypt('staffpass123'),
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('staffs'));

        $response->assertStatus(200);
        $response->assertSee('Operational Staff Directory');
        $response->assertSee('Active Staff Members');
        $response->assertSee('Charlie Cashier');
        $response->assertSee('Enrol New Staff Member');
        $response->assertSee('Operational Staff Register');
    }

    public function test_admin_can_create_new_admin_and_dispatches_password_setup()
    {
        Notification::fake();
        $admin = $this->createAdmin('Main Admin', 'main@example.test');

        $response = $this->actingAs($admin, 'admin')->post(url('admin/newAdmin'), [
            'name' => 'Kemi Williams',
            'email' => 'kemi@example.test',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('admins', [
            'email' => 'kemi@example.test',
            'name' => 'Kemi Williams',
        ]);

        $created = Admin::where('email', 'kemi@example.test')->firstOrFail();
        Notification::assertSentTo($created, AdminResetPassword::class);
    }

    public function test_admin_cannot_delete_own_account()
    {
        $admin = $this->createAdmin('Self Admin', 'self@example.test');

        $response = $this->actingAs($admin, 'admin')->post(url('admin/deleteAdmin'), [
            'admin_id' => $admin->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('admins', [
            'id' => $admin->id,
            'deleted_at' => null,
        ]);
    }

    public function test_admin_can_delete_another_admin()
    {
        $admin1 = $this->createAdmin('Admin One', 'admin1@example.test');
        $admin2 = $this->createAdmin('Admin Two', 'admin2@example.test');

        $response = $this->actingAs($admin1, 'admin')->post(url('admin/deleteAdmin'), [
            'admin_id' => $admin2->id,
        ]);

        $response->assertRedirect();
        $this->assertSoftDeleted('admins', [
            'id' => $admin2->id,
        ]);
    }

    public function test_admin_can_create_new_staff()
    {
        Notification::fake();
        $admin = $this->createAdmin('Lead Admin', 'lead@example.test');

        $response = $this->actingAs($admin, 'admin')->post(url('admin/newStaff'), [
            'name' => 'Tunde Bakare',
            'email' => 'tunde@example.test',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('staff', [
            'email' => 'tunde@example.test',
            'name' => 'Tunde Bakare',
        ]);

        $created = Staff::where('email', 'tunde@example.test')->firstOrFail();
        Notification::assertSentTo($created, StaffResetPassword::class);
    }

    public function test_admin_can_delete_staff()
    {
        $admin = $this->createAdmin('Boss Admin', 'boss@example.test');
        $staff = Staff::create([
            'name' => 'Departing Staff',
            'email' => 'departing@example.test',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->actingAs($admin, 'admin')->post(url('admin/deleteStaff'), [
            'staff_id' => $staff->id,
        ]);

        $response->assertRedirect();
        $this->assertSoftDeleted('staff', [
            'id' => $staff->id,
        ]);
    }
}
