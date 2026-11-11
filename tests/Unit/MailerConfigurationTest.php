<?php

namespace Tests\Unit;

use App\Mail\Store\ContactInquiry;
use App\Mail\Store\OrderReceived;
use App\Models\Sale;
use App\Notifications\CustomerOrderConfirmed;
use App\Notifications\CustomerPaymentFailed;
use Tests\TestCase;

class MailerConfigurationTest extends TestCase
{
    public function test_dedicated_mailers_are_properly_configured()
    {
        $this->assertSame('moshedib@moshedibles.com', config('mail.mailers.admin.username'));
        $this->assertSame('moshedib@moshedibles.com', config('mail.mailers.admin.from.address'));

        $this->assertSame('order@moshedibles.com', config('mail.mailers.order.username'));
        $this->assertSame('order@moshedibles.com', config('mail.mailers.order.from.address'));

        $this->assertSame('info@moshedibles.com', config('mail.mailers.info.username'));
        $this->assertSame('info@moshedibles.com', config('mail.mailers.info.from.address'));
    }

    public function test_order_mailables_and_notifications_use_order_mailer()
    {
        $sale = new Sale(['reference_no' => 'MOS-123', 'order_status' => 'processing']);
        $customer = (object) ['name' => 'John', 'email' => 'john@example.test'];

        $orderReceived = new OrderReceived($sale);
        $this->assertSame('order', $orderReceived->mailer);

        $orderConfirmed = (new CustomerOrderConfirmed($sale))->toMail($customer);
        $this->assertSame('order', $orderConfirmed->mailer);

        $paymentFailed = (new CustomerPaymentFailed($sale))->toMail($customer);
        $this->assertSame('order', $paymentFailed->mailer);
    }

    public function test_inquiry_mailable_uses_info_mailer()
    {
        $inquiry = new ContactInquiry([
            'clientName' => 'Alice',
            'clientEmail' => 'alice@example.test',
            'clientPhone' => '123456789',
            'deliveryDate' => '2026-12-01',
            'items' => 'Cake',
            'specifications' => 'Chocolate cake please',
        ]);

        $this->assertSame('info', $inquiry->mailer);
    }
}
