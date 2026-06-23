<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MGM Ops | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="{{ asset('myapp.png') }}">


    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background:
                radial-gradient(circle at top, rgba(239, 68, 68, 0.20), transparent 32%),
                radial-gradient(circle at bottom right, rgba(255, 255, 255, 0.08), transparent 28%),
                #050505;
            color: #f5f5f7;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            overflow-x: hidden;
        }

        .login-shell {
            width: 100%;
            max-width: 1120px;
            min-height: 680px;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            background: rgba(24, 24, 27, 0.72);
            border: 1px solid rgba(255,255,255,.10);
            border-radius: 38px;
            overflow: hidden;
            box-shadow: 0 40px 140px rgba(0,0,0,.65);
            backdrop-filter: blur(24px);
        }

        .brand-panel {
            position: relative;
            padding: 48px;
            background:
                radial-gradient(circle at 20% 15%, rgba(239,68,68,.30), transparent 30%),
                radial-gradient(circle at 85% 85%, rgba(255,255,255,.10), transparent 28%),
                #09090b;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }

        .brand-panel::after {
            content: "";
            position: absolute;
            width: 460px;
            height: 460px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(239,68,68,.28), transparent);
            filter: blur(20px);
            right: -180px;
            bottom: -180px;
        }

        .logo {
            position: relative;
            z-index: 1;
            font-size: 26px;
            font-weight: 900;
            letter-spacing: -0.7px;
        }

        .logo span {
            color: #ef4444;
        }

        .brand-copy {
            position: relative;
            z-index: 1;
        }

        .pill {
            display: inline-flex;
            background: rgba(239,68,68,.12);
            border: 1px solid rgba(239,68,68,.30);
            color: #fca5a5;
            padding: 9px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 900;
            margin-bottom: 22px;
        }

        .brand-copy h1 {
            margin: 0;
            font-size: clamp(42px, 5vw, 68px);
            line-height: .95;
            letter-spacing: -3px;
            font-weight: 900;
        }

        .brand-copy h1 span {
            background: linear-gradient(135deg, #fff, #fca5a5, #ef4444);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand-copy p {
            max-width: 520px;
            margin-top: 24px;
            color: #a1a1aa;
            font-size: 17px;
            line-height: 1.7;
        }

        .mini-metrics {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .metric {
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.10);
            border-radius: 20px;
            padding: 18px;
        }

        .metric strong {
            display: block;
            font-size: 24px;
            color: #fff;
        }

        .metric span {
            color: #a1a1aa;
            font-size: 12px;
        }

        .form-panel {
            background: rgba(9,9,11,.92);
            padding: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-box {
            width: 100%;
            max-width: 390px;
        }

        .form-box h2 {
            margin: 0;
            font-size: 34px;
            font-weight: 900;
            letter-spacing: -1px;
        }

        .form-box .subtitle {
            margin: 10px 0 34px;
            color: #a1a1aa;
            line-height: 1.6;
        }

        .error-box {
            background: rgba(239,68,68,.12);
            border: 1px solid rgba(239,68,68,.35);
            color: #f87171;
            padding: 13px 14px;
            border-radius: 16px;
            margin-bottom: 18px;
            font-size: 13px;
        }

        .field {
            margin-bottom: 18px;
        }

        .field-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 7px;
        }

        label {
            color: #d4d4d8;
            font-size: 13px;
            font-weight: 800;
        }

        .forgot {
            color: #f87171;
            font-size: 13px;
            font-weight: 800;
        }

        input {
            width: 100%;
            background: #18181b;
            border: 1px solid #3f3f46;
            color: #fafafa;
            border-radius: 16px;
            padding: 15px 16px;
            outline: none;
            font-size: 14px;
        }

        input:focus {
            border-color: #ef4444;
            box-shadow: 0 0 0 4px rgba(239,68,68,.12);
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 9px;
            margin: 6px 0 22px;
            color: #a1a1aa;
            font-size: 13px;
        }

        .remember-row input {
            width: auto;
        }

        .login-btn {
            width: 100%;
            border: none;
            background: linear-gradient(135deg, #ef4444, #b91c1c);
            color: white;
            padding: 15px 18px;
            border-radius: 18px;
            font-weight: 900;
            font-size: 15px;
            cursor: pointer;
            box-shadow: 0 18px 42px rgba(239,68,68,.24);
        }

        .login-btn:hover {
            transform: translateY(-1px);
        }

        .back-home {
            display: block;
            text-align: center;
            margin-top: 22px;
            color: #71717a;
            font-size: 13px;
            font-weight: 700;
        }

        .back-home:hover {
            color: #fafafa;
        }

        @media(max-width: 900px) {
            .login-shell {
                grid-template-columns: 1fr;
            }

            .brand-panel {
                min-height: 420px;
            }
        }

        @media(max-width: 560px) {
            body {
                padding: 14px;
                align-items: flex-start;
            }

            .login-shell {
                border-radius: 26px;
            }

            .brand-panel,
            .form-panel {
                padding: 28px;
            }

            .mini-metrics {
                grid-template-columns: 1fr;
            }

            .form-box h2 {
                font-size: 28px;
            }
        }
    </style>
</head>

<body>

<div class="login-shell">

    <section class="brand-panel">
        <div class="logo">MGM<span>One</span></div>

        <div class="brand-copy">
            <div class="pill">Internal Hotel Operations</div>

            <h1>Sign in to<br><span>manage smarter.</span></h1>

            <p>
                Access housekeeping, maintenance, rota, attendance, restaurant bookings,
                and internal hotel operations from one secure MGM Ops platform.
            </p>
        </div>

        <div class="mini-metrics">
            <div class="metric">
                <strong>24/7</strong>
                <span>Staff access</span>
            </div>

            <div class="metric">
                <strong>6+</strong>
                <span>Modules</span>
            </div>

            <div class="metric">
                <strong>100%</strong>
                <span>Internal use</span>
            </div>
        </div>
    </section>

    <section class="form-panel">
        <div class="form-box">
            <h2>Welcome back</h2>
            <p class="subtitle">Login with your MGM Ops staff account.</p>

            @if ($errors->any())
                <div class="error-box">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <label>Email address</label>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           autocomplete="email"
                           required
                           autofocus>
                </div>

                <div class="field">
                    <div class="field-row">
                        <label>Password</label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot">
                                Forgot?
                            </a>
                        @endif
                    </div>

                    <input type="password"
                           name="password"
                           autocomplete="current-password"
                           required>
                </div>

                <label class="remember-row">
                    <input type="checkbox" name="remember">
                    Remember me
                </label>

                <button type="submit" class="login-btn">
                    Sign in
                </button>
            </form>

            <a href="{{ url('/') }}" class="back-home">
                ← Back to MGM Ops
            </a>
        </div>
    </section>

</div>

</body>
</html>