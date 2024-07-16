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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen" style="background: url('/images/welcome_background.png'); background-size: cover;">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class=" border-0 bg-transparent">
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

                <div class="max-w-7xl mx-auto mt-3 pt-6 pb-0 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif


        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
    <?php session()->pull('notif'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
