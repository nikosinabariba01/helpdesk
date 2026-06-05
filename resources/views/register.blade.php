<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Register</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx"
        crossorigin="anonymous">

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
            margin: 0;
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

        .register-page {
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }

        .register-card {
            position: relative;
            overflow: hidden;
            width: 100%;
            max-width: 470px;
            border-radius: 28px;
            padding: 2.2rem;
            background: rgba(255, 255, 255, var(--glass-card-opacity));
            border: 1px solid rgba(255, 255, 255, var(--glass-card-border-opacity));
            box-shadow:
                0 22px 48px rgba(60, 72, 88, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.58);
            backdrop-filter: blur(24px) saturate(165%);
            -webkit-backdrop-filter: blur(24px) saturate(165%);
        }

        .register-card::before {
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

        .register-card::after {
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

        .register-content {
            position: relative;
            z-index: 2;
        }

        .register-title {
            font-size: 2rem;
            font-weight: 800;
            color: #26334d;
            margin-bottom: 0.45rem;
        }

        .register-subtitle {
            font-size: 0.95rem;
            line-height: 1.6;
            color: rgba(52, 71, 103, 0.72);
            margin-bottom: 1.7rem;
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

        .btn-register {
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

        .btn-register:hover {
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 14px 26px rgba(94, 114, 228, 0.32);
        }

        .signin-text {
            font-size: 0.92rem;
            font-weight: 600;
            color: rgba(52, 71, 103, 0.78);
            margin-top: 1.2rem;
            margin-bottom: 0;
        }

        .signin-link {
            color: #7b5ce6;
            font-weight: 800;
            text-decoration: none;
        }

        .signin-link:hover {
            color: #5e72e4;
            text-decoration: none;
        }

        .alert {
            border-radius: 14px;
            font-size: 0.9rem;
            padding: 0.9rem 1rem;
        }

        .alert ul {
            margin-bottom: 0;
            padding-left: 1.2rem;
        }

        .right-image-wrapper {
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
            height: 100%;
            padding: 1rem;
        }

        .right-image-card {
            position: relative;
            width: 100%;
            height: 100%;
            border-radius: 24px;
            overflow: hidden;
            background-image: url('style/assets/img/teraskos.png');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 3rem;
        }

        .right-image-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(94, 114, 228, 0.72), rgba(123, 92, 230, 0.58));
            z-index: 1;
        }

        .right-image-content {
            position: relative;
            z-index: 2;
            color: #ffffff;
        }

        .right-image-content h3 {
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: 0.75rem;
        }

        .right-image-content p {
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 0;
            opacity: 0.95;
        }

        @media (max-width: 991.98px) {
            .register-page {
                padding: 2rem 1rem;
            }

            .right-image-wrapper {
                display: none;
            }

            .register-card {
                max-width: 500px;
                margin: 0 auto;
            }
        }

        @media (max-width: 576px) {
            .register-card {
                padding: 2rem 1.5rem;
                border-radius: 24px;
            }

            .register-title {
                font-size: 1.7rem;
            }
        }
    </style>
</head>

<body>
    <main class="register-page">
        <div class="container min-vh-100">
            <div class="row min-vh-100 align-items-center">
                <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column justify-content-center mx-lg-0 mx-auto">
                    <div class="register-card">
                        <div class="register-content">

                            <div class="text-start">
                                <h1 class="register-title">Register</h1>
                                <p class="register-subtitle">
                                    Create your account to access Service IT.
                                </p>
                            </div>

                            @if($errors->any())
                                <div class="alert alert-danger mb-3">
                                    <ul>
                                        @foreach($errors->all() as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="/register" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        value="{{ Session::get('name') }}"
                                        class="form-control"
                                        placeholder="Enter your name">
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        value="{{ Session::get('email') }}"
                                        class="form-control"
                                        placeholder="Enter your email">
                                </div>

                                <div class="mb-4">
                                    <label for="password" class="form-label">Password</label>
                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        class="form-control"
                                        placeholder="Enter your password">
                                </div>

                                <button type="submit" name="submit" class="btn btn-register w-100">
                                    Register
                                </button>
                            </form>

                            <p class="signin-text text-center">
                                Already have an account?
                                <a href="/" class="signin-link">Sign in</a>
                            </p>

                        </div>
                    </div>
                </div>

                <div class="right-image-wrapper d-lg-flex d-none">
                    <div class="right-image-card">
                        <div class="right-image-content">
                            <h3>KOST TENGGER 74</h3>
                            <p>Jl. Tengger II No.74, Gajahmungkur Kota Semarang</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>