@extends('layouts.app') {{-- atau @extends('mainlayout.layout') --}}
@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-12 col-md-7 col-lg-5">
        <div class="card shadow border-0 p-4">
            <div class="card-body text-center">
                <h3 class="mb-3 fw-bold">Login dengan Telegram</h3>
                <div id="telegram-widget-box" class="mb-3">
                    <!-- Telegram Login Widget akan muncul di sini -->
                    <script async src="https://telegram.org/js/telegram-widget.js?22"
                        data-telegram-login="kos74_bot" {{-- GANTI DENGAN BOT KAMU TANPA @ --}}
                        data-size="large"
                        data-userpic="false"
                        data-request-access="write"
                        data-onauth="onTelegramAuth">
                    </script>
                </div>

                <div id="profile-wrap"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function onTelegramAuth(user) {
    // Buat konten profil Telegram yang rapi pakai Bootstrap
    let foto = user.photo_url
        ? `<img src="${user.photo_url}" class="rounded-circle border border-3 border-primary mb-3" width="96" height="96" alt="Profile Photo">`
        : `<div class="rounded-circle bg-secondary mb-3" style="width:96px;height:96px;display:inline-block;"></div>`;
    document.getElementById('profile-wrap').innerHTML = `
        <div class="mb-2">${foto}</div>
        <h5 class="mb-1 fw-bold">Hello, ${user.first_name ?? ''} ${user.last_name ?? ''}!</h5>
        <div class="list-group text-start mt-3">
            <div class="list-group-item">
                <span class="fw-semibold">First Name:</span> ${user.first_name ?? '-'}
            </div>
            <div class="list-group-item">
                <span class="fw-semibold">Last Name:</span> ${user.last_name ?? '-'}
            </div>
            <div class="list-group-item">
                <span class="fw-semibold">Username:</span>
                ${user.username ? `<a href="https://t.me/${user.username}" target="_blank">@${user.username}</a>` : '<span class="text-muted">-</span>'}
            </div>
            <div class="list-group-item">
                <span class="fw-semibold">Telegram ID:</span> ${user.id}
            </div>
        </div>
    `;
    // Sembunyikan widget login
    document.getElementById('telegram-widget-box').style.display = "none";
}
</script>
@endsection
