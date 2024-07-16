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

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

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

        <div
            class="sm:max-w-md mx-2 mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg border-top border-primary-subtle">
            {{ $slot }}
        </div>
    </div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    // $(document).ready(function() {

    //     $("#show_password").change(function() {
    //         var password = document.getElementById('password');
    //         if ($(this).is(':checked')) {
    //             password.type = "text";
    //         } else {
    //             password.type = "password";
    //         }

    //     });

    // });

    // Show Password
    function show_password(input_id) {
        var password = document.getElementById('password');
        if (password.type === "password") {
            password.type = "text";
        } else {
            password.type = "password";
        }
    }
</script>

</html>
