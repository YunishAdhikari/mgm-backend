<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <style>
        /* Reset styles for email clients */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; }
        
        /* Responsive styles */
        @media screen and (max-width: 600px) {
            .email-container { width: 100% !important; }
            .mobile-padding { padding: 25px 20px !important; }
            .mobile-full-width { width: 100% !important; display: block !important; }
            .mobile-center { text-align: center !important; }
        }
    </style>
</head>

<body style="margin:0; padding:0; background-color:#f4f7fb; font-family:Arial, Helvetica, sans-serif;">

    <!-- Preview Text -->
    <div style="display: none; font-size: 0; color: #f4f7fb; max-height: 0px; overflow: hidden;">
        Your reservation at MGM Muthu Glasgow River Hotel is confirmed.
    </div>

    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#f4f7fb; min-width:100%;" role="presentation">
        <tr>
            <td align="center" style="padding: 40px 15px;">
                
                <!-- Main Email Container -->
                <table border="0" cellpadding="0" cellspacing="0" width="600" class="email-container" style="background-color:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 10px 35px rgba(0,0,0,0.08);" role="presentation">
                    
                    <!-- HEADER -->
                    <tr>
                        <td style="
                            background: linear-gradient(135deg, #1583ff, #2d5bff);
                            padding: 40px 30px 35px 30px;
                            text-align: center;
                        ">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                <tr>
                                    <td align="center">
                                        <!-- Icon -->
                                        <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center;">
                                            <span style="font-size: 28px;">🍽️</span>
                                        </div>
                                        
                                        <h1 style="margin: 0; color: #ffffff; font-size: 26px; font-weight: 700;">
                                            Dining Reservation
                                        </h1>
                                        <p style="margin: 8px 0 0 0; color: rgba(255,255,255,0.85); font-size: 14px;">
                                            MGM Muthu Glasgow River Hotel
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- BODY -->
                    <tr>
                        <td class="mobile-padding" style="padding: 35px 30px;">
                            
                            <h2 style="margin: 0 0 10px 0; color: #111827; font-size: 22px; font-weight: 700;">
                                Booking Confirmed
                            </h2>

                            <p style="margin: 0 0 25px 0; color: #4b5563; font-size: 16px; line-height: 1.6;">
                                Dear <strong>{{ $booking->guest_name }}</strong>,
                            </p>

                            <p style="margin: 0 0 30px 0; color: #4b5563; font-size: 16px; line-height: 1.6;">
                                Thank you for your booking. We are pleased to confirm your reservation. We look forward to welcoming you.
                            </p>

                            <!-- BOOKING DETAILS CARD -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 14px; margin-bottom: 30px;" role="presentation">
                                <tr>
                                    <td style="padding: 25px;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                            
                                            <!-- Booking Type -->
                                            <tr>
                                                <td style="padding-bottom: 20px; border-bottom: 1px solid #e5e7eb;">
                                                    <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                                        Booking Type
                                                    </p>
                                                    @php
                                                        $isAfternoonTea = ($booking->booking_type == 'afternoon_tea');
                                                    @endphp
                                                    <span style="display: inline-block; padding: 6px 12px; border-radius: 20px; background-color: {{ $isAfternoonTea ? '#fce7f3' : '#eff6ff' }}; color: {{ $isAfternoonTea ? '#be185d' : '#1d4ed8' }}; font-size: 13px; font-weight: 700;">
                                                        {{ $isAfternoonTea ? 'Afternoon Tea' : 'Dinner' }}
                                                    </span>
                                                </td>
                                            </tr>

                                            <!-- Date & Time -->
                                            <tr>
                                                <td style="padding-top: 20px; padding-bottom: 20px; border-bottom: 1px solid #e5e7eb;">
                                                    <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                                        Date & Time
                                                    </p>
                                                    <p style="margin: 0; color: #111827; font-size: 16px; font-weight: 600;">
                                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('l, F j, Y') }}
                                                    </p>
                                                    <p style="margin: 5px 0 0 0; color: #1583ff; font-size: 15px; font-weight: 600;">
                                                        {{ \Carbon\Carbon::parse($booking->slot_start_time)->format('g:i A') }} 
                                                        - 
                                                        {{ \Carbon\Carbon::parse($booking->slot_end_time)->format('g:i A') }}
                                                    </p>
                                                </td>
                                            </tr>

                                            <!-- Guest Details -->
                                            <tr>
                                                <td style="padding-top: 20px;">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                                        <tr>
                                                            <!-- Guest Name -->
                                                            <td class="mobile-full-width" width="50%" style="padding-bottom: 20px; vertical-align: top;">
                                                                <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                                                    Guest Name
                                                                </p>
                                                                <p style="margin: 0; color: #111827; font-size: 15px; font-weight: 600;">
                                                                    {{ $booking->guest_name }}
                                                                </p>
                                                            </td>

                                                            <!-- Pax -->
                                                            <td class="mobile-full-width" width="50%" style="padding-bottom: 20px; vertical-align: top;">
                                                                <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                                                    Number of Guests
                                                                </p>
                                                                <p style="margin: 0; color: #111827; font-size: 15px; font-weight: 600;">
                                                                    {{ $booking->pax }} Guest{{ $booking->pax > 1 ? 's' : '' }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <!-- Table -->
                                                            <td class="mobile-full-width" width="50%" style="vertical-align: top;">
                                                                <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                                                    Table Assignment
                                                                </p>
                                                                <p style="margin: 0; color: #10b981; font-size: 15px; font-weight: 600;">
                                                                    {{ $booking->table->table_name ?? 'To Be Confirmed' }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- CLOSING MESSAGE -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                <tr>
                                    <td style="text-align: center; padding: 20px;">
                                        <p style="margin: 0; color: #374151; font-size: 16px; font-style: italic;">
                                            "We look forward to welcoming you to<br>MGM Muthu Glasgow River Hotel."
                                        </p>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 30px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 13px; font-weight: 600;">
                                Kind Regards,<br>
                                Reception Team
                            </p>
                            <p style="margin: 0; color: #9ca3af; font-size: 12px;">
                                MGM Muthu Glasgow River Hotel
                            </p>
                            <p style="margin: 15px 0 0 0; color: #9ca3af; font-size: 11px;">
                                &copy; {{ date('Y') }} MGM Operations. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
                <!-- End Main Container -->

            </td>
        </tr>
    </table>

</body>
</html>