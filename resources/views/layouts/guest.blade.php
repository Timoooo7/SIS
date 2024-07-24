<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 bg-gray-100"
        style="background: url('/images/welcome_background.png'); background-size: cover;">
        <div>
            <a href="/" class="text-center">
                <x-application-logo class="fill-current text-gray-500 mx-auto" />
            </a>
            <h3 class="text-primary-emphasis h4 fw-bold mx-0"> <span class="text-primary pe-0">S</span>EEO <span
                    class="text-primary pe-0">I</span>nformation
                <span class="text-primary pe-0">S</span>ystem
            </h3>
        </div>

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

        <div
            class="sm:max-w-md mx-2 my-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg border-top border-primary-subtle">
            {{ $slot }}

            <div class="d-flex justify-content-between align-items-center py-4">
                <div class="border w-25"></div>
                <div class="f-6">or Login with</div>
                <div class="border w-25"></div>
            </div>

            <div class="row">
                <div class="col ">
                    <a href="{{ route('google.auth') }}" class="btn btn-outline-primary mx-20">Google Account</a>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    // Show Password
    function show_password(input_id) {
        var password = document.getElementById(input_id);
        if (password.type === "password") {
            password.type = "text";
        } else {
            password.type = "password";
        }
    }
</script>

</html>
