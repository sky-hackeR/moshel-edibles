<?php

namespace App\Notifications;

use App\Notifications\Concerns\QueuesOutboundMail;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerOrderConfirmed extends Notification implements ShouldQueue, ShouldBeEncrypted
{
    use QueuesOutboundMail;

    public $sale;

    public function __construct($sale)
    {
        $this->sale = $sale;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(($this->sale->order_status === 'payment_review' ? 'Payment received, order under review: ' : 'Order confirmed: ') . $this->sale->reference_no)
            ->view('mail.notifications.orderUpdate', [
                'name' => $notifiable->name,
                'sale' => $this->sale,
                'accountUrl' => route('customer.account'),
            ]);
    }
}