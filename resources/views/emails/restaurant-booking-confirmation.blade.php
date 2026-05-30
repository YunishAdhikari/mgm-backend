<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

<h2>Restaurant Booking Confirmation</h2>

<p>Dear {{ $booking->guest_name }},</p>

<p>
    Thank you for your booking.
    We are pleased to confirm your reservation.
</p>

<table cellpadding="8">
    <tr>
        <td><strong>Booking Type</strong></td>
        <td>
            {{ $booking->booking_type == 'afternoon_tea'
                ? 'Afternoon Tea'
                : 'Dinner' }}
        </td>
    </tr>

    <tr>
        <td><strong>Date</strong></td>
        <td>{{ $booking->booking_date }}</td>
    </tr>

    <tr>
        <td><strong>Time</strong></td>
        <td>
            {{ \Carbon\Carbon::parse($booking->slot_start_time)->format('g:i A') }}
            -
            {{ \Carbon\Carbon::parse($booking->slot_end_time)->format('g:i A') }}
        </td>
    </tr>

    <tr>
        <td><strong>Guest Name</strong></td>
        <td>{{ $booking->guest_name }}</td>
    </tr>

    <tr>
        <td><strong>Pax</strong></td>
        <td>{{ $booking->pax }}</td>
    </tr>

    <tr>
        <td><strong>Table</strong></td>
        <td>{{ $booking->table->table_name ?? 'TBC' }}</td>
    </tr>
</table>

<p>
    We look forward to welcoming you to
    MGM Muthu Glasgow River Hotel.
</p>

<p>
    Kind Regards,<br>
    Reception Team<br>
    MGM Muthu Glasgow River Hotel
</p>

</body>
</html>