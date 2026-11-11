<?php
namespace App\Mail\Finance;
use App\Mail\QueuedMailable;

class DailyPerformance extends QueuedMailable {
    public $stats;

    public function __construct($stats) { $this->stats = $stats; }

    public function build() {
        return $this->subject('Daily Business Report: '.date('d M Y'))->view('mail.finance.dailyPerformance');
    }
}