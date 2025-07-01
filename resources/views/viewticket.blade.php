<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Telegram Widget Test</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body { font-family: Arial, sans-serif; background: #f3f6fa; padding: 40px;}
    #hasil-telegram { margin-top: 30px; }
  </style>
</head>
<body>
  <h2>Login dengan Telegram</h2>
  <!-- Telegram Login Widget -->
  <div id="telegram-widget-box">
    <script async src="https://telegram.org/js/telegram-widget.js?22"
      data-telegram-login="kos74_bot"   <!-- Ganti dengan username botmu tanpa @ -->
      data-size="large"
      data-userpic="false"
      data-request-access="write"
      data-onauth="onTelegramAuth">
    </script>
  </div>

  <div id="hasil-telegram"></div>

  <script>
    // Callback dari widget Telegram
    function onTelegramAuth(user) {
      document.getElementById('hasil-telegram').innerHTML = `
        <div style="background:#fff;padding:24px 24px 12px 24px;border-radius:14px;box-shadow:0 1px 10px #0001;">
          <b>Halo, ${user.first_name || ''} ${user.last_name || ''}!</b><br>
          Username: ${user.username ? '@' + user.username : '(tidak ada username)'}<br>
          Telegram ID: ${user.id}<br>
          ${user.photo_url ? `<img src="${user.photo_url}" width="64" style="margin-top:8px;border-radius:40px;">` : ''}
        </div>
      `;
      // Juga log ke console (opsional)
      console.log('Data dari Telegram:', user);
    }
  </script>
</body>
</html>
