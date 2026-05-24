<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - MGM OPS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #dc2626;
            --primary-dark: #b91c1c;
            --primary-light: #f87171;
            --dark: #0f0f0f;
            --dark-secondary: #1a1a1a;
            --gray: #262626;
            --gray-light: #404040;
            --text: #ffffff;
            --text-muted: #9ca3af;
            --text-dim: #6b7280;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #7f1d1d 0%, #450a0a 50%, #1c1917 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .forgot-container {
            width: 100%;
            max-width: 420px;
        }

        .forgot-card {
            background: rgba(30, 30, 30, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(185, 28, 28, 0.3);
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        /* Logo Section */
        .logo-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo {
            width: 80px;
            height: 80px;
            border-radius: 16px;
            object-fit: contain;
            margin-bottom: 16px;
        }

        .logo-section h1 {
            color: white;
            font-size: 24px;
            font-weight: 700;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 16px;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(185, 28, 28, 0.3);
            border-radius: 12px;
            color: white;
            font-size: 15px;
            outline: none;
            transition: all 0.3s;
        }

        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        .form-input:focus {
            background: rgba(0, 0, 0, 0.5);
            border-color: #dc2626;
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.2);
        }

        /* Error Message */
        .error-message {
            background: rgba(185, 28, 28, 0.2);
            border: 1px solid rgba(220, 38, 38, 0.5);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .error-message.show {
            display: flex;
        }

        .error-message svg {
            width: 20px;
            height: 20px;
            color: #fca5a5;
            flex-shrink: 0;
        }

        .error-message p {
            color: #fca5a5;
            font-size: 14px;
        }

        /* Success Message */
        .success-message {
            background: rgba(34, 197, 94, 0.2);
            border: 1px solid rgba(34, 197, 94, 0.5);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .success-message svg {
            width: 20px;
            height: 20px;
            color: #22c55e;
            flex-shrink: 0;
        }

        .success-message p {
            color: #22c55e;
            font-size: 14px;
        }

        /* Info Message */
        .info-message {
            background: rgba(59, 130, 246, 0.15);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
        }

        .info-message p {
            color: #93c5fd;
            font-size: 14px;
            line-height: 1.6;
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 10px 30px rgba(220, 38, 38, 0.4);
            position: relative;
            overflow: hidden;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(220, 38, 38, 0.5);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .submit-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* Loading Spinner */
        .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        @keyframes spin {
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }

        .submit-btn.loading .btn-text {
            visibility: hidden;
        }

        .submit-btn.loading .spinner {
            display: block;
        }

        /* Links */
        .form-links {
            text-align: center;
            margin-top: 24px;
        }

        .form-links a {
            color: #f87171;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }

        .form-links a:hover {
            color: #fca5a5;
        }

        /* Back to Login */
        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: var(--text-muted);
            font-size: 14px;
            margin-top: 24px;
        }

        .back-link a {
            color: #f87171;
            text-decoration: none;
            transition: color 0.3s;
        }

        .back-link a:hover {
            color: #fca5a5;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .forgot-card {
                padding: 32px 24px;
            }
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="forgot-card">
            <!-- Logo Section -->
            <div class="logo-section">
                <img 
                    src="https://www.muthuhotelsmgm.com/uploads/hotel/logo/4309af2d8f39f7f5f03b62571787780b1747654917.jpg" 
                    alt="MGM Logo" 
                    class="logo"
                >
                <h1>Reset Password</h1>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="success-message show">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p>{{ session('status') }}</p>
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="error-message show">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p>{{ $errors->first() }}</p>
                </div>
            @endif

            <!-- Info -->
            <div class="info-message">
                <p>Enter your email address and we will send you a link to reset your password. The link will expire in 60 minutes.</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('password.email') }}" id="forgotForm">
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input" 
                        placeholder="name@example.com" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus
                    >
                </div>

                <!-- Submit -->
                <button type="submit" class="submit-btn" id="submitBtn">
                    <span class="btn-text">Send Password Reset Link</span>
                    <div class="spinner"></div>
                </button>
            </form>

            <!-- Back to Login -->
            <div class="back-link">
                <i class="fas fa-arrow-left"></i>
                Remember your password? 
                <a href="{{ route('login') }}">Sign in here</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('forgotForm');
            const submitBtn = document.getElementById('submitBtn');

            form.addEventListener('submit', function() {
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
            });
        });
    </script>
</body>
</html>