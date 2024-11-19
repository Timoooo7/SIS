<nav class="navbar navbar-expand-lg shadow" style="z-index: 2000; background:#850f8d;">

    <?php
    // Sidebar Identification
    $auth_user = Auth::user();
    $sidebar = Str::of(Route::current()->uri())->split('[/]')->first();
    $sections = [['route' => route('dashboard'), 'active' => $sidebar == 'good', 'title' => 'Goods'], ['route' => route('food.balance'), 'active' => $sidebar == 'food', 'title' => 'Foods']];
    ?>

    <!-- Primary Navigation Menu -->
    <div class="container-fluid" style="background: #850f8d;">
        <a class="navbar-brand" href="{{ route('shop') }}">
            <img src="/storage/images/apps/shop_logo.png" alt="image" style="width: 100%; height: 4rem;">
        </a>
        <div class="collapse navbar-collapse" id="navbarTitle">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @foreach ($sections as $section)
                    {{-- Small Medium Screen --}}
                    <li class="nav-item d-lg-none">
                        <?php
                        $active = $section['active'] ? 'fw-semibold text-opacity-100' : '';
                        ?>
                        <a class="nav-link fs-5 text-light text-opacity-75 ms-2 {{ $active }}"
                            href="{{ $section['route'] }}">{{ $section['title'] }}</a>
                    </li>
                    {{-- Large Screen --}}
                    <li class="nav-item d-none d-lg-block">
                        <?php
                        $active = $section['active'] ? 'fw-semibold text-opacity-100 border-bottom border-warning border-3' : '';
                        ?>
                        <a class="nav-link fs-5 text-light text-opacity-75 ms-2 {{ $active }}"
                            href="{{ $section['route'] }}">{{ $section['title'] }}</a>
                    </li>
                @endforeach
            </ul>
            <div class="border-light border-bottom border-2 w-full d-lg-none mb-2"></div>
            <span class="navbar-text">
                <div class="dropdown position-relative">
                    <button
                        class="my-0 me-3 text-decoration-none border-0 bg-transparent p-0 text-white dropdown dropdown-toggle"
                        role="button" aria-expanded="false" data-bs-toggle="dropdown">
                        {{ $auth_user->name }}
                    </button>
                    <ul class="dropdown-menu position-absolute" style=" right:10px; top:20px;">
                        @if ($auth_user->roles_id > 0)
                            <a href="{{ route('dashboard') }}" class="text-decoration-none">
                                <span class="bi bi-diagram-2 dropdown-item">
                                    {{ 'SIS Dashboard' }}
                                </span>
                            </a>
                        @endif
                        <li onclick="confirmation('{{ route('logout') }}', 'Are you sure want to Logout?')">
                            <span class="bi bi-box-arrow-right dropdown-item">
                                {{ 'Logout' }}
                            </span>
                        </li>
                    </ul>
                </div>
            </span>
        </div>
    </div>
</nav>
