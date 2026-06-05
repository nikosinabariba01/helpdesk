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

        .password-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-input-wrapper .form-control {
            padding-right: 45px;
        }

        .password-toggle-btn {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            cursor: pointer;
            color: rgba(52, 71, 103, 0.6);
            font-size: 1rem;
            padding: 0.5rem;
            transition: color 0.2s ease-in-out;
            z-index: 10;
        }

        .password-toggle-btn:hover {
            color: rgba(52, 71, 103, 0.9);
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
                    <svg viewBox="0 0 512 512" width="125" height="125" xmlns="http://www.w3.org/2000/svg" aria-label="Password reset security illustration">
                        <g id="Change_password">
                            <path d="M464.4326,147.54a9.8985,9.8985,0,0,0-17.56,9.1406,214.2638,214.2638,0,0,1-38.7686,251.42c-83.8564,83.8476-220.3154,83.874-304.207-.0088a9.8957,9.8957,0,0,0-16.8926,7.0049v56.9a9.8965,9.8965,0,0,0,19.793,0v-34.55A234.9509,234.9509,0,0,0,464.4326,147.54Z" fill="currentColor"/>
                            <path d="M103.8965,103.9022c83.8828-83.874,220.3418-83.8652,304.207-.0088a9.8906,9.8906,0,0,0,16.8926-6.9961v-56.9a9.8965,9.8965,0,0,0-19.793,0v34.55C313.0234-1.3556,176.0547,3.7509,89.9043,89.9012A233.9561,233.9561,0,0,0,47.5674,364.454a9.8985,9.8985,0,0,0,17.56-9.1406A214.2485,214.2485,0,0,1,103.8965,103.9022Z" fill="currentColor"/>
                            <path d="M126.4009,254.5555v109.44a27.08,27.08,0,0,0,27,27H358.5991a27.077,27.077,0,0,0,27-27v-109.44a27.0777,27.0777,0,0,0-27-27H153.4009A27.0805,27.0805,0,0,0,126.4009,254.5555ZM328,288.13a21.1465,21.1465,0,1,1-21.1465,21.1464A21.1667,21.1667,0,0,1,328,288.13Zm-72,0a21.1465,21.1465,0,1,1-21.1465,21.1464A21.1667,21.1667,0,0,1,256,288.13Zm-72,0a21.1465,21.1465,0,1,1-21.1465,21.1464A21.1667,21.1667,0,0,1,184,288.13Z" fill="currentColor"/>
                            <path d="M343.6533,207.756V171.7538a87.6533,87.6533,0,0,0-175.3066,0V207.756H188.14V171.7538a67.86,67.86,0,0,1,135.7208,0V207.756Z" fill="currentColor"/>
                        </g>
                    </svg>
                </div>
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
                        <div class="password-input-wrapper">
                            <input
                                type="password"
                                id="newPassword"
                                name="password"
                                class="form-control"
                                placeholder="Masukkan password baru"
                                required>
                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('newPassword', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>

                        <div class="password-strength">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <div class="password-input-wrapper">
                            <input
                                type="password"
                                id="confirmPassword"
                                name="password_confirmation"
                                class="form-control"
                                placeholder="Ulangi password baru"
                                required>
                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('confirmPassword', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-save w-100">
                        Simpan Password Baru
                    </button>
                </form>

            </div>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <script>
        function togglePasswordVisibility(fieldId, button) {
            const field = document.getElementById(fieldId);
            const icon = button.querySelector('i');

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>