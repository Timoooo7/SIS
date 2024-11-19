<x-auth-layout>
    <div class="row px-2 ">
        <div class="col-12 border-secondary border-bottom d-flex pb-1 mb-3 px-0">
            <span class="h4 text-primary-emphasis my-auto me-auto">{{ 'Authentication Required' }}</span>
        </div>
    </div>
    <div class="fs-6 text-secondary">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="row justify-content-center mt-2">
            <div class="col-4 col-lg-3">
                <label for="password" class="form-label my-1">Password</label>
            </div>
            <div class="col-8 col-lg-7">
                <div class="input-group input-group-sm">
                    <input type="password" class="form-control" name="password" id="password"
                        value="{{ old('password') }}" autocomplete="username" required style="width:auto;">
                    <button type="button" class="btn bg-light" onclick="show_password('password','password_icon')">
                        <i class="bi bi-eye-slash-fill" id="password_icon"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
        </div>

        <div class="mt-3">
            <button class="btn btn-primary w-100">
                {{ __('Confirm Password') }} <i class="bi bi-lock-fill border-start ms-3 ps-3"></i>
            </button>
        </div>
    </form>
</x-auth-layout>
