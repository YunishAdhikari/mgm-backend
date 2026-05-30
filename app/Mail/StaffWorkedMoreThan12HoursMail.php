<?php

namespace App\Mail;

use App\Models\AttendanceLog;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StaffWorkedMoreThan12HoursMail extends Mailable
{
    use Queueable, SerializesModels;

    public AttendanceLog $attendanceLog;

    public function __construct(AttendanceLog $attendanceLog)
    {
        $this->attendanceLog = $attendanceLog;
    }

    public function build()
    {
        return $this->subject('Staff Member Worked More Than 12 Hours')
            ->view('emails.staff-worked-more-than-12-hours');
    }
}