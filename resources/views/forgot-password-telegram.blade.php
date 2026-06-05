<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Lupa Password via Telegram</title>
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
            --glass-button-opacity: 0.30;
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

        /* Background soft pink + bokeh/glow ungu */
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
                linear-gradient(180deg,
                    rgba(253, 247, 250, var(--pink-opacity)) 0%,
                    rgba(250, 238, 245, var(--pink-opacity-strong)) 55%,
                    rgba(248, 234, 242, var(--pink-opacity-strong)) 100%);
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

        .forgot-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .forgot-card {
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

        .forgot-card::before {
            content: "";
            position: absolute;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            background:
                linear-gradient(135deg,
                    rgba(255, 255, 255, 0.55) 0%,
                    rgba(255, 255, 255, 0.16) 42%,
                    rgba(255, 255, 255, 0.05) 100%);
        }

        .forgot-card::after {
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

        .forgot-content {
            position: relative;
            z-index: 2;
        }

        .mail-illustration {
            width: 120px;
            height: 95px;
            margin: 0 auto 1.3rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .forgot-title {
            font-size: 2rem;
            font-weight: 800;
            color: #26334d;
            text-align: center;
            margin-bottom: 0.65rem;
        }

        .forgot-subtitle {
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

        .btn-send {
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

        .btn-send:hover {
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 14px 26px rgba(94, 114, 228, 0.32);
        }

        .back-login {
            display: inline-block;
            margin-top: 1.1rem;
            font-size: 0.9rem;
            font-weight: 700;
            color: rgba(52, 71, 103, 0.88);
            text-decoration: none;
        }

        .back-login:hover {
            color: #7b5ce6;
            text-decoration: none;
        }

        .alert {
            border-radius: 14px;
            font-size: 0.9rem;
        }

        @media (max-width: 576px) {
            .forgot-card {
                padding: 2rem 1.5rem;
                border-radius: 24px;
            }

            .forgot-title {
                font-size: 1.65rem;
            }
        }
    </style>
</head>

<body>
    <div class="forgot-wrapper">
        <div class="forgot-card">
            <div class="forgot-content">

                <div class="mail-illustration">
                    <svg viewBox="0 0 240 170" width="132" height="96" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <!-- Mailbox post -->
                        <path d="M169 120V158" stroke="#26334D" stroke-width="5" stroke-linecap="round" />
                        <path d="M188 120V145" stroke="#26334D" stroke-width="5" stroke-linecap="round" />

                        <!-- Bottom/open tray -->
                        <path d="M45 108H220C209 126 191 134 168 134H62C49 134 39 121 45 108Z" fill="#FFFFFF"
                            fill-opacity="0.58" stroke="#26334D" stroke-width="5" stroke-linejoin="round" />

                        <!-- Mailbox body -->
                        <path d="M76 35H164C194 35 218 59 218 89V112H76V35Z" fill="#FFFFFF" fill-opacity="0.55"
                            stroke="#26334D" stroke-width="5" stroke-linejoin="round" />

                        <!-- Dark inner opening -->
                        <path d="M78 35C58 36 42 53 42 74V112H88V74C88 54 84 42 78 35Z" fill="#111111" stroke="#26334D"
                            stroke-width="5" stroke-linejoin="round" />

                        <!-- Rolled mail on left -->
                        <path d="M43 66H20C12 66 7 60 7 53C7 46 13 40 20 40H43" fill="#FFFFFF" fill-opacity="0.65"
                            stroke="#26334D" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M21 41C28 41 33 46 33 53C33 60 28 65 21 65" stroke="#26334D" stroke-width="4"
                            stroke-linecap="round" />
                        <path d="M20 48C23 48 25 50 25 53C25 56 23 58 20 58" stroke="#26334D" stroke-width="3"
                            stroke-linecap="round" />

                        <!-- Envelope/mail in front -->
                        <rect x="55" y="59" width="93" height="58" rx="3" fill="#FFFFFF"
                            fill-opacity="0.82" stroke="#26334D" stroke-width="5" />

                        <path d="M58 62L101.5 96L145 62" stroke="#26334D" stroke-width="5" stroke-linecap="round"
                            stroke-linejoin="round" />

                        <path d="M58 115L89 88" stroke="#26334D" stroke-width="5" stroke-linecap="round"
                            stroke-linejoin="round" />

                        <path d="M145 115L114 88" stroke="#26334D" stroke-width="5" stroke-linecap="round"
                            stroke-linejoin="round" />

                        <!-- Front curve detail -->
                        <path d="M88 35C103 43 111 59 111 83V112" stroke="#26334D" stroke-width="5"
                            stroke-linecap="round" />
                    </svg>
                </div>

                <h3 class="forgot-title">Forgot password?</h3>

                <p class="forgot-subtitle">
                    Enter your email account. OTP code will be sent to your connected Telegram.
                </p>

                @if ($errors->any())
                    <div class="alert alert-danger mb-3">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success mb-3">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('telegram.password.sendOtp') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Email Account</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter your email"
                            value="{{ old('email') }}" required>
                    </div>

                    <button type="submit" class="btn btn-send w-100">
                        Send OTP to Telegram
                    </button>
                </form>

                <div class="text-center">
                    <a href="/" class="back-login">Back to Login</a>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
