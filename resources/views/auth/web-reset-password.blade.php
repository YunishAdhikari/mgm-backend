<!DOCTYPE html>
<html>
<head>
    <title>Reset Password - MGM Ops</title>
</head>
<body style="font-family:Arial;padding:40px;">
    <h2>Reset Password</h2>

    <form method="POST" action="{{ url('/api/reset-password') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div>
            <label>New Password</label><br>
            <input type="password" name="password" required>
        </div>

        <br>

        <div>
            <label>Confirm Password</label><br>
            <input type="password" name="password_confirmation" required>
        </div>

        <br>

        <button type="submit">Reset Password</button>
    </form>
</body>
</html>