<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cek Data Telegram Widget</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  <h2>Login dengan Telegram</h2>
  <div id="telegram-widget-box">
    <script async src="https://telegram.org/js/telegram-widget.js?22"
      data-telegram-login="kos74_bot"      <!-- Ganti dengan username botmu tanpa @ -->
      data-size="large"
      data-userpic="false"
      data-request-access="write"
      data-onauth="onTelegramAuth">
    </script>
  </div>
  
  <script>
    function onTelegramAuth(user) {
      // Tampilkan semua data user ke console browser
      console.log('DATA DARI TELEGRAM:', user);
    }
  </script>
</body>
</html>
