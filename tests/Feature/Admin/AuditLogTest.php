<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\AuditLog;
use App\Models\AuditSession;
use App\Models\Customer;
use App\Models\Staff;
use App\Services\Audit\AuditLogger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(string $email = 'admin@example.test', string $name = 'Admin User'): Admin
    {
        return Admin::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make('secret-password'),
        ]);
    }

    private function createStaff(string $email = 'staff@example.test', string $name = 'Staff User'): Staff
    {
        return Staff::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make('secret-password'),
        ]);
    }

    private function createCustomer(string $email = 'customer@example.test', string $name = 'Customer User'): Customer
    {
        return Customer::create([
            'name' => $name,
            'email' => $email,
            'slug' => 'customer-' . uniqid(),
            'password' => Hash::make('secret-password'),
        ]);
    }

    public function test_admin_can_view_audit_log_dashboard()
    {
        $admin = $this->createAdmin('admin_test@moshedibles.com', 'Super Admin');

        $session = AuditSession::create([
            'session_id' => 'test_session_admin_123',
            'user_type' => 'admin',
            'user_id' => $admin->id,
            'user_name' => 'Target Admin',
            'user_email' => 'target@moshedibles.com',
            'ip_address' => '127.0.0.1',
            'device' => 'Chrome on Windows',
            'started_at' => now(),
            'last_activity_at' => now(),
            'is_active' => true,
        ]);

        AuditLog::create([
            'audit_session_id' => $session->id,
            'user_type' => 'admin',
            'user_id' => $admin->id,
            'category' => 'Auth',
            'action' => 'Login',
            'description' => 'Admin logged in',
            'method' => 'POST',
            'url' => 'http://localhost/admin/login',
            'ip_address' => '127.0.0.1',
            'created_at' => now(),
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.auditLogs.index'));

        $response->assertStatus(200);
        $response->assertSee('Audit &amp; Activity Log', false);
        $response->assertSee('Target Admin');
        $response->assertSee('Admin Sessions');
        $response->assertSee('Staff Sessions');
        $response->assertSee('Customer Sessions');
    }

    public function test_audit_logs_can_be_filtered_by_role()
    {
        $viewer = $this->createAdmin('viewer@example.test', 'Viewer Admin');
        $adminTarget = $this->createAdmin('admin1@example.test', 'Target Officer Alpha');
        $staff = $this->createStaff('staff1@example.test', 'Target Staff Beta');
        $customer = $this->createCustomer('customer1@example.test', 'Target Customer Gamma');

        AuditSession::create([
            'session_id' => 'session_admin',
            'user_type' => 'admin',
            'user_id' => $adminTarget->id,
            'user_name' => 'Target Officer Alpha',
            'started_at' => now(),
            'last_activity_at' => now(),
            'is_active' => true,
        ]);

        AuditSession::create([
            'session_id' => 'session_staff',
            'user_type' => 'staff',
            'user_id' => $staff->id,
            'user_name' => 'Target Staff Beta',
            'started_at' => now(),
            'last_activity_at' => now(),
            'is_active' => true,
        ]);

        AuditSession::create([
            'session_id' => 'session_customer',
            'user_type' => 'customer',
            'user_id' => $customer->id,
            'user_name' => 'Target Customer Gamma',
            'started_at' => now(),
            'last_activity_at' => now(),
            'is_active' => true,
        ]);

        // Filter by admin
        $this->actingAs($viewer, 'admin')
            ->get(route('admin.auditLogs.index', ['role' => 'admin']))
            ->assertStatus(200)
            ->assertSee('Target Officer Alpha')
            ->assertDontSee('Target Staff Beta');

        // Filter by staff
        $this->actingAs($viewer, 'admin')
            ->get(route('admin.auditLogs.index', ['role' => 'staff']))
            ->assertStatus(200)
            ->assertSee('Target Staff Beta')
            ->assertDontSee('Target Customer Gamma');

        // Filter by customer
        $this->actingAs($viewer, 'admin')
            ->get(route('admin.auditLogs.index', ['role' => 'customer']))
            ->assertStatus(200)
            ->assertSee('Target Customer Gamma')
            ->assertDontSee('Target Officer Alpha');
    }

    public function test_audit_session_returns_json_details_for_modal()
    {
        $admin = $this->createAdmin();

        $session = AuditSession::create([
            'session_id' => 'modal_session_123',
            'user_type' => 'admin',
            'user_id' => $admin->id,
            'user_name' => $admin->name,
            'user_email' => $admin->email,
            'started_at' => now(),
            'last_activity_at' => now(),
            'is_active' => true,
        ]);

        AuditLog::create([
            'audit_session_id' => $session->id,
            'user_type' => 'admin',
            'user_id' => $admin->id,
            'category' => 'POS',
            'action' => 'Processed POS Sale',
            'description' => 'Sale MOS-1234 completed',
            'method' => 'POST',
            'url' => 'http://localhost/admin/processSale',
            'details' => ['reference' => 'MOS-1234', 'amount' => 5000],
            'created_at' => now(),
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->getJson(route('admin.auditLogs.show', $session));

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('session.uuid', $session->uuid);
        $response->assertJsonPath('logs.0.action', 'Processed POS Sale');
        $response->assertJsonPath('logs.0.details.reference', 'MOS-1234');
    }

    public function test_full_page_audit_trail_resolves_by_uuid_without_distinct_order_by_error()
    {
        $admin = $this->createAdmin();

        $session = AuditSession::create([
            'session_id' => 'full_page_session_123',
            'user_type' => 'admin',
            'user_id' => $admin->id,
            'user_name' => $admin->name,
            'user_email' => $admin->email,
            'started_at' => now(),
            'last_activity_at' => now(),
            'is_active' => true,
        ]);

        AuditLog::create([
            'audit_session_id' => $session->id,
            'user_type' => 'admin',
            'user_id' => $admin->id,
            'category' => 'Auth',
            'action' => 'Login',
            'created_at' => now()->subMinute(),
        ]);

        AuditLog::create([
            'audit_session_id' => $session->id,
            'user_type' => 'admin',
            'user_id' => $admin->id,
            'category' => 'POS',
            'action' => 'Processed POS Sale',
            'created_at' => now(),
        ]);

        // URL uses uuid instead of integer id
        $this->assertNotNull($session->uuid);
        $url = route('admin.auditLogs.show', $session);
        $this->assertStringContainsString($session->uuid, $url);
        $this->assertStringNotContainsString("/session/{$session->id}", $url);

        $response = $this->actingAs($admin, 'admin')->get($url);

        $response->assertStatus(200);
        $response->assertSee('Session Audit Trail');
        $response->assertSee('Processed POS Sale');
        $response->assertSee('Auth');
        $response->assertSee('POS');
    }

    public function test_unauthenticated_admin_visitor_does_not_create_guest_admin_session()
    {
        // Visiting admin login page without credentials should not create a "Guest Admin" session
        $this->get('/admin/login');

        $this->assertDatabaseMissing('audit_sessions', [
            'user_type' => 'admin',
            'user_name' => 'Guest',
        ]);
    }

    public function test_audit_logger_sanitizes_sensitive_passwords()
    {
        $clean = AuditLogger::sanitizeDetails([
            'username' => 'testuser',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            '_token' => 'xyz',
            'product_name' => 'Vanilla Cake',
        ]);

        $this->assertSame('testuser', $clean['username']);
        $this->assertSame('Vanilla Cake', $clean['product_name']);
        $this->assertSame('[HIDDEN]', $clean['password']);
        $this->assertSame('[HIDDEN]', $clean['password_confirmation']);
        $this->assertSame('[HIDDEN]', $clean['_token']);
    }
}
