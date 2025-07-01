@extends('mainlayout.layout')

@section('container')
<div class="d-flex align-items-center justify-content-center" style="min-height: 80vh; background: #f3f6fa;">
  <div class="card shadow-sm p-5 text-center" style="min-width: 350px;">
    <h3 class="mb-4">Login dengan Telegram</h3>
    <div id="telegram-widget-box" class="mb-3">
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
  window.onTelegramAuth = function(user) {
    document.getElementById('hasil-telegram').innerHTML = `
      <div class="text-center mt-3">
        <h5>Halo, ${user.first_name || ''} ${user.last_name || ''}!</h5>
        ${user.photo_url ? `<img src="${user.photo_url}" class="rounded-circle mb-2" style="width:80px;height:80px">` : ''}
        <div class="mb-1"><b>Username:</b> @${user.username || '-'}</div>
        <div class="mb-1"><b>ID:</b> ${user.id}</div>
      </div>
    `;
  }
</script>
@endsection
