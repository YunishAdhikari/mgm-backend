<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MGM One</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #1a1a1a;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 380px;
        }

        .login-card {
            background: #262626;
            border: 1px solid #404040;
            border-radius: 16px;
            padding: 40px 32px;
            width: 100%;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            object-fit: contain;
            margin-bottom: 16px;
        }

        .logo-section h1 {
            color: #ffffff;
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .logo-section p {
            color: #9ca3af;
            font-size: 13px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #d1d5db;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px;
            background: #1a1a1a;
            border: 1px solid #404040;
            border-radius: 8px;
            color: #ffffff;
            font-size: 14px;
            outline: none;
            transition: border-color 0.3s;
        }

        .form-group input::placeholder {
            color: #6b7280;
        }

        .form-group input:focus {
            border-color: #dc2626;
        }

        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remember-me input {
            width: 16px;
            height: 16px;
            accent-color: #dc2626;
        }

        .remember-me span {
            color: #9ca3af;
            font-size: 13px;
        }

        .forgot-password {
            color: #dc2626;
            font-size: 13px;
            text-decoration: none;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background: #dc2626;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .submit-btn:hover {
            background: #b91c1c;
        }

        .error-message {
            background: rgba(220, 38, 38, 0.1);
            border: 1px solid rgba(220, 38, 38, 0.3);
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
            display: none;
        }

        .error-message.show {
            display: block;
        }

        .error-message p {
            color: #fca5a5;
            font-size: 13px;
        }

        .footer {
            text-align: center;
            margin-top: 24px;
        }

        .footer p {
            color: #6b7280;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Logo Section -->
            <div class="logo-section">
                <img 
                    src="https://www.muthuhotelsmgm.com/assets/images/logo.svg" 
                    alt="MGM Logo" 
                    class="logo"
                >
                <h1>MGM One</h1>
                <p>Sign in to continue</p>
            </div>

            <!-- Error Message -->
            @if($errors->any())
            <div class="error-message show">
                <p>{{ $errors->first() }}</p>
            </div>
            @endif

            <!-- Login Form - Simple HTML Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="name@example.com" value="{{ old('email') }}" required>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>

                <!-- Remember & Forgot -->
                <div class="form-footer">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" id="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-password">Forgot password?</a>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-btn">Sign In</button>
            </form>

            <!-- Footer -->
            <div class="footer">
                <p>Need access? Contact administrator</p>
            </div>
        </div>
    </div>
</body>
</html>