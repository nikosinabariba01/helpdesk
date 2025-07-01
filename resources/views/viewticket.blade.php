<div id="telegram-widget-box">
  <script async src="https://telegram.org/js/telegram-widget.js?22"
    data-telegram-login="kos74_bot"
    data-size="large"
    data-userpic="false"
    data-request-access="write"
    data-onauth="onTelegramAuth">
  </script>
</div>
<div id="hasil-telegram"></div>

<script>
  function onTelegramAuth(user) {
    document.getElementById('telegram-widget-box').innerHTML = "";
    document.getElementById('hasil-telegram').innerHTML = `
      <b>Halo, ${user.first_name}</b><br>
      Username: @${user.username}<br>
      ID: ${user.id}<br>
      ${user.photo_url ? `<img src="${user.photo_url}" width="64">` : ""}
    `;
  }
</script>
