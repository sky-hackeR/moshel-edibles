<?php

namespace Tests\Feature\Admin;

use App\Mail\Admin\AdminCreated;
use App\Models\Admin;
use App\Models\Staff;
use App\Notifications\AdminResetPassword;
use App\Notifications\StaffResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
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

    public function test_admin_creation_dispatches_security_alert_to_creator_and_reset_link_to_new_admin()
    {
        Mail::fake();
        Notification::fake();

        $creator = $this->createAdmin('Authorizing Admin', 'authorizer@example.test');

        $response = $this->actingAs($creator, 'admin')->post(url('admin/newAdmin'), [
            'name'  => 'Ibrahim Musa',
            'email' => 'ibrahim@example.test',
        ]);

        $response->assertRedirect();

        $newAdmin = Admin::where('email', 'ibrahim@example.test')->firstOrFail();

        // 1. Password setup reset link is sent to the new admin
        Notification::assertSentTo($newAdmin, AdminResetPassword::class);

        // 2. Security alert email is sent to the creator/authorizer admin
        Mail::assertSent(AdminCreated::class, function ($mail) use ($creator, $newAdmin) {
            return $mail->hasTo($creator->email)
                && $mail->newAdmin->id === $newAdmin->id
                && $mail->creator->id === $creator->id;
        });
    }

    public function test_admin_created_mailable_has_expected_subject_and_renders_audit_content()
    {
        $creator = $this->createAdmin('Audit Creator', 'audit-creator@example.test');
        $newAdmin = Admin::create([
            'name'     => 'New Super Admin',
            'email'    => 'new-super@example.test',
            'password' => bcrypt('randompass'),
        ]);

        $mailable = new AdminCreated($newAdmin, $creator);
        $mailable->build();

        $this->assertSame('Security Alert: New Admin Added', $mailable->subject);

        $rendered = $mailable->render();
        $this->assertStringContainsString('Security Audit Alert', $rendered);
        $this->assertStringContainsString('New Super Admin', $rendered);
        $this->assertStringContainsString('new-super@example.test', $rendered);
        $this->assertStringContainsString('Audit Creator', $rendered);
        $this->assertStringContainsString('Authorized By', $rendered);
    }

    public function test_unauthorized_user_cannot_create_admin_or_trigger_emails()
    {
        Mail::fake();
        Notification::fake();

        // Guest attempt
        $guestResponse = $this->post(url('admin/newAdmin'), [
            'name'  => 'Unauthorized Guest Attempt',
            'email' => 'guest-admin@example.test',
        ]);
        $guestResponse->assertRedirect(route('admin.login'));
        $this->assertDatabaseMissing('admins', ['email' => 'guest-admin@example.test']);

        // Staff attempt (forbidden from admin management)
        $staff = Staff::create([
            'name'     => 'Staff Cashier',
            'email'    => 'cashier@example.test',
            'password' => bcrypt('password123'),
        ]);

        $staffResponse = $this->actingAs($staff, 'staff')->post(url('admin/newAdmin'), [
            'name'  => 'Staff Escalation Attempt',
            'email' => 'escalation@example.test',
        ]);
        $staffResponse->assertRedirect(route('admin.login'));
        $this->assertDatabaseMissing('admins', ['email' => 'escalation@example.test']);

        Mail::assertNothingSent();
        Notification::assertNothingSent();
    }

    public function test_failed_admin_creation_does_not_dispatch_security_alert_or_reset_link()
    {
        Mail::fake();
        Notification::fake();

        $creator = $this->createAdmin('Current Admin', 'admin@example.test');
        $existingAdmin = $this->createAdmin('Existing Admin', 'duplicate@example.test');

        // Attempt creation with duplicate email
        $response = $this->actingAs($creator, 'admin')->post(url('admin/newAdmin'), [
            'name'  => 'Duplicate Name',
            'email' => 'duplicate@example.test',
        ]);

        $response->assertRedirect();
        $this->assertEquals(2, Admin::count());

        Mail::assertNothingSent();
        Notification::assertNothingSent();
    }

    public function test_security_alert_failure_does_not_prevent_admin_creation_or_password_setup()
    {
        Notification::fake();
        \Illuminate\Support\Facades\Log::shouldReceive('error')
            ->once()
            ->with(\Mockery::pattern('/Admin creation security alert email failed:/'));

        \Illuminate\Support\Facades\Mail::shouldReceive('to')
            ->once()
            ->andReturnSelf();
        \Illuminate\Support\Facades\Mail::shouldReceive('send')
            ->once()
            ->andThrow(new \RuntimeException('SMTP 550 relay not permitted_AUTH'));

        $creator = $this->createAdmin('Creator Admin', 'creator-sim@example.test');

        $response = $this->actingAs($creator, 'admin')->post(url('admin/newAdmin'), [
            'name'  => 'New Success Admin',
            'email' => 'new-success@example.test',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('admins', [
            'email' => 'new-success@example.test',
            'name'  => 'New Success Admin',
        ]);

        $created = Admin::where('email', 'new-success@example.test')->firstOrFail();
        Notification::assertSentTo($created, AdminResetPassword::class);
    }

    public function test_password_setup_failure_does_not_prevent_admin_creation_or_security_alert()
    {
        Mail::fake();
        \Illuminate\Support\Facades\Log::shouldReceive('error')
            ->once()
            ->with(\Mockery::pattern('/Admin password setup email failed:/'));

        \Illuminate\Support\Facades\Password::shouldReceive('broker')
            ->with('admins')
            ->once()
            ->andReturnSelf();
        \Illuminate\Support\Facades\Password::shouldReceive('sendResetLink')
            ->once()
            ->andReturn('passwords.throttled');

        $creator = $this->createAdmin('Creator Admin', 'creator-broker@example.test');

        $response = $this->actingAs($creator, 'admin')->post(url('admin/newAdmin'), [
            'name'  => 'Broker Fail Admin',
            'email' => 'broker-fail@example.test',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('admins', [
            'email' => 'broker-fail@example.test',
        ]);

        $created = Admin::where('email', 'broker-fail@example.test')->firstOrFail();
        Mail::assertSent(AdminCreated::class, function ($mail) use ($creator, $created) {
            return $mail->hasTo($creator->email)
                && $mail->newAdmin->id === $created->id;
        });
    }
}
