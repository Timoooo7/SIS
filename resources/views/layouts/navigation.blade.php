<nav x-data="{ open: false }" class="border-0 bg-primary-subtle shadow border-bottom border-primary sticky-top"
    style="z-index: 2000;">

    <?php
    // Sidebar Identification
    $sidebar = Str::of(Route::current()->uri())->split('[/]')->first();
    $sections = [['route' => route('dashboard'), 'active' => $sidebar == 'seeo', 'title' => 'SEEO'], ['route' => route('food.stand', ['array_id' => 0]), 'active' => $sidebar == 'blaterian', 'title' => 'Blaterian']];
    // Side Nav list
    if ($sidebar == 'seeo') {
        $navs = [['route' => route('dashboard'), 'active' => request()->routeIs('dashboard'), 'title' => 'Dashboard'], ['route' => route('department'), 'active' => request()->routeIs('department'), 'title' => 'Department'], ['route' => route('cashflow'), 'active' => request()->routeIs('cashflow'), 'title' => 'Cash Flow'], ['route' => route('role'), 'active' => request()->routeIs('role'), 'title' => 'Roles']];
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
                        'route' => route('food.stand', ['array_id' => 0]),
                        'active' => request()->routeIs('food.stand', ['array_id' => 0]),
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
                        'route' => route('food.sales'),
                        'active' => request()->routeIs('good'),
                        'title' => 'Goods',
                    ],
                    [
                        'route' => route('food.sales'),
                        'active' => request()->routeIs('good'),
                        'title' => 'Goods',
                    ],
                ],
            ],
        ];
    }
    ?>

    <!-- Primary Navigation Menu -->
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between" style="height: auto">
            <div class="flex">
                <!-- Logo -->
                @if (isset($navs))
                    <button class="pe-3 my-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#appSideBar"
                        aria-controls="offcanvasExample">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            class="bi bi-list text-primary" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                        </svg>
                    </button>
                @endif
                <div class="py-0 flex items-center">
                    <a href="{{ route('intro') }}">
                        <x-application-logo class="img-thumbnail bg-transparent border-0" />
                    </a>
                </div>

                @foreach ($sections as $section)
                    <div class="hidden sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="$section['route']" :active="$section['active']" class="my-auto fs-6" style="height: 50px">
                            {{ $section['title'] }}
                        </x-nav-link>
                    </div>
                @endforeach
            </div>

            {{-- Off Canvas Sidebar --}}
            @if (isset($navs))
                <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="appSideBar"
                    aria-labelledby="offcanvasDarkLabel" style="width: 250px;">
                    <div class="offcanvas-header mt-2">
                        <h4 class="offcanvas-title text-primary-emphasis">{{ __('Side Navigation') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
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
                            @elseif ($sidebar == 'blaterian')
                                @foreach ($navs as $group)
                                    @if ($group['group'])
                                        <p class="fs-5 mb-2 mt-4 border-top border-info">{{ $group['group'] }}</p>
                                    @endif
                                    @foreach ($group['list'] as $nav)
                                        <a href="{{ $nav['route'] }}"
                                            class="d-inline my-2 border-0 rounded list-group-item list-group-item-action list-group-item-info shadow {{ $nav['active'] ? 'active ' : '' }}">
                                            @if ($nav['active'])
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor"
                                                    class="bi bi-caret-right-fill text-info d-inline"
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
            @endif

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex px-3 py-2 text-sm leading-4 font-medium rounded-md">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                        <div class="fw-light text-secondary" style="font-size: 12px">{{ Auth::user()->roles->name }}
                        </div>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="fs-6">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" class="fs-6"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden bg-transparent">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out bg-transparent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-person-gear" viewBox="0 0 16 16">
                        <path :class="{ 'hidden': open, 'inline-flex': !open, 'text-dark' }"
                            d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m.256 7a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1zm3.63-4.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <!-- Responsive Settings Options -->
        <div class="pt-1 pb-1 border-t border-gray-200">
            <div class="mt-3 space-y-1">
                @foreach ($sections as $section)
                    <x-responsive-nav-link class="text-end" :href="$section['route']" :active="$section['active']">
                        {{ $section['title'] }}
                    </x-responsive-nav-link>
                @endforeach
                <x-responsive-nav-link class="text-end" :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                    {{ __('Profile ' . Auth::user()->name) }}
                </x-responsive-nav-link>
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link class="text-end" :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
