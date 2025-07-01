<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Telegram Profile Style</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      background: #f4f7fa;
      font-family: 'Segoe UI', Arial, sans-serif;
      display: flex; min-height: 100vh;
      align-items: center; justify-content: center;
    }
    #profile-card {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 4px 32px #0002;
      padding: 36px 32px 24px 32px;
      min-width: 330px;
      display: flex; flex-direction: column;
      align-items: center;
    }
    #profile-card img {
      border-radius: 50%;
      width: 120px; height: 120px;
      margin-bottom: 18px;
      border: 4px solid #1b9cff;
      background: #eee;
    }
    #profile-card h2 {
      margin: 0 0 16px 0;
      font-size: 1.5em;
      text-align: center;
      color: #222;
    }
    .info-box {
      background: #f3f8fc;
      border-radius: 22px;
      color: #233d58;
      font-size: 1.08em;
      padding: 12px 22px;
      margin-bottom: 14px;
      min-width: 230px;
      text-align: center;
      box-shadow: 0 1px 8px #0cf2;
    }
    .info-box a {
      color: #1b9cff;
      text-decoration: none;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <!-- Telegram Login Widget -->
  <div id="telegram-widget-box">
    <script async src="https://telegram.org/js/telegram-widget.js?22"
      data-telegram-login="kos74_bot"
      data-size="large"
      data-userpic="false"
      data-request-access="write"
      data-onauth="onTelegramAuth">
    </script>
  </div>

  <div id="profile-wrap"></div>

  <script>
    function onTelegramAuth(user) {
      // Cek jika user ada foto profil
      let foto = user.photo_url 
        ? `<img src="${user.photo_url}" alt="Profile Photo">` 
        : `<img src="https://ui-avatars.com/api/?name=${user.first_name}+${user.last_name}&background=1b9cff&color=fff" alt="Avatar">`;
      document.getElementById('profile-wrap').innerHTML = `
        <div id="profile-card">
          ${foto}
          <h2>Hello, ${user.first_name || ''} ${user.last_name || ''}!</h2>
          <div class="info-box">First Name: ${user.first_name || '-'}</div>
          <div class="info-box">Last Name: ${user.last_name || '-'}</div>
          <div class="info-box">
            Username: ${
              user.username 
                ? `<a href="https://t.me/${user.username}" target="_blank">@${user.username}</a>`
                : '<span style="color:#bbb">-</span>'
            }
          </div>
          <div class="info-box">Telegram ID: ${user.id}</div>
        </div>
      `;
      // Sembunyikan widget login setelah login (opsional)
      document.getElementById('telegram-widget-box').style.display = "none";
    }
  </script>
</body>
</html>
