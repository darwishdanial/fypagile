<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\NotifyPanelAssignmentComplete;
use Mail;

class EmailPanelAssignmentCompleteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $status;
    public $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $status, $message = null)
    {
        $this->email = $email;
        $this->status = $status;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)->send(new NotifyPanelAssignmentComplete($this->status, $this->message));
    }
}
