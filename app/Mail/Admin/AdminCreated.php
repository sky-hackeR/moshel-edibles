<?php
namespace App\Mail\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminCreated extends Mailable {
    use Queueable, SerializesModels;
    public $newAdmin;
    public $creator;

    public function __construct($newAdmin, $creator) {
        $this->newAdmin = $newAdmin;
        $this->creator = $creator;
    }

    public function build() {
        return $this->subject('Security Alert: New Admin Added')->view('mail.admin.adminCreated');
    }
}