<!DOCTYPE html>
<html>
<head>
    <title>Live Attendance QR</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
        body {
            background: #111;
            color: white;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        #qrcode {
            background: white;
            padding: 20px;
            border-radius: 20px;
        }

        h1 {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<h1>Staff Attendance QR</h1>

<div id="qrcode"></div>

<script>
    async function loadQr() {

        const response = await fetch('/attendance/live-qr');

        const data = await response.json();

        document.getElementById('qrcode').innerHTML = "";

        new QRCode(document.getElementById("qrcode"), {
            text: data.token,
            width: 300,
            height: 300,
        });
    }

    loadQr();

    setInterval(loadQr, 30000);
</script>

</body>
</html>