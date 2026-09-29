<?php

namespace App\Notifications;

use App\Notifications\Concerns\QueuesOutboundMail;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerPaymentFailed extends Notification implements ShouldQueue, ShouldBeEncrypted
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
            ->subject('Payment needs attention: ' . $this->sale->reference_no)
            ->view('mail.notifications.paymentFailed', [
                'name' => $notifiable->name,
                'sale' => $this->sale,
                'checkoutUrl' => route('store.checkout'),
            ]);
    }
}