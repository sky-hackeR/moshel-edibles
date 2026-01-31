<?php
namespace App\Mail\Finance;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyPerformance extends Mailable {
    use Queueable, SerializesModels;
    public $stats;

    public function __construct($stats) { $this->stats = $stats; }

    public function build() {
        return $this->subject('Daily Business Report: '.date('d M Y'))->view('mail.finance.dailyPerformance');
    }
}