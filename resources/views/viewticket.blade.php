<div id="telegram-widget-box"></div>
<div id="hasil-telegram"></div>

<script>
  // Render widget ke dalam #telegram-widget-box (langsung pada page load)
  document.getElementById('telegram-widget-box').innerHTML = `
    <script async src="https://telegram.org/js/telegram-widget.js?22"
      data-telegram-login="kos74_bot"
      data-size="large"
      data-userpic="false"
      data-request-access="write"
      data-onauth="onTelegramAuth">
    <\/script>
  `;

  // Callback HANYA dipanggil SETELAH login berhasil (otomatis oleh Telegram)
  function onTelegramAuth(user) {
    // Hapus widget
    document.getElementById('telegram-widget-box').innerHTML = "";
    // Tampilkan data Telegram user
    document.getElementById('hasil-telegram').innerHTML = `
      <b>Halo, ${user.first_name}</b><br>
      Username: @${user.username}<br>
      ID: ${user.id}<br>
      ${user.photo_url ? `<img src="${user.photo_url}" width="64">` : ""}
    `;
  }
</script>
