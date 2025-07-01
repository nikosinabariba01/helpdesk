<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Telegram Login Demo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f3f6fa; }
    .center-box { min-height: 100vh; display: flex; align-items: center; justify-content: center; }
    .profile-photo { width: 96px; height: 96px; object-fit: cover; border-radius: 50%; border: 3px solid #0d6efd; margin-bottom: 10px;}
  </style>
</head>
<body>
  <div class="center-box">
    <div class="card shadow p-4 border-0" style="min-width:330px;">
      <h3 class="mb-3 text-center">Login dengan Telegram</h3>
      <div id="telegram-widget-box" class="mb-3 text-center">
        <script async src="https://telegram.org/js/telegram-widget.js?22"
          data-telegram-login="kos74_bot"  {{-- GANTI DENGAN BOT MU TANPA @ --}}
          data-size="large"
          data-userpic="false"
          data-request-access="write"
          data-onauth="onTelegramAuth">
        </script>
      </div>
      <div id="profile-wrap" class="text-center"></div>
    </div>
  </div>
  <script>
    function onTelegramAuth(user) {
      document.getElementById('profile-wrap').innerHTML = `
        ${user.photo_url ? `<img src="${user.photo_url}" class="profile-photo">` : ""}
        <h5 class="fw-bold">Hello, ${user.first_name ?? ""} ${user.last_name ?? ""}!</h5>
        <ul class="list-group mb-0 mt-3">
          <li class="list-group-item"><b>First Name:</b> ${user.first_name ?? '-'}</li>
          <li class="list-group-item"><b>Last Name:</b> ${user.last_name ?? '-'}</li>
          <li class="list-group-item"><b>Username:</b> ${user.username ? `<a href="https://t.me/${user.username}" target="_blank">@${user.username}</a>` : '-'}</li>
          <li class="list-group-item"><b>Telegram ID:</b> ${user.id}</li>
        </ul>
      `;
      document.getElementById('telegram-widget-box').style.display = "none";
    }
  </script>
</body>
</html>
