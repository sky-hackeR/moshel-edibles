<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class CustomerVerifyEmail extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $verificationUrl = URL::temporarySignedRoute(
            'customer.verification.verify',
            now()->addMinutes(60),
            [
                'customer' => $notifiable->slug,
                'hash' => sha1($notifiable->email),
            ]
        );

        return (new MailMessage)
            ->subject('Verify your email address')
            ->view('mail.notifications.emailVerification', [
                'name' => $notifiable->name,
                'verificationUrl' => $verificationUrl,
            ]);
    }
}