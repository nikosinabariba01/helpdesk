<!DOCTYPE html>
<html lang="en">
<head>
    <title>Profil Pengguna</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow">
                <div class="card-body">
                    <h3 class="mb-3">Profil Pengguna</h3>
                    
                    <table class="table">
                        <tr>
                            <th>Nama</th>
                            <td>{{ Auth::user()->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ Auth::user()->email }}</td>
                        </tr>
                        <tr>
                            <th>Telegram</th>
                            <td>
                                @if(Auth::user()->telegram_chat_id)
                                    <span class="badge bg-success">
                                        Terhubung (ID: {{ Auth::user()->telegram_chat_id }})
                                    </span>
                                @else
                                    <span class="badge bg-danger">Belum Terhubung</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    @if(!Auth::user()->telegram_chat_id)
                    <div class="mb-3">
                        <label class="form-label">Hubungkan Telegram:</label>
                        <!-- Widget Telegram -->
                        <script async src="https://telegram.org/js/telegram-widget.js?22"
                            data-telegram-login="kos74_bot"  
                            data-size="large"
                            data-userpic="false"
                            data-request-access="write"
                            data-auth-url="{{ url('telegram/auth') }}">
                        </script>
                    </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success mt-3">{{ session('success') }}</div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
