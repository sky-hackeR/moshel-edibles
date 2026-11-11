<?php
namespace App\Mail\Production;
use App\Mail\QueuedMailable;

class BatchCompleted extends QueuedMailable {
    public $production;
    public function __construct($production) { $this->production = $production; }

    public function build() {
        return $this->subject('Batch Produced: ' . $this->production->product->name)
                    ->view('mail.production.batchCompleted');
    }
}