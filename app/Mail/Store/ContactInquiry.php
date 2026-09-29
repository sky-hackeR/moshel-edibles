<?php

namespace App\Mail\Store;

use App\Mail\QueuedMailable;

class ContactInquiry extends QueuedMailable
{
    public $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function build()
    {
        return $this->replyTo($this->payload['clientEmail'], $this->payload['clientName'])
            ->subject('Order Specification: ' . strtok($this->payload['items'], ','))
            ->view('mail.store.contactInquiry');
    }
}