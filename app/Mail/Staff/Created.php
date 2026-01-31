<?php
namespace App\Mail\Staff;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Created extends Mailable {
    use Queueable, SerializesModels;
    public $staff;
    public $password;

    public function __construct($staff, $password) {
        $this->staff = $staff;
        $this->password = $password;
    }

    public function build() {
        return $this->subject('Staff Account Created')->view('mail.staff.created');
    }
}