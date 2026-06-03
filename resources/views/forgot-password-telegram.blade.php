<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password via Telegram</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow">
        <div class="card-body">
            <h3 class="mb-3 text-center">Lupa Password</h3>
            <p class="text-muted text-center">
                Masukkan email akun Anda. Kode OTP akan dikirim ke Telegram yang sudah terhubung.
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

            <form action="{{ route('telegram.password.sendOtp') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Email Akun</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Kirim OTP ke Telegram
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="/">Kembali ke Login</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>