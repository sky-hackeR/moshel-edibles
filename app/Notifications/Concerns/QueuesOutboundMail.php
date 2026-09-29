<?php

namespace App\Notifications\Concerns;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\Middleware\RateLimited;

trait QueuesOutboundMail
{
    use Queueable;

    public $tries = 1000;
    public $backoff = 60;

    public function viaQueues()
    {
        return ['mail' => 'mail'];
    }

    public function middleware()
    {
        return [new RateLimited('outbound-mail')];
    }
}