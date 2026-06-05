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
        .forgot-password-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-top: -2px;
            margin-bottom: 24px;
        }

        .forgot-password-wrapper a {
            font-size: 14px;
            font-weight: 700;
            color: #344767;
            text-decoration: none;
        }

        .forgot-password-wrapper a:hover {
            color: #111827;
            text-decoration: none;
        }

        .register-text {
            font-size: 15px;
            font-weight: 700;
            color: #344767;
            margin-bottom: 14px;
        }

        .register-btn {
            width: 100%;
            height: 50px;
            border-radius: 8px;
            border: 1px solid #d2d6da;
            background-color: #ffffff;
            color: #344767;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .register-btn:hover {
            background-color: #f8f9fa;
            color: #111827;
            text-decoration: none;
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
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                            <div class="card card-plain">

                                @if (session('success'))
                                    <div class="alert alert-success mt-1" role="alert">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if ($errors->has('email'))
                                    <div class="alert alert-danger mt-1" role="alert">
                                        <strong>Warning!</strong> {{ $errors->first('email') }}
                                    </div>
                                @endif

                                @if ($errors->has('password'))
                                    <div class="alert alert-danger mt-1" role="alert">
                                        <strong>Warning!</strong> {{ $errors->first('password') }}
                                    </div>
                                @endif

                                <div class="card-header pb-0 text-start">
                                    <h4 class="font-weight-bolder">Sign In</h4>
                                    <p class="mb-0">Enter your email and password to sign in</p>
                                </div>

                                <div class="card-body">
                                    <form action="" method="POST">
                                        @csrf

                                        <div class="mb-3">
                                            <input type="email" name="email" class="form-control form-control-lg"
                                                placeholder="Email" aria-label="Email" value="{{ old('email') }}" />
                                        </div>

                                        <div class="mb-2">
                                            <input type="password" name="password" class="form-control form-control-lg"
                                                placeholder="Password" aria-label="Password" />
                                        </div>

                                        <div class="forgot-password-wrapper">
                                            <a href="{{ route('telegram.password.request') }}">
                                                Forgot password?
                                            </a>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" name="submit"
                                                class="btn btn-lg btn-primary w-100 mt-2 mb-0">
                                                Sign in
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <div class="card-footer text-center pt-0 px-lg-4 px-3">
                                    <p class="register-text">
                                        Don't have an account?
                                    </p>

                                    <a href="register" class="register-btn">
                                        Register
                                    </a>
                                </div>

                            </div>
                        </div>

                        <div
                            class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                            <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden"
                                style="
                                    background-image: url('style/assets/img/teraskos.png');
                                    background-size: cover;
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
