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

    <div class="container pb-3 px-10">
        {{-- Dashboard --}}
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
                                <span class="fs-3">{{ format_currency($balance->balance, 'IDR') }}</span>
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
            {{-- Expense --}}
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

        {{-- Content --}}
        <div class="row mt-3">

            {{-- Charts --}}
            <div class="col-12 col-lg-6 ">
                <?php
                // Prepare dataset for profit chart
                $profitChartData = [
                    'labels' => $chart['month'],
                    'datasets' => [
                        [
                            'label' => 'Profit',
                            'data' => $chart['profit'],
                            'borderColor' => ['rgba(54, 162, 235, 1)'],
                            'borderWidth' => 1,
                        ],
                        [
                            'label' => 'Expense',
                            'data' => $chart['expense'],
                            'borderColor' => ['rgba(150, 150, 150, 1)'],
                            'borderWidth' => 1,
                        ],
                        [
                            'label' => 'Income',
                            'data' => $chart['income'],
                            'borderColor' => ['rgba(20, 20, 20, 1)'],
                            'borderWidth' => 1,
                        ],
                    ],
                ];
                ?>
                <canvas id="profitChart" class="m-0  bg-white shadow-sm mx-3 mx-md-1 px-3 mb-3 rounded-md"></canvas>
            </div>
            <div class="col-12 col-lg-6">
                <div class="row">
                    {{-- Content Tab Navigation for Medium and Smaller screen --}}
                    <ul class="nav nav-tabs mx-4">
                        <li class="nav-item"><a id="tab_1" onclick="show_tab(1)" class="nav-link">Income</a></li>
                        <li class="nav-item"><a id="tab_2" onclick="show_tab(2)" class="nav-link">Expense</a></li>
                    </ul>
                    {{-- Income list --}}
                    <div id="content_1" class="col-12 px-4 ">
                        <div class="bg-secondary bg-opacity-10 rounded-md shadow-sm">
                            {{-- Income filter control --}}
                            <div class="bg-secondary rounded-md text-white d-flex justify-content-between">
                                {{-- go to income detail page --}}
                                <span
                                    class="bg-white bg-opacity-75 text-dark text-sm align-self-start fw-bold px-1 rounded-sm mx-2 mt-2 ">
                                    <span class="d-none d-md-inline">INCOME FILTER</span>
                                    <span class="d-md-none">filter</span>
                                </span>
                                <div class="">

                                    {{-- Amount filter --}}
                                    <?php $filter_income_amount = $filter['income']['category'] == 'price' ? $filter['income']['order'] : 'desc'; ?>
                                    @switch($filter_income_amount)
                                        @case('asc')
                                            <a href="{{ route('food.balance.find', ['balance' => 'income', 'category' => 'price', 'order' => 'desc']) }}"
                                                class="text-decoration-none">
                                                <button class="btn btn-secondary text-white text-opacity-75 fw-light text-sm">
                                                    Income <i class="bi bi-arrow-up text-white"></i>
                                                </button>
                                            </a>
                                        @break

                                        @default
                                            <a href="{{ route('food.balance.find', ['balance' => 'income', 'category' => 'price', 'order' => 'asc']) }}"
                                                class="text-decoration-none">
                                                <button class="btn btn-secondary text-white text-opacity-75 fw-light text-sm">
                                                    Income <i class="bi bi-arrow-down text-white"></i>
                                                </button>
                                            </a>
                                    @endswitch
                                    {{-- Last Udpate filter --}}
                                    <?php $filter_income_update = $filter['income']['category'] == 'updated_at' ? $filter['income']['order'] : 'desc'; ?>
                                    @switch($filter_income_update)
                                        @case('asc')
                                            <a href="{{ route('food.balance.find', ['balance' => 'income', 'category' => 'updated_at', 'order' => 'desc']) }}"
                                                class="text-decoration-none">
                                                <button class="btn btn-secondary text-white text-opacity-75 fw-light text-sm">
                                                    Last Updated<i class="bi bi-arrow-up text-white"></i>
                                                </button>
                                            </a>
                                        @break

                                        @default
                                            <a href="{{ route('food.balance.find', ['balance' => 'income', 'category' => 'updated_at', 'order' => 'asc']) }}"
                                                class="text-decoration-none">
                                                <button class="btn btn-secondary text-white text-opacity-75 fw-light text-sm">
                                                    Last Updated<i class="bi bi-arrow-down text-white"></i>
                                                </button>
                                            </a>
                                    @endswitch
                                </div>

                            </div>
                            {{-- Income list item --}}
                            <div class="scroll-container mt-2">
                                <?php
                                function in_category_route($name, $id, $cat_id = 1)
                                {
                                    return $name == 'stand income' ? route('food.stand', ['array_id' => 0, 'show_id' => $id]) : route('program', ['id' => $cat_id]);
                                }
                                ?>
                                @foreach ($income as $in)
                                    <a target="_blank"
                                        href="{{ in_category_route($in->category, $in->category_id, $in->category_detail()->program_id) }}"
                                        class="text-decoration-none">
                                        <div class="card mb-2 mx-2 border-0 bg-white ">
                                            <div class="row m-0 p-0">
                                                <p class="h5 mb-0 mt-2">{{ format_currency($in->price, 'IDR') }}</p>
                                                <p class="text-dark text-opacity-50 mb-0 text-sm">
                                                    {{ $in->category . ': ' . $in->category_detail()->name }}
                                                    <span class="text-xs fw-light text-secondary float-end">
                                                        {{ 'last updated at ' . format_date_time($in->updated_at) }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    {{-- Expense list --}}
                    <div id="content_2" class="col-12 px-4 ">
                        <div class="bg-secondary bg-opacity-10 rounded-md shadow-sm">
                            {{-- Expense filter control --}}
                            <div class="bg-secondary rounded-md text-white d-flex justify-content-between">
                                {{-- go to expense detail page --}}
                                <span
                                    class="bg-white bg-opacity-75 text-dark text-sm align-self-start fw-bold px-1 rounded-sm mx-2 mt-2 ">
                                    <span class="d-none d-md-inline">EXPENSE FILTER</span>
                                    <span class="d-md-none">filter</span>
                                </span>
                                <div class="">

                                    {{-- Amount filter --}}
                                    <?php $filter_expense_amount = $filter['expense']['category'] == 'price' ? $filter['expense']['order'] : 'desc'; ?>
                                    @switch($filter_expense_amount)
                                        @case('asc')
                                            <a href="{{ route('food.balance.find', ['balance' => 'expense', 'category' => 'price', 'order' => 'desc']) }}"
                                                class="text-decoration-none">
                                                <button class="btn btn-secondary text-white text-opacity-75 fw-light text-sm">
                                                    Expense <i class="bi bi-arrow-up text-white"></i>
                                                </button>
                                            </a>
                                        @break

                                        @default
                                            <a href="{{ route('food.balance.find', ['balance' => 'expense', 'category' => 'price', 'order' => 'asc']) }}"
                                                class="text-decoration-none">
                                                <button class="btn btn-secondary text-white text-opacity-75 fw-light text-sm">
                                                    Expense <i class="bi bi-arrow-down text-white"></i>
                                                </button>
                                            </a>
                                    @endswitch
                                    {{-- Last Udpate filter --}}
                                    <?php $filter_expense_update = $filter['expense']['category'] == 'updated_at' ? $filter['expense']['order'] : 'desc'; ?>
                                    @switch($filter_expense_update)
                                        @case('asc')
                                            <a href="{{ route('food.balance.find', ['balance' => 'expense', 'category' => 'updated_at', 'order' => 'desc']) }}"
                                                class="text-decoration-none">
                                                <button class="btn btn-secondary text-white text-opacity-75 fw-light text-sm">
                                                    Last Updated<i class="bi bi-arrow-up text-white"></i>
                                                </button>
                                            </a>
                                        @break

                                        @default
                                            <a href="{{ route('food.balance.find', ['balance' => 'expense', 'category' => 'updated_at', 'order' => 'asc']) }}"
                                                class="text-decoration-none">
                                                <button class="btn btn-secondary text-white text-opacity-75 fw-light text-sm">
                                                    Last Updated<i class="bi bi-arrow-down text-white"></i>
                                                </button>
                                            </a>
                                    @endswitch
                                </div>

                            </div>
                            {{-- Expense list item --}}
                            <div class="scroll-container mt-2">
                                <?php
                                function ex_category_route($ctgry, $id)
                                {
                                    return $ctgry == 'stand expense' ? route('food.stand', ['array_id' => 0, 'show_id' => $id]) : route('food.stand', ['array_id' => 0, 'show_id' => $id]);
                                }
                                ?>
                                @foreach ($expense as $ex)
                                    <a target="_blank"
                                        href="{{ ex_category_route($ex->category, $ex->category_id) }}"
                                        class="text-decoration-none">
                                        <div class="card mb-2 mx-2 border-0 bg-white ">
                                            <div class="row m-0 p-0">
                                                <p class="h5 mb-0 mt-2">{{ format_currency($ex->price, 'IDR') }}</p>
                                                <p class="text-dark text-opacity-50 mb-0 text-sm">
                                                    {{ $ex->category . ': ' . $ex->category_detail()->name }}
                                                    <span class="text-xs fw-light text-secondary float-end">
                                                        {{ 'last updated at ' . format_date_time($ex->updated_at, 'auto') }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <script>
        var default_tab = sessionStorage.getItem('default_tab');
        window.onload = function() {
            if (window.innerWidth < 992) {}
            if (default_tab !== null) {
                show_tab(default_tab);
            } else {
                show_tab(1);
            }
        };

        function show_tab(target) {
            const tabs = 2;
            for (let number = 1; number <= tabs; number++) {
                let tab = document.getElementById('tab_' + number);
                let content = document.getElementById('content_' + number);
                if (target != number) {
                    // set tab to deactive
                    tab.setAttribute('class', 'nav-link');
                    // set content to hide
                    content.setAttribute('hidden', '');
                } else {
                    // set tab to active
                    tab.setAttribute('class', 'nav-link active bg-secondary text-white');
                    // set content to show
                    content.removeAttribute('hidden');
                }
            }

            sessionStorage.setItem('default_tab', target);
        }

        // Charts
        var profitChart = document.getElementById('profitChart').getContext('2d');
        var myChart = new Chart(profitChart, {
            type: 'line',
            data: @json($profitChartData),
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Stand Chart',
                        position: 'top',
                        font: {
                            size: 18
                        }
                    }
                },
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: 'Price (IDR)'
                        },
                        beginAtZero: true
                    }
                }

            }
        });
    </script>

</x-app-layout>
