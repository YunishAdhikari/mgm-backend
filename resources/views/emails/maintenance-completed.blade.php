<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Completed</title>
</head>

<body style="margin:0; padding:0; background:#f4f7fb; font-family:Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 15px;">
        <tr>
            <td align="center">

                <table width="650" cellpadding="0" cellspacing="0"
                    style="
                        background:#ffffff;
                        border-radius:18px;
                        overflow:hidden;
                        box-shadow:0 10px 35px rgba(0,0,0,0.08);
                    ">

                    <!-- HEADER -->
                    <tr>
                        <td style="
                            background:linear-gradient(135deg,#10b981,#1583ff);
                            padding:35px 30px;
                            text-align:center;
                            color:white;
                        ">

                            <h1 style="
                                margin:0;
                                font-size:32px;
                            ">
                                MGM Ops
                            </h1>

                            <p style="
                                margin-top:10px;
                                font-size:15px;
                                opacity:0.9;
                            ">
                                Hotel Internal Management System
                            </p>

                        </td>
                    </tr>

                    <!-- BODY -->
                    <tr>
                        <td style="padding:35px 30px;">

                            <h2 style="
                                margin-top:0;
                                color:#111827;
                                font-size:28px;
                            ">
                                ✅ Maintenance Job Completed
                            </h2>

                            <p style="
                                color:#4b5563;
                                font-size:16px;
                                line-height:1.7;
                            ">
                                A maintenance task has successfully been completed and updated in the MGM Ops system.
                            </p>

                            <!-- JOB DETAILS CARD -->
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="
                                    margin-top:30px;
                                    border-radius:14px;
                                    overflow:hidden;
                                    border:1px solid #e5e7eb;
                                ">

                                <tr>
                                    <td style="
                                        background:#f9fafb;
                                        padding:18px 22px;
                                        border-bottom:1px solid #e5e7eb;
                                    ">

                                        <strong style="color:#111827;">
                                            Completed Maintenance Details
                                        </strong>

                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:22px;">

                                        <p style="margin:0 0 18px;">
                                            <strong>Title:</strong><br>

                                            <span style="color:#374151;">
                                                {{ $job->title }}
                                            </span>
                                        </p>

                                        <p style="margin:0 0 18px;">
                                            <strong>Description:</strong><br>

                                            <span style="color:#374151;">
                                                {{ $job->description }}
                                            </span>
                                        </p>
                                        @if($job->image)

    <div style="margin-top:25px;">

        <strong>Attached Image</strong><br><br>

        <img
            src="{{ asset('uploads/maintenance/' . $job->image) }}"
            alt="Maintenance Image"
            style="
                width:100%;
                max-width:500px;
                border-radius:14px;
                border:1px solid #e5e7eb;
            "
        >

    </div>

@endif

                                        <table width="100%" cellpadding="0" cellspacing="0">

                                            <tr>

                                                <td width="50%" style="padding-bottom:16px;">

                                                    <strong>Priority</strong><br>

                                                    <span style="
                                                        display:inline-block;
                                                        margin-top:6px;
                                                        padding:8px 14px;
                                                        border-radius:30px;
                                                        background:#fee2e2;
                                                        color:#991b1b;
                                                        font-size:13px;
                                                        font-weight:bold;
                                                    ">

                                                        {{ ucfirst($job->priority) }}

                                                    </span>

                                                </td>

                                                <td width="50%" style="padding-bottom:16px;">

                                                    <strong>Status</strong><br>

                                                    <span style="
                                                        display:inline-block;
                                                        margin-top:6px;
                                                        padding:8px 14px;
                                                        border-radius:30px;
                                                        background:#dcfce7;
                                                        color:#166534;
                                                        font-size:13px;
                                                        font-weight:bold;
                                                    ">

                                                        {{ ucfirst($job->status) }}

                                                    </span>

                                                </td>

                                            </tr>

                                            <tr>

                                                <td style="padding-bottom:16px;">

                                                    <strong>Location</strong><br>

                                                    <span style="color:#374151;">
                                                        {{ $job->location ?? 'N/A' }}
                                                    </span>

                                                </td>

                                                <td style="padding-bottom:16px;">

                                                    <strong>Room Number</strong><br>

                                                    <span style="color:#374151;">
                                                        {{ $job->room_number ?? 'N/A' }}
                                                    </span>

                                                </td>

                                            </tr>

                                            <tr>

                                                <td colspan="2">

                                                    <strong>Completed Date</strong><br>

                                                    <span style="color:#374151;">
                                                        {{ $job->completed_date }}
                                                    </span>

                                                </td>

                                            </tr>

                                        </table>

                                    </td>
                                </tr>

                            </table>

                            <!-- BUTTON -->
                            {{-- <div style="text-align:center; margin-top:35px;">

                                <a href="{{ url('/admin/maintenance') }}"
                                    style="
                                        display:inline-block;
                                        padding:14px 30px;
                                        border-radius:12px;
                                        background:linear-gradient(135deg,#10b981,#1583ff);
                                        color:white;
                                        text-decoration:none;
                                        font-weight:bold;
                                        font-size:15px;
                                    ">

                                    View Maintenance Dashboard

                                </a>

                            </div> --}}

                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="
                            background:#f9fafb;
                            padding:22px;
                            text-align:center;
                            border-top:1px solid #e5e7eb;
                        ">

                            <p style="
                                margin:0;
                                color:#6b7280;
                                font-size:14px;
                            ">
                                MGM Ops © {{ date('Y') }} <br>
                                Hotel Internal Operations Platform
                            </p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>