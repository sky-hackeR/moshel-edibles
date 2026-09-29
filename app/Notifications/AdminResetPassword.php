<?php

namespace App\Notifications;

use App\Notifications\Concerns\QueuesOutboundMail;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AdminResetPassword extends Notification implements ShouldQueue, ShouldBeEncrypted
{
    use QueuesOutboundMail;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a new notification instance.
     *
     * @param $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Set up your administrator password')
            ->view('mail.notifications.passwordReset', [
                'name' => $notifiable->name,
                'portalName' => 'administrator',
                'resetUrl' => url('/admin/password/reset/' . $this->token . '?email=' . urlencode($notifiable->email)),
            ]);
    }
}
