<?php
namespace App\Mail\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountCreated extends Mailable {
    use Queueable, SerializesModels;
    public $admin;
    public $password;

    public function __construct($admin, $password) {
        $this->admin = $admin;
        $this->password = $password;
    }

    public function build() {
        return $this->subject('Welcome to the Admin Team')->view('mail.admin.accountCreated');
    }
}