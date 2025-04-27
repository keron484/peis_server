<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Queue\Queueable;

class SendOtpTokenJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public $otp;
    public $email;
    public function __construct($otp, $email)
    {
        $this->otp = $otp;
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)->send(new OtpMail($this->otp));
    }
}
