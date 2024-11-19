<nav class="navbar navbar-expand-lg bg-primary shadow" style="z-index: 2000;">

    <?php
    // Sidebar Identification
    $sidebar = Str::of(Route::current()->uri())->split('[/]')->first();
    $sections = [['route' => route('dashboard'), 'active' => $sidebar == 'seeo', 'title' => 'SEEO'], ['route' => route('food.balance'), 'active' => $sidebar == 'blaterian', 'title' => 'Blaterian']];
    ?>

    <!-- Primary Navigation Menu -->
    <div class="container-fluid">
        <button class="btn btn-primary py-0 mx-2 {{ request()->routeIs('profile.edit') ? 'disabled' : '' }}"
            type="button" data-bs-toggle="offcanvas" data-bs-target="#appSideBar" aria-controls="appSideBar">
            <i class="bi bi-list fs-1 text-{{ request()->routeIs('profile.edit') ? 'primary' : 'light' }}"></i>
        </button>
        <a class="navbar-brand" href="{{ route('intro') }}">
            <x-application-logo class="img-thumbnail bg-transparent border-0" />
        </a>
        <button class="btn btn-primary border-0 d-lg-none" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarTitle" aria-controls="navbarTitle">
            <i class="bi bi-three-dots text-light fs-1"></i>
        </button>
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
                <div class="dropdown">
                    <button
                        class="my-0 me-3 text-decoration-none border-0 bg-transparent p-0 text-white dropdown dropdown-toggle"
                        role="button" aria-expanded="false" data-bs-toggle="dropdown">
                        {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person-lines-fill"></i> {{ 'Profile' }}</a>
                        </li>
                        <li onclick="confirmation('{{ route('logout') }}', 'Are you sure want to Logout?')">
                            <span class="bi bi-box-arrow-right dropdown-item">
                                {{ 'Logout' }}
                            </span>
                        </li>
                    </ul>
                </div>
                <p class="my-0 me-3 text-light text-opacity-75 fw-light">
                    {{ Auth::user()->roles->name }}
                </p>
            </span>
        </div>
    </div>
</nav>
