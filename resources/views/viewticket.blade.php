<!DOCTYPE html>
<html>
<head><title>Test Telegram Widget</title></head>
<body>
  <h2>Telegram Login Demo</h2>
  <div id="tg-login-here"></div>
  <script>
    // tambahkan script widget manual (biar bisa dihapus nanti)
    let tg = document.createElement('script');
    tg.async = true;
    tg.src = 'https://telegram.org/js/telegram-widget.js?22';
    tg.setAttribute('data-telegram-login', 'kos74_bot'); // username bot kamu
    tg.setAttribute('data-size', 'large');
    tg.setAttribute('data-userpic', 'false');
    tg.setAttribute('data-request-access', 'write');
    tg.setAttribute('data-onauth', 'onTelegramAuth');
    document.getElementById('tg-login-here').appendChild(tg);

    function onTelegramAuth(user) {
      document.getElementById('tg-login-here').innerHTML = '<b>Sukses login sebagai ' + user.first_name + '</b>';
      console.log(user);
      // kirim ke server? fetch('/auth/telegram', ...)
    }
  </script>
</body>
</html>
