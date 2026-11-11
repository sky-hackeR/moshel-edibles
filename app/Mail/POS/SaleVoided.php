<?php

namespace App\Mail\POS;

use App\Mail\QueuedMailable;

class SaleVoided extends QueuedMailable
{
    public $saleReference, $amount, $user, $reason;

    public function __construct($saleReference, $amount, $user, $reason)
    {
        $this->saleReference = $saleReference;
        $this->amount = $amount;
        $this->user = $user;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('🚨 URGENT: Sale Transaction Voided')
                    ->view('mail.pos.saleVoided');
    }
}