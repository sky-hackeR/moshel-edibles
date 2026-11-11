<?php

namespace App\Mail\Unit;

use App\Mail\QueuedMailable;

class UnitModified extends QueuedMailable
{
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