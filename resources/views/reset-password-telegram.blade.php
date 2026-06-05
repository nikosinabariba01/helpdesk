<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password Baru</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />

    <style>
        :root {
            /* ATUR BACKGROUND DI SINI */
            --pink-opacity: 0.18;
            --pink-opacity-strong: 0.25;
            --purple-bokeh-opacity: 0.14;

            /* ATUR GLASS CARD DI SINI */
            --glass-card-opacity: 0.22;
            --glass-card-border-opacity: 0.55;
            --glass-input-opacity: 0.38;
        }

        html,
        body {
            min-height: 100%;
        }

        body {
            position: relative;
            overflow-x: hidden;
            font-family: "Open Sans", sans-serif;
            background-color: rgba(252, 244, 248, var(--pink-opacity));
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -2;
            background:
                radial-gradient(circle at 14% 23%, rgba(140, 82, 255, var(--purple-bokeh-opacity)) 0 6px, transparent 14px),
                radial-gradient(circle at 21% 76%, rgba(140, 82, 255, var(--purple-bokeh-opacity)) 0 7px, transparent 16px),
                radial-gradient(circle at 29% 12%, rgba(140, 82, 255, var(--purple-bokeh-opacity)) 0 6px, transparent 14px),
                radial-gradient(circle at 33% 41%, rgba(140, 82, 255, var(--purple-bokeh-opacity)) 0 7px, transparent 15px),
                radial-gradient(circle at 41% 18%, rgba(140, 82, 255, 0.10) 0 5px, transparent 13px),
                radial-gradient(circle at 47% 80%, rgba(140, 82, 255, var(--purple-bokeh-opacity)) 0 7px, transparent 15px),
                radial-gradient(circle at 58% 31%, rgba(140, 82, 255, 0.12) 0 6px, transparent 14px),
                radial-gradient(circle at 66% 73%, rgba(140, 82, 255, var(--purple-bokeh-opacity)) 0 7px, transparent 16px),
                radial-gradient(circle at 74% 12%, rgba(140, 82, 255, var(--purple-bokeh-opacity)) 0 6px, transparent 14px),
                radial-gradient(circle at 83% 61%, rgba(140, 82, 255, 0.12) 0 7px, transparent 15px),
                radial-gradient(circle at 91% 22%, rgba(140, 82, 255, var(--purple-bokeh-opacity)) 0 7px, transparent 16px),
                radial-gradient(circle at 88% 80%, rgba(140, 82, 255, 0.13) 0 6px, transparent 14px),
                linear-gradient(
                    180deg,
                    rgba(253, 247, 250, var(--pink-opacity)) 0%,
                    rgba(250, 238, 245, var(--pink-opacity-strong)) 55%,
                    rgba(248, 234, 242, var(--pink-opacity-strong)) 100%
                );
            filter: blur(2px);
        }

        body::after {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -1;
            background:
                radial-gradient(circle at 6% 44%, rgba(140, 82, 255, 0.08) 0 4px, transparent 11px),
                radial-gradient(circle at 24% 17%, rgba(140, 82, 255, 0.09) 0 4px, transparent 11px),
                radial-gradient(circle at 36% 89%, rgba(140, 82, 255, 0.08) 0 5px, transparent 12px),
                radial-gradient(circle at 52% 86%, rgba(140, 82, 255, 0.09) 0 4px, transparent 12px),
                radial-gradient(circle at 68% 18%, rgba(140, 82, 255, 0.07) 0 4px, transparent 10px),
                radial-gradient(circle at 79% 38%, rgba(140, 82, 255, 0.08) 0 5px, transparent 11px),
                radial-gradient(circle at 94% 71%, rgba(140, 82, 255, 0.09) 0 4px, transparent 11px),
                radial-gradient(circle at 50% 50%, rgba(255, 192, 203, 0.08) 0 220px, transparent 420px);
            filter: blur(4px);
        }

        .reset-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .reset-card {
            position: relative;
            width: 100%;
            max-width: 470px;
            overflow: hidden;
            border-radius: 28px;
            padding: 2.4rem 2.2rem;
            background: rgba(255, 255, 255, var(--glass-card-opacity));
            border: 1px solid rgba(255, 255, 255, var(--glass-card-border-opacity));
            box-shadow:
                0 22px 48px rgba(60, 72, 88, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.58);
            backdrop-filter: blur(24px) saturate(165%);
            -webkit-backdrop-filter: blur(24px) saturate(165%);
        }

        .reset-card::before {
            content: "";
            position: absolute;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            background:
                linear-gradient(
                    135deg,
                    rgba(255, 255, 255, 0.55) 0%,
                    rgba(255, 255, 255, 0.16) 42%,
                    rgba(255, 255, 255, 0.05) 100%
                );
        }

        .reset-card::after {
            content: "";
            position: absolute;
            width: 190px;
            height: 190px;
            top: -75px;
            right: -75px;
            z-index: 0;
            pointer-events: none;
            background: radial-gradient(circle, rgba(140, 82, 255, 0.22), transparent 68%);
        }

        .reset-content {
            position: relative;
            z-index: 2;
        }

        .reset-illustration {
            width: 125px;
            height: 100px;
            margin: 0 auto 1.3rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .reset-title {
            font-size: 2rem;
            font-weight: 800;
            color: #26334d;
            text-align: center;
            margin-bottom: 0.65rem;
        }

        .reset-subtitle {
            font-size: 0.95rem;
            line-height: 1.6;
            color: rgba(52, 71, 103, 0.72);
            text-align: center;
            margin-bottom: 1.8rem;
        }

        .form-label {
            font-size: 0.88rem;
            font-weight: 700;
            color: rgba(52, 71, 103, 0.92);
            margin-bottom: 0.55rem;
        }

        .form-control {
            height: 52px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.62);
            background: rgba(255, 255, 255, var(--glass-input-opacity));
            color: #344767;
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, 0.56),
                0 8px 18px rgba(60, 72, 88, 0.06);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .form-control::placeholder {
            color: rgba(52, 71, 103, 0.55);
        }

        .form-control:focus {
            border-color: rgba(123, 92, 230, 0.55);
            background: rgba(255, 255, 255, 0.52);
            box-shadow:
                0 0 0 0.15rem rgba(123, 92, 230, 0.13),
                inset 0 1px 0 rgba(255, 255, 255, 0.62);
        }

        .password-strength {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
            margin-top: 0.75rem;
        }

        .password-strength span {
            height: 4px;
            border-radius: 999px;
            background: rgba(123, 92, 230, 0.26);
        }

        .btn-save {
            height: 52px;
            border-radius: 14px;
            border: none;
            background: linear-gradient(135deg, #7b5ce6 0%, #5e72e4 100%);
            color: #ffffff;
            font-size: 1rem;
            font-weight: 700;
            box-shadow: 0 12px 22px rgba(94, 114, 228, 0.26);
            transition: all 0.2s ease-in-out;
        }

        .btn-save:hover {
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 14px 26px rgba(94, 114, 228, 0.32);
        }

        .alert {
            border-radius: 14px;
            font-size: 0.9rem;
        }

        @media (max-width: 576px) {
            .reset-card {
                padding: 2rem 1.5rem;
                border-radius: 24px;
            }

            .reset-title {
                font-size: 1.65rem;
            }
        }
    </style>
</head>

<body>
    <div class="reset-wrapper">
        <div class="reset-card">
            <div class="reset-content">

                <div class="reset-illustration">
                    <svg viewBox="0 0 190 150" width="125" height="100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M58 36C58 24.9543 66.9543 16 78 16H127C138.046 16 147 24.9543 147 36V80H58V36Z"
                            fill="rgba(255,255,255,0.48)" stroke="#26334D" stroke-width="5" />
                        <path d="M73 32H130C136.627 32 142 37.3726 142 44V86H62V44C62 37.3726 67.3726 32 74 32H73Z"
                            fill="rgba(255,255,255,0.68)" stroke="#26334D" stroke-width="5" />
                        <path d="M62 45L102 74L142 45" stroke="#26334D" stroke-width="5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M64 87H144" stroke="#26334D" stroke-width="5" stroke-linecap="round" />

                        <rect x="70" y="78" width="64" height="45" rx="12"
                            fill="rgba(255,255,255,0.62)" stroke="#26334D" stroke-width="5" />
                        <path d="M86 78V67C86 58.1634 93.1634 51 102 51C110.837 51 118 58.1634 118 67V78"
                            stroke="#26334D" stroke-width="5" stroke-linecap="round" />
                        <circle cx="102" cy="99" r="7" fill="rgba(123,92,230,0.30)" stroke="#26334D"
                            stroke-width="5" />
                        <path d="M102 106V113" stroke="#26334D" stroke-width="5" stroke-linecap="round" />

                        <path d="M42 60H66V94H42C32.6112 94 25 86.3888 25 77C25 67.6112 32.6112 60 42 60Z"
                            fill="rgba(255,255,255,0.55)" stroke="#26334D" stroke-width="5" />
                        <path d="M24 77H10" stroke="#26334D" stroke-width="5" stroke-linecap="round" />
                        <circle cx="43" cy="77" r="8" fill="rgba(123,92,230,0.22)" stroke="#26334D"
                            stroke-width="5" />
                    </svg>
                </div>

                <h3 class="reset-title">Buat Password Baru</h3>

                <p class="reset-subtitle">
                    Masukkan password baru untuk akun Anda.
                </p>

                @if($errors->any())
                    <div class="alert alert-danger mb-3">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success mb-3">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('telegram.password.reset') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="Masukkan password baru"
                            required>

                        <div class="password-strength">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            class="form-control"
                            placeholder="Ulangi password baru"
                            required>
                    </div>

                    <button type="submit" class="btn btn-save w-100">
                        Simpan Password Baru
                    </button>
                </form>

            </div>
        </div>
    </div>
</body>

</html>