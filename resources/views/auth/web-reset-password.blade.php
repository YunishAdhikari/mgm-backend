<!DOCTYPE html>
<html>
<head>
    <title>Reset Password - MGM Ops</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
        }

        .header {
            background: linear-gradient(135deg, #e94560 0%, #0f3460 100%);
            padding: 40px 40px 60px;
            text-align: center;
            position: relative;
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 0;
            right: 0;
            height: 40px;
            background: white;
            border-radius: 20px 20px 0 0;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .logo svg {
            width: 40px;
            height: 40px;
            fill: #e94560;
        }

        .header h1 {
            color: white;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
        }

        .form-container {
            padding: 20px 40px 40px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            color: #333;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e8e8e8;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-group input:focus {
            outline: none;
            border-color: #e94560;
            background: white;
            box-shadow: 0 0 0 4px rgba(233, 69, 96, 0.1);
        }

        .form-group input::placeholder {
            color: #aaa;
        }

        .error-message {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #e94560 0%, #0f3460 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(233, 69, 96, 0.4);
        }

        button:active {
            transform: translateY(0);
        }

        .back-link {
            text-align: center;
            margin-top: 24px;
        }

        .back-link a {
            color: #666;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .back-link a:hover {
            color: #e94560;
        }

        .icon-group {
            display: flex;
            gap: 8px;
        }

        /* Password strength indicator */
        .password-hint {
            font-size: 12px;
            color: #888;
            margin-top: 6px;
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }

            .header {
                padding: 30px 30px 50px;
            }

            .form-container {
                padding: 15px 25px 30px;
            }

            .header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <div class="logo">
                <!-- Lock Icon SVG -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 1C8.676 1 6 3.676 6 7v2H4v14h16V9h-2V7c0-3.324-2.676-6-6-6zm0 2c2.276 0 4 1.724 4 4v2H8V7c0-2.276 1.724-4 4-4zm0 10c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z"/>
                </svg>
            </div>
            <h1>Reset Password</h1>
            <p>Create a new secure password for your account</p>
        </div>

        <div class="form-container">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                @if($errors->any())
                    <div class="error-message">
                        @foreach($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif

                <div class="form-group">
                    <label>New Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        placeholder="Enter your new password"
                        required
                    >
                    <div class="password-hint">Use at least 8 characters with letters and numbers</div>
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        placeholder="Confirm your new password"
                        required
                    >
                </div>

                <button type="submit">
                    🔐 Update Password
                </button>

                <div class="back-link">
                    <a href="{{ route('login') }}">← Back to Login</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>