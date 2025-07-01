<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login Telegram - Hilangkan Widget Setelah Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <style>
    body { background: #f3f6fa; padding: 40px; }
  </style>
</head>
<body>
  <div class="container" style="max-width:400px">
    <h4 class="mb-4">Login dengan Telegram</h4>
    <!-- Tempat widget Telegram -->
    <div id="telegram-widget-box" class="mb-4"></div>

    <!-- Hasil login Telegram -->
    <div id="hasil-telegram"></div>
  </div>

  <script>
    // Render Telegram widget via JS agar mudah diganti/hide setelah login
    window.onload = function() {
      let box = document.getElementById('telegram-widget-box');
      box.innerHTML = `
        <script async src="https://telegram.org/js/telegram-widget.js?22"
          data-telegram-login="kos74_bot"
          data-size="large"
          data-userpic="false"
          data-request-access="write"
          data-onauth="onTelegramAuth">
        <\/script>
      `;
    }

    // Callback setelah login sukses
    function onTelegramAuth(user) {
      // Hapus/hide widget Telegram
      document.getElementById('telegram-widget-box').innerHTML = "";

      // Tampilkan info user Telegram dengan Bootstrap
      document.getElementById('hasil-telegram').innerHTML = `
        <div class="card shadow-sm">
          <div class="card-body text-center">
            ${user.photo_url ? `<img src="${user.photo_url}" class="rounded-circle mb-2" style="width:64px;height:64px;">` : ''}
            <h5 class="mb-0">${user.first_name || ''} ${user.last_name || ''}</h5>
            <div class="text-secondary small mb-1">@${user.username || '<i>no username</i>'}</div>
            <div class="mb-1">ID: <b>${user.id}</b></div>
            <div class="text-success">Sudah login via Telegram!</div>
          </div>
        </div>
      `;
      // Optional: juga tampilkan data di console
      console.log('Data dari Telegram:', user);
    }
  </script>
</body>
</html>
