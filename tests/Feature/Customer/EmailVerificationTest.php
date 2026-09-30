<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Notifications\CustomerVerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_an_inactive_customer_and_sends_verification()
    {
        Notification::fake();

        $response = $this->post(route('customer.register.submit'), [
            'name' => 'New Customer',
            'email' => 'new-customer@example.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $customer = Customer::where('email', 'new-customer@example.test')->firstOrFail();

        $response->assertRedirect();
        $response->assertSessionHas('auth_modal', 'login');
        $response->assertSessionHas('verification_sent', true);
        $this->assertGuest('customer');
        $this->assertSame('new-customer', $customer->slug);
        $this->assertSame('inactive', $customer->status);
        $this->assertNull($customer->email_verified_at);
        Notification::assertSentTo($customer, CustomerVerifyEmail::class);
    }

    public function test_unverified_login_displays_a_resend_action_inside_the_sign_in_modal()
    {
        $customer = Customer::create([
            'name' => 'Pending Customer',
            'email' => 'pending-modal@example.test',
            'password' => bcrypt('password123'),
        ]);

        $this->post(route('customer.login.submit'), [
            'email' => $customer->email,
            'password' => 'password123',
        ])->assertSessionHas('auth_modal', 'login')
            ->assertSessionHas('verification_pending', $customer->email);

        $this->get(route('store.welcome'))
            ->assertOk()
            ->assertSee('Your account exists and your password is correct')
            ->assertSee('Send verification email');
    }

    public function test_unverified_customer_can_request_another_verification_email()
    {
        Notification::fake();

        $customer = Customer::create([
            'name' => 'Resend Customer',
            'email' => 'resend@example.test',
            'password' => bcrypt('password123'),
        ]);

        $this->post(route('customer.verification.resend'), ['email' => $customer->email])
            ->assertRedirect()
            ->assertSessionHas('auth_modal', 'login')
            ->assertSessionHas('verification_sent', true);

        Notification::assertSentTo($customer, CustomerVerifyEmail::class);
        $this->assertSame('inactive', $customer->fresh()->status);
    }

    public function test_signed_email_verification_activates_customer_and_allows_login()
    {
        $customer = Customer::create([
            'name' => 'Verified Customer',
            'email' => 'verified@example.test',
            'password' => bcrypt('password123'),
            'status' => 'inactive',
        ]);

        $url = URL::temporarySignedRoute(
            'customer.verification.verify',
            now()->addMinutes(60),
            ['customer' => $customer->slug, 'hash' => sha1($customer->email)]
        );

        $this->get($url)->assertRedirect(route('store.welcome'))
            ->assertSessionHas('auth_modal', 'login');
        $this->assertSame('active', $customer->fresh()->status);
        $this->assertNotNull($customer->fresh()->email_verified_at);

        $this->post(route('customer.login.submit'), [
            'email' => $customer->email,
            'password' => 'password123',
        ])->assertRedirect('/account');

        $this->assertAuthenticatedAs($customer->fresh(), 'customer');
    }

    public function test_unverified_customer_cannot_sign_in()
    {
        $customer = Customer::create([
            'name' => 'Pending Customer',
            'email' => 'pending@example.test',
            'password' => bcrypt('password123'),
            'status' => 'inactive',
        ]);

        $this->post(route('customer.login.submit'), [
            'email' => $customer->email,
            'password' => 'password123',
        ])->assertSessionHasErrors('email')
            ->assertSessionHas('auth_modal', 'login');

        $this->assertGuest('customer');
    }

    public function test_unknown_email_offers_account_creation_in_the_modal()
    {
        $this->from(route('store.welcome'))
            ->post(route('customer.login.submit'), [
                'email' => 'missing@example.test',
                'password' => 'password123',
            ])
            ->assertRedirect(route('store.welcome'))
            ->assertSessionHas('auth_modal', 'login')
            ->assertSessionHas('customer_not_found', true);

        $this->get(route('store.welcome'))
            ->assertOk()
            ->assertSee('No customer account was found for this email')
            ->assertSee('Create an account');
    }

    public function test_wrong_password_does_not_offer_email_verification()
    {
        $customer = Customer::create([
            'name' => 'Wrong Password Customer',
            'email' => 'wrong-password@example.test',
            'password' => bcrypt('correct-password'),
        ]);

        $this->post(route('customer.login.submit'), [
            'email' => $customer->email,
            'password' => 'incorrect-password',
        ])->assertSessionHasErrors('email')
            ->assertSessionHas('auth_modal', 'login')
            ->assertSessionMissing('verification_pending');
    }
}