@php
    $restaurant = $booking->restaurant ?? null;
    $hotel = $restaurant?->hotel ?? null;

    $hotelName = $hotel?->name ?? 'MGM Hotel';
    $restaurantName = $restaurant?->name ?? 'Restaurant';

    $isAfternoonTea = $booking->booking_type === 'afternoon_tea';
    $bookingTypeLabel = $isAfternoonTea ? 'Afternoon Tea' : 'Dinner';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmation</title>
</head>

<body style="margin:0; padding:0; background-color:#f4f7fb; font-family:Arial, Helvetica, sans-serif;">

<div style="display:none; font-size:0; color:#f4f7fb; max-height:0; overflow:hidden;">
    Your reservation at {{ $restaurantName }} is confirmed.
</div>

<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f7fb;">
    <tr>
        <td align="center" style="padding:40px 15px;">

            <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 10px 35px rgba(0,0,0,.08);">

                <tr>
                    <td style="background:linear-gradient(135deg,#1583ff,#2d5bff); padding:40px 30px; text-align:center;">
                        <div style="font-size:38px; margin-bottom:14px;">🍽️</div>

                        <h1 style="margin:0; color:#ffffff; font-size:26px;">
                            Dining Reservation Confirmed
                        </h1>

                        <p style="margin:8px 0 0; color:rgba(255,255,255,.85); font-size:14px;">
                            {{ $restaurantName }} • {{ $hotelName }}
                        </p>
                    </td>
                </tr>

                <tr>
                    <td style="padding:35px 30px;">

                        <h2 style="margin:0 0 10px; color:#111827; font-size:22px;">
                            Hello {{ $booking->guest_name }},
                        </h2>

                        <p style="margin:0 0 28px; color:#4b5563; font-size:16px; line-height:1.6;">
                            Thank you for your booking. Your reservation has been confirmed.
                        </p>

                        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:14px;">
                            <tr>
                                <td style="padding:25px;">

                                    <p style="margin:0 0 8px; color:#6b7280; font-size:12px; text-transform:uppercase; font-weight:700;">
                                        Restaurant
                                    </p>
                                    <p style="margin:0 0 20px; color:#111827; font-size:16px; font-weight:700;">
                                        {{ $restaurantName }}
                                    </p>

                                    <p style="margin:0 0 8px; color:#6b7280; font-size:12px; text-transform:uppercase; font-weight:700;">
                                        Booking Type
                                    </p>
                                    <span style="display:inline-block; padding:6px 12px; border-radius:20px; background:{{ $isAfternoonTea ? '#fce7f3' : '#eff6ff' }}; color:{{ $isAfternoonTea ? '#be185d' : '#1d4ed8' }}; font-size:13px; font-weight:700; margin-bottom:20px;">
                                        {{ $bookingTypeLabel }}
                                    </span>

                                    <p style="margin:20px 0 8px; color:#6b7280; font-size:12px; text-transform:uppercase; font-weight:700;">
                                        Date & Time
                                    </p>
                                    <p style="margin:0; color:#111827; font-size:16px; font-weight:700;">
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('l, F j, Y') }}
                                    </p>
                                    <p style="margin:5px 0 20px; color:#1583ff; font-size:15px; font-weight:700;">
                                        {{ \Carbon\Carbon::parse($booking->slot_start_time)->format('g:i A') }}
                                        -
                                        {{ \Carbon\Carbon::parse($booking->slot_end_time)->format('g:i A') }}
                                    </p>

                                    <table width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td width="50%" style="padding-bottom:18px;">
                                                <p style="margin:0 0 8px; color:#6b7280; font-size:12px; text-transform:uppercase; font-weight:700;">
                                                    Guests
                                                </p>
                                                <p style="margin:0; color:#111827; font-size:15px; font-weight:700;">
                                                    {{ $booking->pax }} Guest{{ $booking->pax > 1 ? 's' : '' }}
                                                </p>
                                            </td>

                                            <td width="50%" style="padding-bottom:18px;">
                                                <p style="margin:0 0 8px; color:#6b7280; font-size:12px; text-transform:uppercase; font-weight:700;">
                                                    Table
                                                </p>
                                                <p style="margin:0; color:#10b981; font-size:15px; font-weight:700;">
                                                    {{ $booking->table->table_name ?? 'To Be Confirmed' }}
                                                </p>
                                            </td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>
                        </table>

                        <p style="margin:30px 0 0; color:#374151; font-size:16px; text-align:center; font-style:italic;">
                            We look forward to welcoming you to<br>
                            {{ $restaurantName }}.
                        </p>

                    </td>
                </tr>

                <tr>
                    <td style="background:#f9fafb; padding:30px; text-align:center; border-top:1px solid #e5e7eb;">
                        <p style="margin:0 0 8px; color:#6b7280; font-size:13px; font-weight:700;">
                            Kind Regards,<br>
                            {{ $hotelName }} Reception Team
                        </p>

                        <p style="margin:0; color:#9ca3af; font-size:12px;">
                            {{ $hotelName }}
                        </p>

                        <p style="margin:15px 0 0; color:#9ca3af; font-size:11px;">
                            &copy; {{ date('Y') }} MGM Operations. All rights reserved.
                        </p>
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>