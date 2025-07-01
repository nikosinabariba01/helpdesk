<div class="d-flex flex-column align-items-center justify-content-center" style="height: 70vh;">
    <div class="card shadow-sm p-4" style="min-width: 350px;">
        <h3 class="mb-4 text-center">Login dengan Telegram</h3>
        <div id="telegram-widget-box" class="mb-3">
            <!-- Widget -->
            <script async src="https://telegram.org/js/telegram-widget.js?22"
              data-telegram-login="kos74_bot"
              data-size="large"
              data-userpic="true"
              data-request-access="write"
              data-onauth="onTelegramAuth">
            </script>
        </div>
        <div id="hasil-telegram"></div>
    </div>
</div>
<script>
  // Penting: pastikan function global!
  window.onTelegramAuth = function(user) {
    document.getElementById('hasil-telegram').innerHTML = `
      <div class="text-center">
        <h5>Hello, ${user.first_name || ''} ${user.last_name || ''}!</h5>
        <img src="${user.photo_url}" class="rounded-circle mb-2" style="width:96px;height:96px">
        <div class="mb-1"><b>Username:</b> @${user.username || '-'}</div>
        <div class="mb-1"><b>ID:</b> ${user.id}</div>
      </div>
    `;
    // Opsional: log ke console
    console.log('Data Telegram:', user);
  }
</script>
