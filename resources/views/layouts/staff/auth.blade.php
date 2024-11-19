<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="height:100%;">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Icon Logo -->
    <link rel="icon" href="/images/logo.png" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased"
    style="overflow-x: hidden; height:100%; background: url('/images/welcome_background.png'); background-size: cover;">
    <?php
    // Error validation trigger toast
    if (!empty($errors->all())) {
        $err_messages = '';
        foreach ($errors->all() as $err) {
            $err_messages .= ' ' . $err;
        }
        session()->put('notif', ['type' => 'warning', 'message' => $err_messages]);
    }
    ?>

    {{-- Notification Toast --}}
    @if (session()->has('notif'))
        <?php
        if (session()->get('notif')['type'] == 'danger') {
            $type = 'danger';
        } elseif (session()->get('notif')['type'] == 'warning') {
            $type = 'warning';
        } else {
            $type = 'primary';
        }
        ?>
        <div class="toast-container top-15 start-50 translate-middle-x p-3">
            <div class="toast bg-{{ $type }}-subtle border border-top-0 border-start-0 border-end-0 border-{{ $type }} "
                role="alert" aria-live="assertive" aria-atomic="true" id="toast_notification">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session()->get('notif')['message'] }}
                    </div>
                    <button type="button" class="ms-auto btn-close me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    <main style="height:100%;">
        <div class="container" style="height:100%;">
            <div class="row justify-content-center" style="height:100%;">
                <div class="col-6 d-none d-lg-block" style="margin-top:100px;">
                    <a href="/" class="text-center">
                        <img src="{{ url('/images/logo.png') }}" alt="SEEO Logo" width="300px" height="300px">
                    </a>
                    <h1 class="text-primary-emphasis h1 fw-bold mx-0"> BLATERIAN <i class="fs-5">by</i> </h1>
                    <h3 class="fw-light text-dark d-none d-lg-inline">
                        Soedirman
                        Engineering
                        Entrepeneurship Organization</h3>
                </div>
                <div class="col-lg-6 col-md-10 col-12 bg-secondary bg-opacity-25 pt-lg-5 pb-5 pb-lg-0"
                    style="height:100%;">
                    <div class="row justify-content-center">
                        <div class="col-lg-10 pt-lg-5">
                            <div class="row justify-content-center d-lg-none pt-5">
                                <div class="col text-center">
                                    <a href="/" class="">
                                        <img src="{{ url('/images/logo.png') }}" alt="SEEO Logo" width="40%"
                                            height="auto">
                                    </a>
                                </div>
                            </div>
                            <div class="row justify-content-center d-lg-none">
                                <div class="col-auto">
                                    <h1 class="text-primary-emphasis h1 fw-bold mx-0"> BLATERIAN <i
                                            class="fs-5">by</i>
                                    </h1>
                                </div>
                            </div>
                            <div class="row d-lg-none">
                                <div class="col-auto">
                                    <h3 class="fw-light text-dark text-center mx-3">
                                        Soedirman
                                        Engineering
                                        Entrepeneurship Organization</h3>
                                </div>
                            </div>
                            <div class="card shadow mx-3 px-md-3 px-0 mt-lg-5 mb-lg-0 mt-3 mb-5">
                                <div class="card-body">
                                    {{-- Auth Content --}}
                                    {{ $slot }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <?php session()->pull('notif'); ?>
    <script>
        // Show Password
        function show_password(input_id, icon_id) {
            var password = document.getElementById(input_id);
            var password_icon = document.getElementById(icon_id);
            if (password.type === "password") {
                password.type = "text";
                password_icon.classList.remove('bi-eye-slash-fill');
                password_icon.classList.add('bi-eye-fill');
            } else {
                password.type = "password";
                password_icon.classList.remove('bi-eye-fill');
                password_icon.classList.add('bi-eye-slash-fill');
            }
        }

        // Toast Script
        document.addEventListener("DOMContentLoaded", function() {
            var element = document.getElementById("toast_notification");

            // Create toast instance
            var myToast = new bootstrap.Toast(element);

            // Showing toast
            myToast.show();
        });

        // Tooltip
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    </script>
</body>

</html>
