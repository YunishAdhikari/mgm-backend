<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Password - MGM Ops</title>

    <script>
        window.onload = function () {
            const token = @json($token);
            const email = @json($email);

            const deepLink = `mgmops://reset-password?token=${token}&email=${email}`;

            const webResetUrl = `/reset-password?token=${token}&email=${email}`;

            const userAgent = navigator.userAgent || navigator.vendor || window.opera;

            const isAndroid = /android/i.test(userAgent);
            const isIOS = /iPad|iPhone|iPod/.test(userAgent) && !window.MSStream;

            if (isAndroid || isIOS) {
                window.location.href = deepLink;

                setTimeout(function () {
                    window.location.href = webResetUrl;
                }, 2000);
            } else {
                window.location.href = webResetUrl;
            }
        };
    </script>
</head>

<body style="font-family: Arial, sans-serif; text-align:center; padding:40px;">
    <h2>Opening MGM Ops...</h2>
    <p>Please wait while we redirect you to reset your password.</p>

    <p>
        If nothing happens,
        <a href="/reset-password?token={{ $token }}&email={{ $email }}">
            click here to reset password on web
        </a>.
    </p>
</body>
</html>