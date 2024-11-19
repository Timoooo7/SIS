{{-- Sidebar --}}
<?php
// Sidebar Identification
$sidebar = Str::of(Route::current()->uri())->split('[/]')->first();
$sections = [['route' => route('dashboard'), 'active' => $sidebar == 'seeo', 'title' => 'SEEO'], ['route' => route('food.balance'), 'active' => $sidebar == 'blaterian', 'title' => 'Blaterian']];
// Side Nav list
if ($sidebar == 'seeo') {
    $navs = [
        [
            'route' => route('dashboard'),
            'active' => request()->routeIs('dashboard'),
            'title' => 'Dashboard',
        ],
        [
            'route' => route('role'),
            'active' => request()->routeIs('role'),
            'title' => 'Employee',
        ],
        [
            'route' => route('department'),
            'active' => request()->routeIs('department'),
            'title' => 'Departments',
        ],
        [
            'route' => route('cashflow'),
            'active' => request()->routeIs('cashflow'),
            'title' => 'Cash Flow',
        ],
        [
            'route' => route('contribution'),
            'active' => request()->routeIs('contribution'),
            'title' => 'Contribution',
        ],
    ];
} elseif ($sidebar == 'blaterian') {
    $navs = [
        [
            'group' => 'Foods',
            'list' => [
                [
                    'route' => route('food.balance'),
                    'active' => request()->routeIs('food.balance'),
                    'title' => 'Balance',
                ],
                [
                    'route' => route('food.stand'),
                    'active' => request()->routeIs('food.stand'),
                    'title' => 'Stand',
                ],
                [
                    'route' => route('food.sales'),
                    'active' => request()->routeIs('food.sales'),
                    'title' => 'Point of Sales',
                ],
            ],
        ],
        [
            'group' => 'Goods',
            'list' => [
                [
                    'route' => route('good.balance'),
                    'active' => request()->routeIs('good.balance'),
                    'title' => 'Balance',
                ],
                [
                    'route' => route('good.product'),
                    'active' => request()->routeIs('good.product'),
                    'title' => 'Product',
                ],
            ],
        ],
    ];
}
?>

<div class="offcanvas offcanvas-start bg-white bg-opacity-75" data-bs-scroll="true" tabindex="-1" id="appSideBar"
    aria-labelledby="offcanvasDarkLabel" style="width: 250px; padding-top:100px;">
    <div class="offcanvas-header mt-2 bg-white">
        <span class="offcanvas-title text-primary-emphasis fs-4"><i
                class="bi bi-signpost-split border-end border-primary border-2 pe-2 me-2"></i>{{ __('Pages') }}
        </span>
    </div>
    <div class="border border-primary" style="width:100%"></div>

    <div class="offcanvas-body mt-2">
        <!-- Navigation Links -->

        <div class="container">
            @if ($sidebar == 'seeo')
                @foreach ($navs as $nav)
                    <div class="row mb-3">
                        <div class="col-12">
                            <a href="{{ $nav['route'] }}" class="text-decoration-none">
                                <div class="card card-border-hover shadow fs-6 px-2 py-1 {{ $nav['active'] ? 'border-primary border-top-0 border-end-0 border-bottom-0 border-2' : '' }}"
                                    style=" {{ $nav['active'] ? 'background:linear-gradient(to right, #cee3ff 0%, #f7fbff 80%);' : '' }}">
                                    {{ $nav['title'] }}
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            @elseif ($sidebar == 'blaterian')
                @foreach ($navs as $group)
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="bg-white rounded-none rounded-top px-2 py-1 text-primary-emphasis fw-bold">
                                {{ $group['group'] }}</div>
                            <div class="bg-secondary bg-opacity-25 rounded-bottom pt-3 ">
                                @foreach ($group['list'] as $nav)
                                    <div class="row">
                                        <div class="col-12 d-flex">
                                            <a href="{{ $nav['route'] }}"
                                                class="text-decoration-none ms-3 mb-3 w-100 me-2">
                                                <div class="card card-border-hover shadow fs-6 px-2 py-1 {{ $nav['active'] ? 'border-primary border-top-0 border-end-0 border-bottom-0 border-2' : '' }}"
                                                    style=" {{ $nav['active'] ? 'background:linear-gradient(to right, #cee3ff 0%, #f7fbff 80%);' : '' }}">
                                                    {{ $nav['title'] }}
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

    </div>
</div>
