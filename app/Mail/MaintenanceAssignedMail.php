<?php

namespace App\Mail;

use App\Models\MaintenanceJob;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MaintenanceAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $job;

    public function __construct(MaintenanceJob $job)
    {
        $this->job = $job;
    }

    public function build()
    {
        return $this->subject('New Maintenance Job Assigned')
                    ->view('emails.maintenance-assigned');
    }
}