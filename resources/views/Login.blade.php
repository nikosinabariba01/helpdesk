<!--
=========================================================
* Argon Dashboard 2 - v2.0.4
=========================================================
-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <link rel="apple-touch-icon" sizes="76x76" href="style/assets/img/apple-icon.png" />
    <link rel="icon" type="image/png" href="{{ asset('style/assets/img/favicon.png') }}" />

    <title>Service IT</title>

    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />

    <!-- Nucleo Icons -->
    <link rel="stylesheet" href="{{ asset('style/assets/css/nucleo-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/assets/css/nucleo-svg.css') }}" />

    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('style/assets/css/argon-dashboard.css') }}" rel="stylesheet" />

    <style>
        html,
        body {
            min-height: 100%;
        }

        body {
            position: relative;
            overflow-x: hidden;
            background-color: #fcf4f8;
        }

        /* Background soft pink + bokeh/glow ungu */
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -2;
            background:
                radial-gradient(circle at 14% 23%, rgba(140, 82, 255, 0.22) 0 6px, transparent 14px),
                radial-gradient(circle at 21% 76%, rgba(140, 82, 255, 0.18) 0 7px, transparent 16px),
                radial-gradient(circle at 29% 12%, rgba(140, 82, 255, 0.20) 0 6px, transparent 14px),
                radial-gradient(circle at 33% 41%, rgba(140, 82, 255, 0.16) 0 7px, transparent 15px),
                radial-gradient(circle at 41% 18%, rgba(140, 82, 255, 0.12) 0 5px, transparent 13px),
                radial-gradient(circle at 47% 80%, rgba(140, 82, 255, 0.16) 0 7px, transparent 15px),
                radial-gradient(circle at 58% 31%, rgba(140, 82, 255, 0.14) 0 6px, transparent 14px),
                radial-gradient(circle at 66% 73%, rgba(140, 82, 255, 0.19) 0 7px, transparent 16px),
                radial-gradient(circle at 74% 12%, rgba(140, 82, 255, 0.18) 0 6px, transparent 14px),
                radial-gradient(circle at 83% 61%, rgba(140, 82, 255, 0.14) 0 7px, transparent 15px),
                radial-gradient(circle at 91% 22%, rgba(140, 82, 255, 0.17) 0 7px, transparent 16px),
                radial-gradient(circle at 88% 80%, rgba(140, 82, 255, 0.15) 0 6px, transparent 14px),
                linear-gradient(180deg, #fdf7fa 0%, #faeef5 55%, #f8eaf2 100%);
            filter: blur(2px);
        }

        body::after {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -1;
            background:
                radial-gradient(circle at 6% 44%, rgba(140, 82, 255, 0.08) 0 4px, transparent 11px),
                radial-gradient(circle at 24% 17%, rgba(140, 82, 255, 0.10) 0 4px, transparent 11px),
                radial-gradient(circle at 36% 89%, rgba(140, 82, 255, 0.09) 0 5px, transparent 12px),
                radial-gradient(circle at 52% 86%, rgba(140, 82, 255, 0.10) 0 4px, transparent 12px),
                radial-gradient(circle at 68% 18%, rgba(140, 82, 255, 0.08) 0 4px, transparent 10px),
                radial-gradient(circle at 79% 38%, rgba(140, 82, 255, 0.09) 0 5px, transparent 11px),
                radial-gradient(circle at 94% 71%, rgba(140, 82, 255, 0.10) 0 4px, transparent 11px),
                radial-gradient(circle at 50% 50%, rgba(255, 192, 203, 0.10) 0 220px, transparent 420px);
            filter: blur(4px);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.42);
            border: 1px solid rgba(255, 255, 255, 0.50);
            box-shadow: 0 10px 35px rgba(60, 72, 88, 0.13);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 24px;
            overflow: hidden;
        }

        .glass-card .card-header,
        .glass-card .card-body,
        .glass-card .card-footer {
            background: transparent !important;
        }

        .glass-card .card-header {
            padding: 2rem 2rem 1rem;
        }

        .glass-card .card-body {
            padding: 1rem 2rem 1.5rem;
        }

        .glass-card .card-footer {
            padding: 0 2rem 2rem;
        }

        .glass-card .form-control {
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, 0.08);
            background: rgba(255, 255, 255, 0.82);
            box-shadow: none;
        }

        .glass-card .form-control:focus {
            border-color: #7b5ce6;
            box-shadow: 0 0 0 0.15rem rgba(123, 92, 230, 0.14);
            background: rgba(255, 255, 255, 0.95);
        }

        .forgot-password-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-top: 2px;
            margin-bottom: 1.5rem;
        }

        .forgot-password-wrapper a {
            font-size: 14px;
            font-weight: 600;
            color: #344767;
            text-decoration: none;
        }

        .forgot-password-wrapper a:hover {
            color: #111827;
            text-decoration: none;
        }

        .signin-btn {
            border-radius: 12px;
            padding: 0.85rem 1rem;
            font-size: 1rem;
            font-weight: 600;
        }

        .register-text {
            font-size: 15px;
            font-weight: 600;
            color: #344767;
            margin-bottom: 14px;
        }

        .register-btn {
            width: 100%;
            height: 50px;
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.75);
            color: #344767;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
        }

        .register-btn:hover {
            background: rgba(255, 255, 255, 0.95);
            color: #111827;
            text-decoration: none;
            transform: translateY(-1px);
        }

        .alert {
            border-radius: 12px;
        }

        @media (max-width: 991.98px) {
            .glass-card {
                margin-top: 2rem;
                margin-bottom: 2rem;
            }
        }
    </style>
