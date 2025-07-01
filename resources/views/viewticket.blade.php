<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Test Telegram Widget</title>
    <style>
        #login-success { color: green; font-weight: bold; margin-top: 16px; }
    </style>
</head>
<body>
    <h1>Test Telegram Widget</h1>
    <div id="telegram-login-widget"></div>
    <div id="login-success" style="display:none"></div>

    <script>
    // Render widget
    document.addEventListener('DOMContentLoaded', function() {
        let tgWidget = document.createElement('script');
        tgWidget.async = true;
        tgWidget.src = 'https://telegram.org/js/telegram-widget.js?7';
        tgWidget.setAttribute('data-telegram-login', 'kos74_bot'); // Ganti sesuai bot kamu
        tgWidget.setAttribute('data-size', 'large');
        tgWidget.setAttribute('data-userpic', 'false');
        tgWidget.setAttribute('data-request-access', 'write');
        tgWidget.setAttribute('data-on-auth', 'onTelegramAuth');
        document.getElementById('telegram-login-widget').appendChild(tgWidget);
    });

    // Ganti alert → update halaman
    function onTelegramAuth(user) {
        // Sembunyikan tombol login
        document.getElementById('telegram-login-widget').style.display = 'none';
        // Tampilkan pesan sukses + nama
        document.getElementById('login-success').style.display = 'block';
        document.getElementById('login-success').textContent =
            `Selamat datang, ${user.first_name}${user.last_name ? ' ' + user.last_name : ''}! Telegram ID Anda: ${user.id}`;
        
        // Optional: kirim ke backend, reload, dsb.
        // fetch('/telegram/auth', { ... });
        // location.reload();
    }
    </script>
</body>
</html>
