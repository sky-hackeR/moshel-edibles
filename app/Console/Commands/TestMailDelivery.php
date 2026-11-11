<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailDelivery extends Command
{
    protected $signature = 'mail:test {mailer=info : The mailer to test (info, order, admin, smtp)} {to? : The recipient email address}';

    protected $description = 'Send a test email using the specified mailer configuration';

    public function handle()
    {
        $mailer = $this->argument('mailer');
        $recipient = $this->argument('to') ?: config("mail.mailers.{$mailer}.from.address", config('mail.from.address'));

        $this->info("Attempting to send a test email via [{$mailer}] mailer to [{$recipient}]...");
        $this->table(['Setting', 'Value'], [
            ['Mailer', $mailer],
            ['Host', config("mail.mailers.{$mailer}.host")],
            ['Port', config("mail.mailers.{$mailer}.port")],
            ['Encryption', config("mail.mailers.{$mailer}.encryption")],
            ['Username', config("mail.mailers.{$mailer}.username")],
            ['From Address', config("mail.mailers.{$mailer}.from.address", config('mail.from.address'))],
            ['Recipient', $recipient],
        ]);

        try {
            Mail::mailer($mailer)->raw("This is a test email sent from Moshel Edibles via the [{$mailer}] mailer on port " . config("mail.mailers.{$mailer}.port") . ".", function ($message) use ($recipient, $mailer) {
                $message->to($recipient)
                    ->subject("SMTP Test Successful: [{$mailer}] Mailer");
            });

            $this->info("SUCCESS: Test email sent successfully via [{$mailer}]!");
            return 0;
        } catch (\Throwable $e) {
            $this->error("FAILED: Could not send email via [{$mailer}].");
            $this->line("Error: " . $e->getMessage());
            return 1;
        }
    }
}
