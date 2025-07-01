<!DOCTYPE html>
<html>
<head>
  <title>Telegram Login Test</title>
</head>
<body>
  <h2>Test Telegram Widget</h2>

  <div id="telegram-login-widget"></div>

  <script>
    // Pasang widget secara dinamis, username bot TANPA @
    document.addEventListener('DOMContentLoaded', function() {
      var tgWidget = document.createElement('script');
      tgWidget.async = true;
      tgWidget.src = 'https://telegram.org/js/telegram-widget.js?7';
      tgWidget.setAttribute('data-telegram-login', 'kos74_bot'); // <- GANTI sesuai bot kamu
      tgWidget.setAttribute('data-size', 'large');
      tgWidget.setAttribute('data-userpic', 'false');
      tgWidget.setAttribute('data-request-access', 'write');
      tgWidget.setAttribute('data-on-auth', 'onTelegramAuth'); // JANGAN typo!
      document.getElementById('telegram-login-widget').appendChild(tgWidget);
    });

    // WAJIB GLOBAL (bukan dalam scope lain)!
    function onTelegramAuth(user) {
      alert("Auth data dari Telegram:\n" + JSON.stringify(user, null, 2)); // tes saja tampilkan

      // Coba POST ke endpointmu
      fetch('/telegram/auth', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(user)
      })
      .then(response => response.json())
      .then(res => {
        alert('Response dari server: ' + JSON.stringify(res));
        // location.reload(); // kalau ingin reload kalau sukses
      });
    }
  </script>
</body>
</html>
