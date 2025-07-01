<div id="telegram-widget-box">
  <script async src="https://telegram.org/js/telegram-widget.js?22"
    data-telegram-login="kos74_bot"  {{-- Ganti dengan username bot kamu --}}
    data-size="large"
    data-userpic="false"
    data-request-access="write"
    data-onauth="onTelegramAuth">
  </script>
</div>

<div id="hasil-telegram"></div>

<script>
function onTelegramAuth(user) {
  document.getElementById('hasil-telegram').innerHTML = `
    <b>Data dari Telegram (client):</b>
    <pre>${JSON.stringify(user, null, 2)}</pre>
    <b>Kirim ke server untuk di-echo:</b>
  `;
  // Kirim ke Laravel (tes echo)
  fetch('/tes-echo-telegram', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify(user)
  })
  .then(res => res.text())
  .then(res => {
    document.getElementById('hasil-telegram').innerHTML += res;
  });
}
</script>
