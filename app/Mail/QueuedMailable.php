<?php

namespace App\Mail;

use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;

abstract class QueuedMailable extends Mailable implements ShouldQueue, ShouldBeEncrypted
{
    use SerializesModels;

    public $queue = 'mail';
    public $tries = 1000;
    public $backoff = 60;

    public function middleware()
    {
        return [new RateLimited('outbound-mail')];
    }
}