<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\Staff;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class CustomerDeletionTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(string $name = 'Super Admin', string $email = 'superadmin@example.test'): Admin
    {
        return Admin::create([
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make('password123'),
        ]);
    }

    private function createCustomer(string $name = 'John Doe', string $email = 'john@example.test'): Customer
    {
        return Customer::create([
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make('secret123'),
        ]);
    }

    public function test_authorized_admin_can_delete_customer_with_soft_delete()
    {
        $admin = $this->createAdmin();
        $customer = $this->createCustomer('Adebayo Johnson', 'adebayo@example.test');

        $response = $this->actingAs($admin, 'admin')->post(url('admin/deleteCustomer'), [
            'customer_id' => $customer->id,
        ]);

        $response->assertRedirect();

        // 1. Soft-delete is preserved: record still in table with deleted_at populated
        $this->assertSoftDeleted('customers', [
            'id' => $customer->id,
        ]);

        $this->assertNotNull($customer->fresh()->deleted_at);
    }

    public function test_customer_deletion_exception_is_handled_gracefully_and_record_remains_intact()
    {
        $admin = $this->createAdmin();
        $customer = $this->createCustomer('Fail Target', 'fail-target@example.test');

        // Hook into the model deleting event to simulate a database failure / constraint exception
        Customer::deleting(function () {
            throw new \RuntimeException('Simulated low-level database constraint failure on customer delete');
        });

        Log::shouldReceive('error')
            ->once()
            ->withArgs(function ($message) {
                return str_contains($message, 'Customer deletion failed: Simulated low-level database constraint failure');
            });

        $response = $this->actingAs($admin, 'admin')->post(url('admin/deleteCustomer'), [
            'customer_id' => $customer->id,
        ]);

        $response->assertRedirect();

        // Customer must NOT be deleted
        $freshCustomer = $customer->fresh();
        $this->assertNotNull($freshCustomer);
        $this->assertNull($freshCustomer->deleted_at);
        $this->assertDatabaseHas('customers', [
            'id'         => $customer->id,
            'deleted_at' => null,
        ]);
    }

    public function test_unauthorized_users_cannot_delete_customer()
    {
        $customer = $this->createCustomer('Protected Customer', 'protected@example.test');

        // 1. Guest request
        $guestResponse = $this->post(url('admin/deleteCustomer'), [
            'customer_id' => $customer->id,
        ]);
        $guestResponse->assertRedirect(route('admin.login'));
        $this->assertNull($customer->fresh()->deleted_at);

        // 2. Staff request
        $staff = Staff::create([
            'name'     => 'Staff Cashier',
            'email'    => 'staff-cashier@example.test',
            'password' => Hash::make('password123'),
        ]);

        $staffResponse = $this->actingAs($staff, 'staff')->post(url('admin/deleteCustomer'), [
            'customer_id' => $customer->id,
        ]);
        $staffResponse->assertRedirect(route('admin.login'));
        $this->assertNull($customer->fresh()->deleted_at);
    }

    public function test_customer_deletion_validates_customer_id()
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin, 'admin')->post(url('admin/deleteCustomer'), [
            'customer_id' => 999999, // Non-existent ID
        ]);

        $response->assertRedirect();
        $this->assertEquals(0, Customer::count());
    }
}
