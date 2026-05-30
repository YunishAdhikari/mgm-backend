<h2>Long Shift Alert</h2>

<p>
    {{ $attendanceLog->user->name }}
    has worked more than 12 hours.
</p>

<p>
    Clock In:
    {{ $attendanceLog->clock_in_at }}
</p>

<p>
    Current Duration:
    {{ now()->diffInHours($attendanceLog->clock_in_at) }}
    hours
</p>