<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your MGM Ops Account</title>
    <style>
        /* Reset styles for email clients */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; }
        
        /* Responsive styles */
        @media screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
            }
            .email-container-padding {
                padding: 20px !important;
            }
            .mobile-content {
                width: 100% !important;
                display: block !important;
            }
            .mobile-padding {
                padding: 10px 0 !important;
            }
            .mobile-center {
                text-align: center !important;
            }
        }
    </style>
</head>

<body style="
    margin:0;
    padding:0;
    background-color:#f4f7fb;
    font-family:Arial,Helvetica,sans-serif;
    line-height:1.6;
">

    <!-- Preview Text (Hidden in email client) -->
    <div style="display: none; font-size: 0; color: #f4f7fb; max-height: 0px; overflow: hidden;">
        Your MGM Ops employee account has been created successfully. Login to access your dashboard.
    </div>

    <!-- Centered Layout Table -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#f4f7fb; min-width:100%;" role="presentation">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                
                <!-- Main Email Container -->
                <table border="0" cellpadding="0" cellspacing="0" width="600" class="email-container" style="background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.05);" role="presentation">
                    
                    <!-- Header with Gradient -->
                    <tr>
                        <td style="padding: 40px 40px 30px 40px; text-align: center; background: linear-gradient(135deg, #1583ff, #2d5bff);">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                <tr>
                                    <td align="center">
                                        <!-- Logo Icon Placeholder -->
                                        <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; border: 2px solid rgba(255,255,255,0.3);">
                                            <span style="color: white; font-size: 24px; font-weight: bold;">MGM</span>
                                        </div>
                                        
                                        <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700; letter-spacing: -0.5px;">MGM Ops</h1>
                                        <p style="margin: 8px 0 0 0; color: rgba(255,255,255,0.85); font-size: 14px;">Hotel Internal Management Platform</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Main Content -->
                    <tr>
                        <td class="email-container-padding" style="padding: 40px 40px 30px 40px;">
                            
                            <h2 style="margin: 0 0 15px 0; color: #1a1a1a; font-size: 22px; font-weight: 700;">
                                Welcome, {{ $user->name }}!
                            </h2>

                            <p style="margin: 0 0 25px 0; color: #555555; font-size: 16px;">
                                Your employee account has been successfully created. You now have access to the MGM Ops internal platform.
                            </p>

                            <!-- Account Details Card -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8f9fc; border: 1px solid #e5e7eb; border-radius: 12px; margin-bottom: 30px;" role="presentation">
                                <tr>
                                    <td style="padding: 25px;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                            
                                            <!-- Email Row -->
                                            <tr>
                                                <td style="padding-bottom: 20px; border-bottom: 1px solid #e5e7eb;">
                                                    <p style="margin: 0 0 5px 0; color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Email Address</p>
                                                    <p style="margin: 0; color: #111827; font-size: 16px; font-weight: 600;">{{ $user->email }}</p>
                                                </td>
                                            </tr>

                                            <!-- Password Row -->
                                            <tr>
                                                <td style="padding-top: 20px; padding-bottom: 20px; border-bottom: 1px solid #e5e7eb;">
                                                    <p style="margin: 0 0 5px 0; color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Temporary Password</p>
                                                    <p style="margin: 0; color: #111827; font-size: 16px; font-weight: 600;">{{ $plainPassword }}</p>
                                                </td>
                                            </tr>

                                            <!-- Details Row -->
                                            <tr>
                                                <td style="padding-top: 20px;">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                                        <tr>
                                                            <td style="padding-bottom: 10px;">
                                                                <p style="margin: 0 0 5px 0; color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Role</p>
                                                                <p style="margin: 0; color: #1583ff; font-size: 15px; font-weight: 600;">{{ optional($user->role)->name ?? 'Employee' }}</p>
                                                            </td>
                                                            <td>
                                                                <p style="margin: 0 0 5px 0; color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Department</p>
                                                                <p style="margin: 0; color: #1583ff; font-size: 15px; font-weight: 600;">{{ optional($user->department)->name ?? 'General' }}</p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                <tr>
                                    <td align="center" class="mobile-center">
                                        <table border="0" cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>
                                                <td align="center" style="border-radius: 8px; background: linear-gradient(135deg, #1583ff, #2d5bff);">
                                                    <a href="{{ route('login') }}" target="_blank" style="font-size: 16px; font-weight: bold; color: #ffffff; text-decoration: none; padding: 16px 32px; border-radius: 8px; display: inline-block;">
                                                        Login to Dashboard
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fc; padding: 30px 40px; border-top: 1px solid #e5e7eb;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                <tr>
                                    <td align="center">
                                        <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 13px;">
                                            Having trouble logging in? Contact your system administrator.
                                        </p>
                                        <p style="margin: 0; color: #9ca3af; font-size: 12px;">
                                            &copy; {{ date('Y') }} MGM Operations. All rights reserved.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
                <!-- End Main Container -->

            </td>
        </tr>
    </table>

</body>
</html>