<!DOCTYPE html>
<html>
<head>
  <title>Test Telegram Widget</title>
</head>
<body>
  <h1>Test Telegram Widget</h1>
  <script>
    function onTelegramAuth(user) {
      alert('Telegram: ' + JSON.stringify(user));
    }
  </script>
  <script async src="https://telegram.org/js/telegram-widget.js?7"
    data-telegram-login="kos74_bot"
    data-size="large"
    data-userpic="false"
    data-request-access="write"
    data-on-auth="onTelegramAuth"></script>
</body>
</html>
