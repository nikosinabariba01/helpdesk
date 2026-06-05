<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Verifikasi OTP Telegram</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />

    <style>
        :root {
            --pink-opacity: 0.18;
            --pink-opacity-strong: 0.25;
            --purple-bokeh-opacity: 0.14;

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

        .verify-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .verify-card {
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

        .verify-card::before {
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

        .verify-card::after {
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

        .verify-content {
            position: relative;
            z-index: 2;
        }

        .verify-illustration {
            width: 145px;
            height: 115px;
            margin: 0 auto 1.3rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .verify-illustration-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .verify-title {
            font-size: 2rem;
            font-weight: 800;
            color: #26334d;
            text-align: center;
            margin-bottom: 0.65rem;
        }

        .verify-subtitle {
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
            margin-bottom: 0.75rem;
        }

        .otp-box-wrapper {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 10px;
            margin-bottom: 1.5rem;
        }

        .otp-box {
            width: 100%;
            height: 58px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.62);
            background: rgba(255, 255, 255, var(--glass-input-opacity));
            color: #26334d;
            font-size: 1.35rem;
            font-weight: 800;
            text-align: center;
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, 0.56),
                0 8px 18px rgba(60, 72, 88, 0.06);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            outline: none;
            transition: all 0.2s ease-in-out;
        }

        .otp-box:focus {
            border-color: rgba(123, 92, 230, 0.75);
            background: rgba(255, 255, 255, 0.55);
            box-shadow:
                0 0 0 0.15rem rgba(123, 92, 230, 0.13),
                inset 0 1px 0 rgba(255, 255, 255, 0.62);
            transform: translateY(-1px);
        }

        .btn-verify {
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

        .btn-verify:hover {
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 14px 26px rgba(94, 114, 228, 0.32);
        }

        .resend-link {
            display: inline-block;
            margin-top: 1.1rem;
            font-size: 0.9rem;
            font-weight: 700;
            color: rgba(52, 71, 103, 0.88);
            text-decoration: none;
        }

        .resend-link:hover {
            color: #7b5ce6;
            text-decoration: none;
        }

        .alert {
            border-radius: 14px;
            font-size: 0.9rem;
        }

        @media (max-width: 576px) {
            .verify-card {
                padding: 2rem 1.5rem;
                border-radius: 24px;
            }

            .verify-title {
                font-size: 1.65rem;
            }

            .verify-illustration {
                width: 130px;
                height: 100px;
            }

            .otp-box-wrapper {
                gap: 7px;
            }

            .otp-box {
                height: 50px;
                font-size: 1.1rem;
                border-radius: 12px;
            }
        }
    </style>
</head>

<body>
    <div class="verify-wrapper">
        <div class="verify-card">
            <div class="verify-content">

                <div class="verify-illustration">
                    <img
                        src="{{ asset('img/mailbox_telegram_aesthetic_vector.svg') }}"
                        alt="OTP Telegram Illustration"
                        class="verify-illustration-img">
                </div>

                <h3 class="verify-title">Verifikasi OTP</h3>

                <p class="verify-subtitle">
                    Masukkan kode OTP 6 digit yang dikirim ke Telegram Anda.
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

                <form action="{{ route('telegram.password.verifyOtp') }}" method="POST" id="otpForm">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label">Kode OTP</label>

                        <input type="hidden" name="otp" id="otpValue">

                        <div class="otp-box-wrapper">
                            <input type="text" class="otp-box" maxlength="1" inputmode="numeric" autocomplete="one-time-code" required>
                            <input type="text" class="otp-box" maxlength="1" inputmode="numeric" required>
                            <input type="text" class="otp-box" maxlength="1" inputmode="numeric" required>
                            <input type="text" class="otp-box" maxlength="1" inputmode="numeric" required>
                            <input type="text" class="otp-box" maxlength="1" inputmode="numeric" required>
                            <input type="text" class="otp-box" maxlength="1" inputmode="numeric" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-verify w-100">
                        Verifikasi OTP
                    </button>
                </form>

                <div class="text-center">
                    <a href="{{ route('telegram.password.request') }}" class="resend-link">
                        Kirim OTP ulang
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script>
        const otpBoxes = document.querySelectorAll('.otp-box');
        const otpValue = document.getElementById('otpValue');
        const otpForm = document.getElementById('otpForm');

        otpBoxes.forEach((box, index) => {
            box.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '');

                if (this.value && index < otpBoxes.length - 1) {
                    otpBoxes[index + 1].focus();
                }

                updateOtpValue();
            });

            box.addEventListener('keydown', function (event) {
                if (event.key === 'Backspace' && !this.value && index > 0) {
                    otpBoxes[index - 1].focus();
                }
            });

            box.addEventListener('paste', function (event) {
                event.preventDefault();

                const pasteData = (event.clipboardData || window.clipboardData)
                    .getData('text')
                    .replace(/[^0-9]/g, '')
                    .slice(0, 6);

                pasteData.split('').forEach((digit, i) => {
                    if (otpBoxes[i]) {
                        otpBoxes[i].value = digit;
                    }
                });

                const nextIndex = pasteData.length < 6 ? pasteData.length : 5;
                otpBoxes[nextIndex].focus();

                updateOtpValue();
            });
        });

        function updateOtpValue() {
            let otp = '';

            otpBoxes.forEach(box => {
                otp += box.value;
            });

            otpValue.value = otp;
        }

        otpForm.addEventListener('submit', function (event) {
            updateOtpValue();

            if (otpValue.value.length !== 6) {
                event.preventDefault();
                otpBoxes[0].focus();
            }
        });
    </script>
</body>

</html>