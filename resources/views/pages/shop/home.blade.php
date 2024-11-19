<?php

// This is temporary shop page for future development.
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="height:100%">

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

    <!-- Chart.Js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased"
    style="overflow-x: hidden;background-position: center; min-height: 100vh; background: #fff;">

    <!-- Page Content -->
    <main>
        <div class="position-fixed" style="bottom: 0; top:0; width:100%; height:100%;">
            <div class="translate-middle position-absolute top-50 start-50" style="font-size: 4rem">
                <div class="row">
                    <div class="col-12 col-lg-auto d-flex text-primary-emphasis">
                        <i class="bi bi-shop-window me-3"></i>
                        <div class="d-flex">
                            <span class="d-none d-lg-block">{{ 'On progress...' }}</span>
                            <span class="d-lg-none h1 my-auto">{{ 'Still on' }} <br> {{ 'progress...' }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 d-flex">
                        <a href="{{ route('intro') }}"
                            class="btn btn-primary mx-auto mt-3 h1 px-lg-5 px-2">{{ 'Back to Introduction' }}</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
