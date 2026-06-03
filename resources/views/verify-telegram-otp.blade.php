<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi OTP Telegram</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow">
        <div class="card-body">
            <h3 class="mb-3 text-center">Verifikasi OTP</h3>
            <p class="text-muted text-center">
                Masukkan kode OTP 6 digit yang dikirim ke Telegram Anda.
            </p>

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('telegram.password.verifyOtp') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Kode OTP</label>
                    <input type="text" name="otp" class="form-control" maxlength="6" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Verifikasi OTP
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('telegram.password.request') }}">Kirim OTP ulang</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>