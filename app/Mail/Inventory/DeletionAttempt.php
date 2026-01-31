<?php
namespace App\Mail\Inventory;

use Illuminate\Mail\Mailable;

class DeletionAttempt extends Mailable {
    public $ingredient;
    public $user;
    public $reason;

    public function __construct($ingredient, $user, $reason) {
        $this->ingredient = $ingredient;
        $this->user = $user;
        $this->reason = $reason;
    }

    public function build() {
        return $this->subject('Action Blocked: Ingredient Deletion Attempt')
                    ->view('mail.inventory.deletionAttempt');
    }
}