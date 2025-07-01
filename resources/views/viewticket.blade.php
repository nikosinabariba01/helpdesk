<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Test Telegram Widget</title>
</head>
<body>
  <h2>Login dengan Telegram</h2>
  <div id="telegram-widget-box">
    <!-- HANYA ini di sini -->
    <script async src="https://telegram.org/js/telegram-widget.js?22"
      data-telegram-login="kos74_bot"
      data-size="large"
      data-userpic="false"
      data-request-access="write"
      data-onauth="onTelegramAuth">
    </script>
  </div>
  <!-- CALLBACK HARUS DILUAR SITU! -->
  <script>
    function onTelegramAuth(user) {
      console.log('DATA DARI TELEGRAM:', user);
    }
  </script>
</body>
</html>
