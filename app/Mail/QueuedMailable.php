<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

abstract class QueuedMailable extends Mailable
{
    use SerializesModels;
}
