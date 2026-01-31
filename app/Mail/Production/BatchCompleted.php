<?php
namespace App\Mail\Production;
use Illuminate\Mail\Mailable;

class BatchCompleted extends Mailable {
    public $production;
    public function __construct($production) { $this->production = $production; }

    public function build() {
        return $this->subject('Batch Produced: ' . $this->production->product->name)
                    ->view('mail.production.batchCompleted');
    }
}