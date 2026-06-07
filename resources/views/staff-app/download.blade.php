<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MGM Ops Staff App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(239, 68, 68, 0.18), transparent 34%),
                radial-gradient(circle at bottom right, rgba(20, 184, 166, 0.12), transparent 34%),
                #09090b;
            color: #fafafa;
            font-family: Inter, Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 22px;
        }

        .page {
            width: 100%;
            max-width: 980px;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 22px;
            align-items: stretch;
        }

        .hero-card,
        .info-card {
            background: rgba(24, 24, 27, 0.92);
            border: 1px solid #3f3f46;
            border-radius: 28px;
            box-shadow: 0 28px 80px rgba(0,0,0,.35);
            overflow: hidden;
        }

        .hero-card {
            padding: 34px;
            position: relative;
        }

        .hero-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 85% 10%, rgba(239,68,68,.18), transparent 32%);
            pointer-events: none;
        }

        .content {
            position: relative;
            z-index: 1;
        }

        .logo {
            width: 78px;
            height: 78px;
            border-radius: 24px;
            background: linear-gradient(135deg, #ef4444, #7f1d1d);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 22px;
            font-size: 34px;
            font-weight: 900;
            box-shadow: 0 18px 35px rgba(239,68,68,.32);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(34,197,94,.12);
            color: #4ade80;
            border: 1px solid rgba(34,197,94,.35);
            padding: 8px 13px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 900;
            margin-bottom: 16px;
        }

        .badge-dot {
            width: 7px;
            height: 7px;
            background: #22c55e;
            border-radius: 50%;
            display: inline-block;
        }

        h1 {
            margin: 0;
            font-size: 42px;
            line-height: 1.05;
            font-weight: 900;
            letter-spacing: -1px;
        }

        .subtitle {
            color: #a1a1aa;
            line-height: 1.7;
            margin: 18px 0 0;
            font-size: 16px;
            max-width: 560px;
        }

        .version-box {
            margin-top: 26px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .version-item {
            background: #27272a;
            border: 1px solid #3f3f46;
            border-radius: 18px;
            padding: 15px;
        }

        .version-item span {
            display: block;
            color: #71717a;
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            margin-bottom: 7px;
        }

        .version-item strong {
            font-size: 18px;
            color: #fafafa;
        }

        .download-btn {
            margin-top: 24px;
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: linear-gradient(135deg, #ef4444, #b91c1c);
            color: white;
            padding: 16px 20px;
            border-radius: 18px;
            text-decoration: none;
            font-weight: 900;
            font-size: 16px;
            box-shadow: 0 18px 40px rgba(239,68,68,.28);
        }

        .download-btn:hover {
            color: white;
            transform: translateY(-1px);
        }

        .empty-box {
            margin-top: 24px;
            background: rgba(245,158,11,.12);
            border: 1px solid rgba(245,158,11,.35);
            color: #fbbf24;
            padding: 16px;
            border-radius: 18px;
            line-height: 1.6;
        }

        .release-notes {
            margin-top: 18px;
            background: rgba(39,39,42,.88);
            border: 1px solid #3f3f46;
            border-radius: 18px;
            padding: 16px;
        }

        .release-notes h3 {
            margin: 0 0 8px;
            color: #fafafa;
            font-size: 15px;
        }

        .release-notes p {
            margin: 0;
            color: #d4d4d8;
            line-height: 1.6;
            white-space: pre-line;
        }

        .info-card {
            padding: 28px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .info-title {
            font-size: 22px;
            font-weight: 900;
            margin-bottom: 16px;
        }

        .steps {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .step {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            background: #27272a;
            border: 1px solid #3f3f46;
            border-radius: 18px;
            padding: 15px;
        }

        .step-number {
            width: 30px;
            height: 30px;
            border-radius: 10px;
            background: rgba(239,68,68,.14);
            color: #f87171;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            flex-shrink: 0;
        }

        .step strong {
            display: block;
            color: #fafafa;
            margin-bottom: 4px;
        }

        .step p {
            margin: 0;
            color: #a1a1aa;
            line-height: 1.45;
            font-size: 13px;
        }

        .security-note {
            margin-top: 18px;
            background: rgba(20,184,166,.10);
            border: 1px solid rgba(20,184,166,.32);
            color: #5eead4;
            border-radius: 18px;
            padding: 16px;
            line-height: 1.5;
            font-size: 13px;
        }

        .footer {
            margin-top: 20px;
            color: #71717a;
            font-size: 12px;
            text-align: center;
        }

        @media(max-width: 850px) {
            body {
                align-items: flex-start;
            }

            .page {
                grid-template-columns: 1fr;
            }

            h1 {
                font-size: 34px;
            }

            .version-box {
                grid-template-columns: 1fr;
            }
        }

        @media(max-width: 480px) {
            body {
                padding: 14px;
            }

            .hero-card,
            .info-card {
                border-radius: 22px;
                padding: 22px;
            }

            h1 {
                font-size: 30px;
            }
        }
    </style>
</head>

<body>

<div class="page">

    <div class="hero-card">
        <div class="content">
            <div class="logo">M</div>

            <div class="badge">
                <span class="badge-dot"></span>
                Internal Staff App
            </div>

            <h1>MGM Ops<br>Android App</h1>

            <p class="subtitle">
                Download the latest MGM Ops staff app for Android.
                This app is only for authorized hotel employees.
            </p>

            @if($latestVersion)
                <div class="version-box">
                    <div class="version-item">
                        <span>Version</span>
                        <strong>{{ $latestVersion->version_name }}</strong>
                    </div>

                    <div class="version-item">
                        <span>Build</span>
                        <strong>{{ $latestVersion->version_code }}</strong>
                    </div>

                    <div class="version-item">
                        <span>Released</span>
                        <strong>{{ $latestVersion->created_at->format('d M Y') }}</strong>
                    </div>
                </div>

                @if($latestVersion->release_notes)
                    <div class="release-notes">
                        <h3>What’s New</h3>
                        <p>{{ $latestVersion->release_notes }}</p>
                    </div>
                @endif

                <a class="download-btn" href="{{ $latestVersion->apk_url }}">
                    Download Latest APK
                </a>
            @else
                <div class="empty-box">
                    No Android app version is currently available. Please contact your manager or system administrator.
                </div>
            @endif

            <div class="footer">
                MGM Ops is an internal hotel operations application.
            </div>
        </div>
    </div>

    <div class="info-card">
        <div>
            <div class="info-title">How to install</div>

            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <div>
                        <strong>Download the APK</strong>
                        <p>Tap the download button and wait for the file to finish downloading.</p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">2</div>
                    <div>
                        <strong>Allow installation</strong>
                        <p>If Android asks, allow installation from your browser or file manager.</p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">3</div>
                    <div>
                        <strong>Install and login</strong>
                        <p>Open MGM Ops and login using the account provided by your manager.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="security-note">
            Only install this app from the official MGM Ops staff download page.
            Do not share your login details with anyone.
        </div>
    </div>

</div>

</body>
</html>