</head>

<body class="">
    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                <!-- Navbar -->
                <!-- End Navbar -->
            </div>
        </div>
    </div>

    <main class="main-content mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">

                        <!-- Login Form -->
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column justify-content-center mx-lg-0 mx-auto">
                            <div class="card glass-card">

                                @if (session('success'))
                                    <div class="alert alert-success mt-3 mx-3 mb-0" role="alert">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if ($errors->has('email'))
                                    <div class="alert alert-danger mt-3 mx-3 mb-0" role="alert">
                                        <strong>Warning!</strong> {{ $errors->first('email') }}
                                    </div>
                                @endif

                                @if ($errors->has('password'))
                                    <div class="alert alert-danger mt-3 mx-3 mb-0" role="alert">
                                        <strong>Warning!</strong> {{ $errors->first('password') }}
                                    </div>
                                @endif

                                <div class="card-header pb-0 text-start">
                                    <h4 class="font-weight-bolder mb-1">Sign In</h4>
                                    <p class="mb-0">Enter your email and password to sign in</p>
                                </div>

                                <div class="card-body">
                                    <form action="" method="POST">
                                        @csrf

                                        <div class="mb-3">
                                            <input
                                                type="email"
                                                name="email"
                                                class="form-control form-control-lg"
                                                placeholder="Email"
                                                aria-label="Email"
                                                value="{{ old('email') }}" />
                                        </div>

                                        <div class="mb-2">
                                            <input
                                                type="password"
                                                name="password"
                                                class="form-control form-control-lg"
                                                placeholder="Password"
                                                aria-label="Password" />
                                        </div>

                                        <div class="forgot-password-wrapper">
                                            <a href="{{ route('telegram.password.request') }}">
                                                Forgot password?
                                            </a>
                                        </div>

                                        <div class="text-center">
                                            <button
                                                type="submit"
                                                name="submit"
                                                class="btn btn-primary btn-lg w-100 mt-2 mb-0 signin-btn">
                                                Sign in
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <div class="card-footer text-center">
                                    <p class="register-text mb-3">
                                        Don't have an account?
                                    </p>

                                    <a href="register" class="register-btn">
                                        Register
                                    </a>
                                </div>

                            </div>
                        </div>

                        <!-- Right Image Section -->
                        <div
                            class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                            <div
                                class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden"
                                style="
                                    background-image: url('style/assets/img/teraskos.png');
                                    background-size: cover;
                                    background-position: center;
                                ">
                                <span class="mask bg-gradient-primary opacity-6"></span>

                                <h3 class="mt-5 text-white font-weight-bolder position-relative">
                                    KOST TENGGER 74
                                </h3>

                                <p class="text-white position-relative">
                                    Jl. Tengger II No.74, Gajahmungkur Kota Semarang
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Core JS Files -->
    <script src="{{ asset('style/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('style/assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('style/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('style/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>

    <script>
        var win = navigator.platform.indexOf("Win") > -1;

        if (win && document.querySelector("#sidenav-scrollbar")) {
            var options = {
                damping: "0.5",
            };

            Scrollbar.init(document.querySelector("#sidenav-scrollbar"), options);
        }
    </script>

    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <!-- Argon Dashboard JS -->
    <script src="{{ asset('style/assets/js/argon-dashboard.min.js') }}"></script>
</body>

</html>