<!DOCTYPE html>
<html>
<head><title>Widget Test</title></head>
<body>
  <div id="telegram-login-widget"></div>
  <script>
    function onTelegramAuth(user) {
      alert('User: ' + JSON.stringify(user));
    }
    document.addEventListener('DOMContentLoaded', function() {
      var tgWidget = document.createElement('script');
      tgWidget.async = true;
      tgWidget.src = 'https://telegram.org/js/telegram-widget.js?7';
      tgWidget.setAttribute('data-telegram-login', 'kos74_bot');
      tgWidget.setAttribute('data-size', 'large');
      tgWidget.setAttribute('data-userpic', 'false');
      tgWidget.setAttribute('data-request-access', 'write');
      tgWidget.setAttribute('data-on-auth', 'onTelegramAuth');
      document.getElementById('telegram-login-widget').appendChild(tgWidget);
    });
  </script>
</body>
</html>
