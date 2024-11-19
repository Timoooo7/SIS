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

<div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="appSideBar"
    aria-labelledby="offcanvasDarkLabel" style="width: 250px; padding-top:100px;">
    <div class="offcanvas-header mt-2">
        <h4 class="offcanvas-title text-primary-emphasis">{{ __('Side Navigation') }}</h5>
    </div>
    <div class="border border-info mx-auto" style="width:90%"></div>

    <div class="offcanvas-body mt-2">
        <!-- Navigation Links -->
        <div class="list-group list-group-flush pe-3 ps-2">
            @if ($sidebar == 'seeo')
                @foreach ($navs as $nav)
                    <a href="{{ $nav['route'] }}"
                        class="d-inline my-2 border-0 rounded list-group-item list-group-item-action list-group-item-info shadow {{ $nav['active'] ? 'active ' : '' }}">
                        @if ($nav['active'])
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-caret-right-fill text-info d-inline" viewBox="0 0 16 16">
                                <path
                                    d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z" />
                            </svg>
                        @endif
                        {{ $nav['title'] }}
                    </a>
                @endforeach
            @elseif ($sidebar == 'blaterian')
                @foreach ($navs as $group)
                    @if ($group['group'])
                        <p class="fs-5 mb-2 border-top border-info">{{ $group['group'] }}</p>
                    @endif
                    @foreach ($group['list'] as $nav)
                        <a href="{{ $nav['route'] }}"
                            class="d-inline my-2 border-0 rounded list-group-item list-group-item-action list-group-item-info shadow {{ $nav['active'] ? 'active ' : '' }}">
                            @if ($nav['active'])
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-caret-right-fill text-info d-inline"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z" />
                                </svg>
                            @endif
                            {{ $nav['title'] }}
                        </a>
                    @endforeach
                @endforeach
            @endif
        </div>
    </div>
</div>
