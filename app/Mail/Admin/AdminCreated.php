<?php
namespace App\Mail\Admin;
use App\Mail\QueuedMailable;

class AdminCreated extends QueuedMailable {
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