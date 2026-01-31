<?php

namespace App\Mail\POS;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SaleVoided extends Mailable
{
    use Queueable, SerializesModels;

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