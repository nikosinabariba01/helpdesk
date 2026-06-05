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
            width: 125px;
            height: 100px;
            margin: 0 auto 1.3rem;
            display: flex;
            align-items: center;
            justify-content: center;
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
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" id="send" width="120" height="120" aria-label="Send mail illustration">
                        <path d="M15.744 7.91a.492.492 0 0 1 .567-.422c1.405.206 2.931.464 4.536.765a.5.5 0 0 1-.092.991.486.486 0 0 1-.092-.009 114.06 114.06 0 0 0-4.5-.758.5.5 0 0 1-.419-.567Zm-2.144.072a.5.5 0 1 0-.5-.5.5.5 0 0 0 .5.5Zm24.68 16.736a.5.5 0 1 0 .5.5.5.5 0 0 0-.5-.5Zm0 3.242a.5.5 0 1 0 .5.5.5.5 0 0 0-.5-.5Zm-.5 3.242a.5.5 0 1 0 .5.5.5.5 0 0 0-.5-.502Zm-5.188-12.457-6.233 3.371a2.292 2.292 0 0 1-2.7-.4l-6.845-6.874a.5.5 0 0 0-.709.706l6.845 6.874a3.3 3.3 0 0 0 3.884.578l6.234-3.371a.5.5 0 1 0-.476-.88Zm12.008-.218-2.267.988 1.315.72a.5.5 0 0 1-.215.938c-.088 0-1.47.071-2.725.071a13.292 13.292 0 0 1-1.68-.076.5.5 0 1 1 .148-.989 18.243 18.243 0 0 0 2.389.056l-1.374-.752a.5.5 0 0 1 .48-.877l.552.3 1.38-.6a38.3 38.3 0 0 1-3.779-1.172 42.841 42.841 0 0 1-2.793 3.384c.007.223.009.443.013.664l3.543.43a.5.5 0 0 1-.059 1h-.061l-3.416-.415a63 63 0 0 1-.814 9.672A1.431 1.431 0 0 1 33.6 33.04a114.851 114.851 0 0 1-21.044-5.334 1.428 1.428 0 0 1-.841-1.812 28.408 28.408 0 0 0 .869-7.433L8.236 17.44a.5.5 0 0 1 .229-.974l4.132.971v-.335a36.78 36.78 0 0 1-2.922-5.225c-.7-.105-2.554-.387-4.089-.642l2.188 1.65a.5.5 0 0 1-.226.893l-.919.141 2.347 1.207a.5.5 0 1 1-.457.889L4.8 14.1a.5.5 0 0 1 .153-.939l1.272-.194L3.3 10.76a.5.5 0 0 1 .42-.885c1.052.257 6.328 1.046 6.382 1.054a.5.5 0 0 1 .386.3 31.883 31.883 0 0 0 2.1 3.99c-.027-1.51-.084-2.784-.123-3.522a1.425 1.425 0 0 1 .453-1.121 1.41 1.41 0 0 1 1.143-.369 167.429 167.429 0 0 1 20.509 4.3 1.427 1.427 0 0 1 1.03 1.152 37.76 37.76 0 0 1 .362 3.428 27.214 27.214 0 0 0 2.233-2.82.5.5 0 0 1 .6-.208 24.623 24.623 0 0 0 5.583 1.51.468.468 0 0 1 .505.39.5.5 0 0 1-.283.568Zm-9.988-2.714a.424.424 0 0 0-.309-.344A166.6 166.6 0 0 0 13.932 11.2a.335.335 0 0 0-.054 0 .424.424 0 0 0-.418.449c.167 3.178.424 10.958-.8 14.57a.426.426 0 0 0 .241.554 113.742 113.742 0 0 0 20.853 5.284.435.435 0 0 0 .5-.352 54.357 54.357 0 0 0 .359-15.892ZM20.436 36.6a23.23 23.23 0 0 1-8.307 3.427.5.5 0 0 0 .068.995.56.56 0 0 0 .069-.005A23.857 23.857 0 0 0 21 37.422a.5.5 0 1 0-.563-.826Zm-5.272-2.545a.5.5 0 0 0-.816-.577A18.741 18.741 0 0 1 8.123 38.6a.5.5 0 1 0 .455.89 19.358 19.358 0 0 0 6.586-5.439ZM11.2 30.288a.5.5 0 0 0-.952-.307c0 .016-.569 1.634-3.323 4.087a.5.5 0 1 0 .665.747c2.995-2.665 3.589-4.453 3.61-4.527Z" fill="currentColor"></path>
                    </svg>
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