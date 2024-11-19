<x-auth-layout>
    <div class="row px-2 ">
        <div class="col-12 border-secondary border-bottom d-flex pb-1 mb-3 px-0">
            <h4 class="h4 text-primary-emphasis my-auto me-auto">{{ 'REGISTER' }}</h4>
            <a class="fs-6 float-end" href="{{ route('login') }}"><button class="btn btn-sm btn-light">LOGIN</button></a>
        </div>
    </div>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <!-- Name -->
        <div class="row mt-2">
            <div class="col-3 col-md-4">
                <label for="name" class="form-label my-1">Name</label>
            </div>
            <div class="col-9 col-md-8">
                <input type="text" class="form-control form-control-sm" name="name" id="name"
                    value="{{ old('name') }}" autocomplete="name" required>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
        </div>
        <!-- Email Address -->
        <div class="row mt-2">
            <div class="col-3 col-md-4">
                <label for="email" class="form-label my-1">Email</label>
            </div>
            <div class="col-9 col-md-8">
                <input type="text" class="form-control form-control-sm" name="email" id="email"
                    value="{{ old('email') }}" autocomplete="username"required>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
        </div>
        <!-- Phone -->
        <div class="row mt-2">
            <div class="col-3 col-md-4">
                <label for="phone" class="form-label my-1">Phone</label>
            </div>
            <div class="col-9 col-md-8">
                <input type="number" class="form-control form-control-sm" name="phone" id="phone"
                    value="{{ old('phone') }}" autocomplete="username"required>
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>
        </div>
        <!-- Password -->
        <div class="row mt-2">
            <div class="col-3 col-md-4">
                <label for="password" class="form-label my-1">Password</label>
            </div>
            <div class="col-9 col-md-8">
                <div class="input-group input-group-sm">
                    <input type="password" class="form-control form-control-sm" name="password" id="password"
                        value="{{ old('password') }}" autocomplete="password" required>
                    <button type="button" class="btn bg-light text-secondary"
                        onclick="show_password('password','password_icon')">
                        <i class="bi bi-eye-slash-fill" id="password_icon"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
        </div>
        <!-- Confirm Password -->
        <div class="row mt-2">
            <div class="col-3 col-md-4">
                <label for="password_confirmation" class="form-label my-1">Confirm <span
                        class="d-none d-md-inline">Password</span></label>
            </div>
            <div class="col-9 col-md-8">
                <div class="input-group input-group-sm">
                    <input type="password" class="form-control form-control-sm" name="password_confirmation"
                        id="password_confirmation" value="{{ old('password_confirmation') }}"
                        autocomplete="password_confirmation" required>
                    <button type="button" class="btn bg-light text-secondary"
                        onclick="show_password('password_confirmation','password_confirmation_icon')">
                        <i class="bi bi-eye-slash-fill" id="password_confirmation_icon"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="row justify-content-center mt-4">
            <div class="col-auto">
                <div class="input-group input-group-sm shadow-sm">
                    <a class="link-secondary text-decoration-none btn btn-sm btn-light text-sm"
                        href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>

                    <button class="btn btn-primary px-3">
                        {{ __('Register') }}<i class="bi bi-person-fill-add border-start border-1 ms-1 ps-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </form>

    <div class="d-flex justify-content-between align-items-center py-4">
        <div class="border w-25"></div>
        <div class="f-6">or Register with</div>
        <div class="border w-25"></div>
    </div>

    <div class="row justify-content-center">
        <div class="col-auto">
            <a href="{{ route('google.auth') }}" class="btn btn-sm btn-outline-primary shadow mx-20">
                Google Account <i class="bi bi-google border-start border-2 border-primary ms-1 ps-1"></i></a>
        </div>
    </div>
</x-auth-layout>
