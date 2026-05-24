<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>MGM Ops Account</title>
</head>

<body style="
    background:#f4f7fb;
    font-family:Arial,sans-serif;
    padding:40px;
">

    <div style="
        max-width:650px;
        background:white;
        margin:auto;
        border-radius:18px;
        overflow:hidden;
        box-shadow:0 10px 35px rgba(0,0,0,0.08);
    ">

        <div style="
            background:linear-gradient(135deg,#1583ff,#ff15c4);
            padding:35px;
            color:white;
            text-align:center;
        ">

            <h1>MGM Ops</h1>

            <p>Hotel Internal Management Platform</p>

        </div>

        <div style="padding:35px;">

            <h2>Welcome {{ $user->name }}</h2>

            <p>
                Your employee account has been created successfully.
            </p>

            <div style="
                background:#f9fafb;
                border:1px solid #e5e7eb;
                border-radius:14px;
                padding:25px;
                margin-top:25px;
            ">

                <p>
                    <strong>Email:</strong><br>
                    {{ $user->email }}
                </p>

                <p>
                    <strong>Password:</strong><br>
                    {{ $plainPassword }}
                </p>

                <p>
                    <strong>Role:</strong><br>
                    {{ $user->role->name ?? 'N/A' }}
                </p>

                <p>
                    <strong>Department:</strong><br>
                    {{ $user->department->name ?? 'N/A' }}
                </p>

            </div>

            <div style="margin-top:30px; text-align:center;">

                <a href="mgmops://login"
                    style="
                        background:linear-gradient(135deg,#1583ff,#ff15c4);
                        color:white;
                        padding:14px 28px;
                        border-radius:12px;
                        text-decoration:none;
                        font-weight:bold;
                    ">

                    Login to MGM Ops

                </a>

            </div>

        </div>

    </div>

</body>
</html>