<x-auth-layout>
    <div class="row px-2 ">
        <div class="col-12 border-secondary border-bottom d-flex pb-1 mb-3 px-0">
            <span class="h4 text-primary-emphasis my-auto me-auto">{{ 'Login' }}</span>
            <a class="fs-6 " href="{{ route('register') }}"><button
                    class="btn btn-sm btn-light text-primary">REGISTER</button></a>
        </div>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="row">
            <div class="col-3 col-md-4">
                <label for="email" class="form-label my-1">Email</label>
            </div>
            <div class="col-9 col-md-8">
                <input type="text" class="form-control form-control-sm" name="email" id="email"
                    value="{{ old('email') }}" autocomplete="username"required>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
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

        <!-- Remember Me -->
        <div class="row justify-content-start mt-2">
            <div class="col-12 ">
                <label for="remember_me" class="text-secondary">
                    <input id="remember_me" type="checkbox" name="remember"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>
        </div>

        <div class="row justify-content-center mt-4">
            <div class="col-auto">
                <div class="input-group input-group-sm shadow-sm">
                    <a class="link-secondary text-decoration-none btn btn-sm btn-light text-sm"
                        href="{{ route('password.request') }}">
                        {{ __('Forget password?') }}
                    </a>
                    <button class="btn btn-sm btn-primary px-3">
                        {{ __('Login') }}<i class="bi bi-box-arrow-in-right border-start border-1 ms-1 ps-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </form>

    <div class="d-flex justify-content-between align-items-center py-4">
        <div class="border w-25"></div>
        <div class="f-6">or Login with</div>
        <div class="border w-25"></div>
    </div>

    <div class="row justify-content-center">
        <div class="col-auto">
            <a href="{{ route('google.auth') }}" class="btn btn-sm btn-outline-primary shadow-sm mx-20">
                Google Account <i class="bi bi-google border-start border-2 border-primary ms-1 ps-1"></i></a>
        </div>
    </div>

</x-auth-layout>
