<?php

namespace App\Jobs;

use App\Mail\AppAdminPasswordMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendAppAdminPasswordJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public $password;
    public $email;
    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)->send(new AppAdminPasswordMail($this->password));
    }
}
