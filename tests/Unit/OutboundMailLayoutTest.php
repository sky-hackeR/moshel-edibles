<?php

namespace Tests\Unit;

use App\Models\Sale;
use App\Models\SiteInfo;
use App\Notifications\AdminResetPassword;
use App\Notifications\CustomerOrderConfirmed;
use App\Notifications\CustomerPaymentFailed;
use App\Notifications\CustomerResetPassword;
use App\Notifications\CustomerVerifyEmail;
use App\Notifications\StaffResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OutboundMailLayoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_notifications_render_through_the_shared_mail_layout()
    {
        $customer = (object) ['name' => 'Customer', 'email' => 'customer@example.test', 'slug' => 'customer-slug'];
        $admin = (object) ['name' => 'Admin', 'email' => 'admin@example.test'];
        $staff = (object) ['name' => 'Staff', 'email' => 'staff@example.test'];
        SiteInfo::create(['logo' => 'public\\uploads\\siteInfo\\logo.png']);
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
            (new CustomerVerifyEmail())->toMail($customer),
            (new CustomerOrderConfirmed($sale))->toMail($customer),
            (new CustomerPaymentFailed($sale))->toMail($customer),
        ];

        $this->assertSame([
            'mail.notifications.passwordReset',
            'mail.notifications.passwordReset',
            'mail.notifications.passwordReset',
            'mail.notifications.emailVerification',
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
        $verificationUrl = $messages[3]->viewData['verificationUrl'];
        $this->assertStringContainsString('/email/verify/customer-slug/', $verificationUrl);
        $this->assertStringContainsString('signature=', $verificationUrl);

        $inlineMessage = new class {
            public $embeddedFile;

            public function embed($file)
            {
                $this->embeddedFile = $file;
                return 'cid:site-logo';
            }
        };
        $verificationHtml = view('mail.notifications.emailVerification', array_merge(
            $messages[3]->viewData,
            ['message' => $inlineMessage]
        ))->render();
        $this->assertStringContainsString('src="cid:site-logo"', $verificationHtml);
        $this->assertSame(public_path('uploads/siteInfo/logo.png'), $inlineMessage->embeddedFile);

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