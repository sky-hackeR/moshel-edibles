<?php

namespace App\Mail\Unit;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UnitModified extends Mailable
{
    use Queueable, SerializesModels;

    public $unit;
    public $action;
    public $user;

    public function __construct($unit, $action, $user)
    {
        $this->unit = $unit;
        $this->action = $action;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject("Unit Measurement $this->action: " . $this->unit->name)
                    ->view('mail.unit.unitModified');
    }
}