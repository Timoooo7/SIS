<?php $auth_user = Auth::user(); ?>

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
    style="overflow-x: hidden;background-position: center; min-height: 100vh; background: url('/images/welcome_background.png');">

    <!-- Page Content -->
    <main>
        <div class="container mt-5">
            <div class="row g-4 justify-content center">
                <div class="col-12 col-lg-7 order-lg-1 order-2">
                    <div class="row mt-lg-5">
                        <div class="col-12">
                            <div class="h3 text-uppercase mx-3 text-primary-emphasis">{{ 'Blaterian' }}</div>
                            <div class="mx-3 scrolling-text-container border-0">
                                <span
                                    class="scrolling-text fs-5">{{ 'by Soedirman Engineering Entrepreneurship Organization' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-lg-5 mt-4">
                        <div class="col-12">
                            <div class="h1 text-dark mx-3">{{ 'Set up your mind to be an entrepreneur!' }}</div>
                        </div>
                    </div>
                    <div class="row mt-lg-3 mt-2">
                        <div class="col-12">
                            <p style="text-align:justify;" class="mx-3 fs-5">
                                {{ 'Established in 2020 to develop students` creativity and experience in entrepreneurship. The desire to have an impact on the surrounding environment gave birth to the ' }}
                                <span class="text-primary-emphasis fw-bold">{{ 'Blaterian' }}</span>
                                {{ ' brand by bringing local wisdom values ​​from where we come from. Located on the border of Purbalingga district, its name is Blater Village.' }}
                            </p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 d-flex">
                            @if ($auth_user)
                                <a href="{{ route('shop') }}"
                                    class="btn btn-light border-0 ms-auto me-{{ $auth_user->roles_id !== null ? '4' : 'auto' }} text-decoration-none d-flex px-4 shadow">
                                    <i class="bi bi-shop-window me-2 pe-2 border-end border-secondary fs-4"></i>
                                    <span class="h5 my-auto">{{ 'Shop' }}</span>
                                </a>
                                @if ($auth_user->roles_id !== null)
                                    <a href="{{ route('dashboard') }}"
                                        class="btn btn-light border-0 me-auto text-decoration-none d-flex px-4 shadow">
                                        <i class="bi bi-building me-2 pe-2 border-end border-secondary fs-4"></i>
                                        <span class="h5 my-auto">{{ 'Dashboard' }}</span>
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}"
                                    class="btn btn-light border-0 ms-auto me-4 text-decoration-none d-flex px-4 shadow">
                                    <i class="bi bi-door-open me-2 pe-2 border-end border-secondary fs-4"></i>
                                    <span class="h5 my-auto">{{ 'Login' }}</span>
                                </a>
                                <a href="{{ route('register') }}"
                                    class="btn btn-light border-0 me-auto text-decoration-none d-flex px-4 shadow">
                                    <i class="bi bi-person-add me-2 pe-2 border-end border-secondary fs-4"></i>
                                    <span class="h5 my-auto">{{ 'Register' }}</span>
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="row my-3 gx-3 px-3 justify-content-end text-end">
                        <div class="col-lg-auto col-12">
                            <a href="https://www.instagram.com/blaterian.id" target="_blank" rel="noopener noreferrer"
                                class="text-nowrap text-decoration-none text-primary-emphasis"><i
                                    class="bi bi-instagram me-2"></i>{{ 'blaterian.id' }}
                            </a>
                        </div>
                        <div class="col-lg-auto col-12">
                            <a href="https://www.instagram.com/seeo_ftunsoed" target="_blank" rel="noopener noreferrer"
                                class="text-nowrap text-decoration-none text-primary-emphasis"><i
                                    class="bi bi-instagram me-2"></i>{{ 'seeo_ftunsoed' }}
                            </a>
                        </div>
                        <div class="col-lg-auto col-12">
                            <a href="https://www.tiktok.com/@seeoftunsoed" target="_blank" rel="noopener noreferrer"
                                class="text-nowrap text-decoration-none text-primary-emphasis"><i
                                    class="bi bi-tiktok me-2"></i>{{ '@seeoftunsoed' }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-5 order-lg-2 order-1">
                    <div class="mx-3 h-100 d-flex">
                        <img src="storage/images/apps/welcome_image.png" alt="image" class="my-auto"
                            style="width:100%; height:auto; max-height:100%;">
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
