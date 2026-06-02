<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Long Shift Alert</title>
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
        ⚠️ Alert: LongShift Alert for {{ $attendanceLog->user->name ?? 'Employee' }} - Exceeded 12 hours.
    </div>

    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#f4f7fb; min-width:100%;" role="presentation">
        <tr>
            <td align="center" style="padding: 40px 15px;">
                
                <!-- Main Email Container -->
                <table border="0" cellpadding="0" cellspacing="0" width="600" class="email-container" style="background-color:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 10px 35px rgba(0,0,0,0.08);" role="presentation">
                    
                    <!-- HEADER (Warning Orange Gradient) -->
                    <tr>
                        <td style="
                            background: linear-gradient(135deg, #f59e0b, #ea580c);
                            padding: 40px 30px 35px 30px;
                            text-align: center;
                        ">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                <tr>
                                    <td align="center">
                                        <!-- Icon -->
                                        <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center;">
                                            <span style="font-size: 28px;">⚠️</span>
                                        </div>
                                        
                                        <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 700;">
                                            Long Shift Alert
                                        </h1>
                                        <p style="margin: 8px 0 0 0; color: rgba(255,255,255,0.85); font-size: 14px;">
                                            MGM Ops • HR Monitoring System
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
                                ⚠️ Excessive Work Hours Detected
                            </h2>

                            <p style="margin: 0 0 25px 0; color: #4b5563; font-size: 16px; line-height: 1.6;">
                                This is an automated notification. An employee has exceeded the standard 12-hour work shift limit.
                            </p>

                            <!-- ALERT CARD -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #fffbeb; border: 1px solid #fcd34d; border-radius: 14px; margin-bottom: 30px;" role="presentation">
                                <tr>
                                    <td style="padding: 25px;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                            
                                            <!-- Employee Name -->
                                            <tr>
                                                <td style="padding-bottom: 20px; border-bottom: 1px solid #fcd34d;">
                                                    <p style="margin: 0 0 8px 0; color: #92400e; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                                        Employee Name
                                                    </p>
                                                    <p style="margin: 0; color: #111827; font-size: 18px; font-weight: 700;">
                                                        {{ optional($attendanceLog->user)->name ?? 'Unknown Employee' }}
                                                    </p>
                                                </td>
                                            </tr>

                                            <!-- Time Details -->
                                            <tr>
                                                <td style="padding-top: 20px; padding-bottom: 20px; border-bottom: 1px solid #fcd34d;">
                                                    <p style="margin: 0 0 8px 0; color: #92400e; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                                        Clock In Time
                                                    </p>
                                                    <p style="margin: 0; color: #111827; font-size: 16px; font-weight: 600;">
                                                        {{ \Carbon\Carbon::parse($attendanceLog->clock_in_at)->format('l, F j, Y • g:i A') }}
                                                    </p>
                                                </td>
                                            </tr>

                                            <!-- Duration -->
                                            <tr>
                                                <td style="padding-top: 20px;">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                                        <tr>
                                                            <td class="mobile-full-width" style="padding-bottom: 10px;">
                                                                <p style="margin: 0 0 8px 0; color: #92400e; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                                                    Current Duration
                                                                </p>
                                                                @php
                                                                    $hours = now()->diffInHours($attendanceLog->clock_in_at);
                                                                    $bgColor = $hours >= 12 ? '#fee2e2' : '#fef3c7';
                                                                    $textColor = $hours >= 12 ? '#991b1b' : '#92400e';
                                                                @endphp
                                                                <span style="display: inline-block; padding: 8px 16px; border-radius: 8px; background-color: {{ $bgColor }}; color: {{ $textColor }}; font-size: 20px; font-weight: 700;">
                                                                    {{ $hours }} Hours
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- ACTION MESSAGE -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                <tr>
                                    <td style="background-color: #f3f4f6; padding: 20px; border-radius: 10px; text-align: center;">
                                        <p style="margin: 0; color: #4b5563; font-size: 14px;">
                                            Please review this shift. Ensure the employee is aware of overtime policies and is managing rest breaks appropriately.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 30px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 13px;">
                                MGM Ops • HR Monitoring System
                            </p>
                            <p style="margin: 0; color: #9ca3af; font-size: 12px;">
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