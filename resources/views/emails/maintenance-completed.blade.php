<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Completed</title>
    <style>
        /* Reset styles for email clients */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; }
        
        /* Responsive styles */
        @media screen and (max-width: 650px) {
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
        Maintenance completed: {{ $job->title }} - Review the completed job details in MGM Ops.
    </div>

    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#f4f7fb; min-width:100%;" role="presentation">
        <tr>
            <td align="center" style="padding: 40px 15px;">
                
                <!-- Main Email Container -->
                <table border="0" cellpadding="0" cellspacing="0" width="650" class="email-container" style="background-color:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 10px 35px rgba(0,0,0,0.08);" role="presentation">
                    
                    <!-- HEADER -->
                    <tr>
                        <td style="
                            background: linear-gradient(135deg, #10b981, #1583ff);
                            padding: 40px 30px 35px 30px;
                            text-align: center;
                        ">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                <tr>
                                    <td align="center">
                                        <!-- Icon -->
                                        <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center;">
                                            <span style="font-size: 28px;">✅</span>
                                        </div>
                                        
                                        <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700;">
                                            MGM Ops
                                        </h1>
                                        <p style="margin: 8px 0 0 0; color: rgba(255,255,255,0.85); font-size: 14px;">
                                            Hotel Internal Management System
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- BODY -->
                    <tr>
                        <td class="mobile-padding" style="padding: 35px 30px;">
                            
                            <h2 style="margin: 0 0 10px 0; color: #111827; font-size: 24px; font-weight: 700;">
                                ✅ Maintenance Job Completed
                            </h2>

                            <p style="margin: 0 0 30px 0; color: #4b5563; font-size: 16px; line-height: 1.6;">
                                A maintenance task has successfully been completed. The details are shown below for your records.
                            </p>

                            <!-- JOB DETAILS CARD -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 14px; margin-bottom: 30px;" role="presentation">
                                <tr>
                                    <td style="padding: 25px;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                            
                                            <!-- Title -->
                                            <tr>
                                                <td style="padding-bottom: 20px; border-bottom: 1px solid #e5e7eb;">
                                                    <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                                        Job Title
                                                    </p>
                                                    <p style="margin: 0; color: #111827; font-size: 18px; font-weight: 700;">
                                                        {{ $job->title }}
                                                    </p>
                                                </td>
                                            </tr>

                                            <!-- Description -->
                                            <tr>
                                                <td style="padding-top: 20px; padding-bottom: 20px; border-bottom: 1px solid #e5e7eb;">
                                                    <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                                        Description
                                                    </p>
                                                    <p style="margin: 0; color: #374151; font-size: 15px; line-height: 1.6;">
                                                        {{ $job->description }}
                                                    </p>
                                                </td>
                                            </tr>

                                            <!-- Image (If exists) -->
                                            @if($job->image)
                                            <tr>
                                                <td style="padding-top: 20px; padding-bottom: 20px; border-bottom: 1px solid #e5e7eb;">
                                                    <p style="margin: 0 0 12px 0; color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                                        Attached Image
                                                    </p>
                                                    <img src="{{ asset('uploads/maintenance/' . $job->image) }}" alt="Maintenance Image" style="width: 100%; max-width: 100%; border-radius: 10px; border: 1px solid #e5e7eb; display: block;">
                                                </td>
                                            </tr>
                                            @endif

                                            <!-- Details Grid -->
                                            <tr>
                                                <td style="padding-top: 20px;">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                                        <tr>
                                                            <!-- Priority -->
                                                            <td class="mobile-full-width" width="50%" style="padding-bottom: 20px; vertical-align: top;">
                                                                <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                                                    Priority
                                                                </p>
                                                                @php
                                                                    $priorityColorBg = '#fee2e2';
                                                                    $priorityColorText = '#991b1b';
                                                                    $jobPriority = strtolower($job->priority ?? 'low');
                                                                    if($jobPriority == 'high') { $priorityColorBg = '#fee2e2'; $priorityColorText = '#991b1b'; }
                                                                    elseif($jobPriority == 'medium') { $priorityColorBg = '#fef3c7'; $priorityColorText = '#92400e'; }
                                                                    else { $priorityColorBg = '#d1fae5'; $priorityColorText = '#065f46'; }
                                                                @endphp
                                                                <span style="display: inline-block; padding: 6px 12px; border-radius: 20px; background-color: {{ $priorityColorBg }}; color: {{ $priorityColorText }}; font-size: 13px; font-weight: 700;">
                                                                    {{ ucfirst($job->priority) }}
                                                                </span>
                                                            </td>

                                                            <!-- Status -->
                                                            <td class="mobile-full-width" width="50%" style="padding-bottom: 20px; vertical-align: top;">
                                                                <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                                                    Status
                                                                </p>
                                                                <span style="display: inline-block; padding: 6px 12px; border-radius: 20px; background-color: #dcfce7; color: #166534; font-size: 13px; font-weight: 700;">
                                                                    {{ ucfirst($job->status) ?? 'Completed' }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <!-- Location -->
                                                            <td class="mobile-full-width" width="50%" style="vertical-align: top;">
                                                                <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                                                    Location
                                                                </p>
                                                                <p style="margin: 0; color: #111827; font-size: 15px; font-weight: 600;">
                                                                    {{ $job->location ?? 'N/A' }}
                                                                </p>
                                                            </td>

                                                            <!-- Room Number -->
                                                            <td class="mobile-full-width" width="50%" style="vertical-align: top;">
                                                                <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                                                    Room / Area
                                                                </p>
                                                                <p style="margin: 0; color: #111827; font-size: 15px; font-weight: 600;">
                                                                    {{ $job->room_number ?? 'N/A' }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <!-- Completed Date -->
                                                        @if(!empty($job->completed_date))
                                                        <tr>
                                                            <td colspan="2" style="padding-top: 20px;">
                                                                <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                                                    Completed On
                                                                </p>
                                                                <p style="margin: 0; color: #10b981; font-size: 15px; font-weight: 600;">
                                                                    {{ \Carbon\Carbon::parse($job->completed_date)->format('F j, Y, g:i A') }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                    </table>
                                                </td>
                                            </tr>

                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- FOOTER NOTE -->
                            <p style="margin: 0; font-size: 13px; color: #6b7280; text-align: center;">
                                This job has been marked as complete in the system.
                            </p>

                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 30px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 13px;">
                                MGM Ops © {{ date('Y') }} • Hotel Internal Operations Platform
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