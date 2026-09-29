<?php

namespace Tests\Unit;

use App\Models\Sale;
use App\Notifications\AdminResetPassword;
use App\Notifications\CustomerOrderConfirmed;
use App\Notifications\CustomerPaymentFailed;
use App\Notifications\CustomerResetPassword;
use App\Notifications\StaffResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OutboundMailLayoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_notifications_render_through_the_shared_mail_layout()
    {
        $customer = (object) ['name' => 'Customer', 'email' => 'customer@example.test'];
        $admin = (object) ['name' => 'Admin', 'email' => 'admin@example.test'];
        $staff = (object) ['name' => 'Staff', 'email' => 'staff@example.test'];
        $sale = new Sale([
            'reference_no' => 'MOS-TEST123456',
            'payment_status' => 'paid',
            'order_status' => 'processing',
            'payable_amount' => 10,
        ]);

        $messages = [
            (new AdminResetPassword('admin-token'))->toMail($admin),
            (new StaffResetPassword('staff-token'))->toMail($staff),
            (new CustomerResetPassword('customer-token'))->toMail($customer),
            (new CustomerOrderConfirmed($sale))->toMail($customer),
            (new CustomerPaymentFailed($sale))->toMail($customer),
        ];

        $this->assertSame([
            'mail.notifications.passwordReset',
            'mail.notifications.passwordReset',
            'mail.notifications.passwordReset',
            'mail.notifications.orderUpdate',
            'mail.notifications.paymentFailed',
        ], array_map(function ($message) {
            return $message->view;
        }, $messages));

        foreach ($messages as $message) {
            $html = view($message->view, $message->viewData)->render();
            $this->assertStringContainsString('Automated business communication', $html);
        }

        $this->assertStringContainsString('/password/reset/customer-token', $messages[2]->viewData['resetUrl']);

        $reviewSale = new Sale([
            'reference_no' => 'MOS-REVIEW123456',
            'payment_status' => 'review',
            'order_status' => 'payment_review',
            'payable_amount' => 10,
        ]);
        $reviewHtml = view('mail.notifications.orderUpdate', [
            'name' => 'Customer',
            'sale' => $reviewSale,
            'accountUrl' => route('customer.account'),
        ])->render();

        $this->assertStringContainsString('amount or currency did not match', $reviewHtml);
        $this->assertStringNotContainsString('No further payment is needed.', $reviewHtml);
    }
}