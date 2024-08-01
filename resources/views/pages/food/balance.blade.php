<?php
use Illuminate\Support\Carbon;
?>
<x-app-layout>
    <x-slot name="header">
        <nav aria-label="breadcrumb w-auto b-0" style="--bs-breadcrumb-divider: '>';">
            <ol class="breadcrumb">
                <li class="breadcrumb-item font-semibold text-xl text-gray-800 leading-tight" aria-current="page">
                    {{ $title }}</li>
            </ol>
        </nav>
    </x-slot>

    <div class="container py-3 px-10">
        <div class="row">
            {{-- Dashboard for medium and smaller screen  --}}
            <div id="carouselDashboard" class="carousel slide d-lg-none" data-bs-ride="carousel">
                <div class="carousel-inner p-3">
                    {{-- Balance carousel --}}
                    <div class="carousel-item active shadow-md">
                        <div class="card text-bg-primary">
                            <div class="card-body">
                                <div class="row m-0 p-0">
                                    <div class="col-auto">
                                        <div class="bi bi-wallet2 my-auto" style="font-size:300%"> </div>
                                    </div>
                                    <div class="col-1 d-flex">
                                        <div class="border-start border-secondary-emphasis h-100"></div>
                                    </div>
                                    <div class="col-auto">
                                        <p class="text-white mb-1 text-opacity-75 fs-6 fw-light ">Balance</p>
                                        <span class="fs-2">{{ format_currency($balance->balance, 'IDR') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Income carousel --}}
                    <div class="carousel-item shadow-md">
                        <div class="card text-bg-secondary">
                            <div class="card-body">
                                <div class="row m-0 p-0">
                                    <div class="col-auto">
                                        <div class="bi bi-box-arrow-in-down my-auto" style="font-size:300%"> </div>
                                    </div>
                                    <div class="col-1 d-flex">
                                        <div class="border-start border-secondary-emphasis h-100"></div>
                                    </div>
                                    <div class="col-auto">
                                        <p class="text-white mb-1 text-opacity-75 fs-6 fw-light ">Income</p>
                                        <span class="fs-2">{{ format_currency($balance->income, 'IDR') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Expense carousel --}}
                    <div class="carousel-item shadow-md">
                        <div class="card text-bg-secondary">
                            <div class="card-body">
                                <div class="row m-0 p-0">
                                    <div class="col-auto">
                                        <div class="bi bi-box-arrow-in-up my-auto" style="font-size:300%"> </div>
                                    </div>
                                    <div class="col-1 d-flex">
                                        <div class="border-start border-secondary-emphasis h-100"></div>
                                    </div>
                                    <div class="col-auto">
                                        <p class="text-white mb-1 text-opacity-75 fs-6 fw-light ">Expense</p>
                                        <span class="fs-2">{{ format_currency($balance->expense, 'IDR') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Dashboard for large and bigger screen  --}}
            {{-- Balance --}}
            <div class="col-6 col-xl-4 mt-3 d-none d-lg-block">
                <div class="card text-bg-primary">
                    <div class="card-body">
                        <div class="row m-0 p-0">
                            <div class="col-auto">
                                <div class="bi bi-wallet2 my-auto" style="font-size:300%"> </div>
                            </div>
                            <div class="col-1 d-flex">
                                <div class="border-start border-secondary-emphasis h-100"></div>
                            </div>
                            <div class="col-auto">
                                <p class="text-white mb-1 text-opacity-75 fs-6 fw-light ">Balance</p>
                                <span class="fs-3">{{ $balance_formated }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Income --}}
            <div class="col-6 col-xl-4 mt-3 d-none d-lg-block">
                <div class="card text-bg-secondary">
                    <div class="card-body">
                        <div class="row m-0 p-0">
                            <div class="col-auto">
                                <div class="bi bi-box-arrow-in-down my-auto" style="font-size:300%"> </div>
                            </div>
                            <div class="col-1 d-flex">
                                <div class="border-start border-secondary-emphasis h-100"></div>
                            </div>
                            <div class="col-auto">
                                <p class="text-white mb-1 text-opacity-75 fs-6 fw-light ">Income</p>
                                <span class="fs-3">{{ format_currency($balance->income, 'IDR') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-4 mt-3 d-none d-lg-block">
                <div class="card text-bg-secondary">
                    <div class="card-body">
                        <div class="row m-0 p-0">
                            <div class="col-auto">
                                <div class="bi bi-box-arrow-in-up my-auto" style="font-size:300%"> </div>
                            </div>
                            <div class="col-1 d-flex">
                                <div class="border-start border-secondary-emphasis h-100"></div>
                            </div>
                            <div class="col-auto">
                                <p class="text-white mb-1 text-opacity-75 fs-6 fw-light ">Expense</p>
                                <span class="fs-3">{{ format_currency($balance->expense, 'IDR') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script></script>
</x-app-layout>
