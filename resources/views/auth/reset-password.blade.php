<x-auth-layout>
    <div class="row px-2">
        <div class="col-12 border-secondary border-bottom d-flex pb-1 mb-3 px-0">
            <span class="h4 text-primary-emphasis my-auto me-auto">{{ 'Reset Password' }}</span>
        </div>
    </div>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="row justify-content-center mt-2">
            <div class="col-lg-3 col-4">
                <label for="email" class="form-label my-1">Email</label>
            </div>
            <div class="col-lg-7 col-8">
                <input type="text" class="form-control form-control-sm" name="email" id="email"
                    value="{{ old('email', $request->email) }}" autocomplete="username" required>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
        </div>
        <!-- Password -->
        <div class="row justify-content-center mt-2">
            <div class="col-lg-3 col-4">
                <label for="password" class="form-label my-1">Password</label>
            </div>
            <div class="col-lg-7 col-8">
                <div class="input-group input-group-sm">
                    <input type="password" class="form-control form-control-sm" name="password" id="password"
                        value="{{ old('password') }}" autocomplete="username" required>
                    <button type="button" class="btn bg-light" onclick="show_password('password','password_icon')">
                        <i class="bi bi-eye-slash-fill" id="password_icon"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
        </div>
        <!-- Confirm Password -->
        <div class="row justify-content-center mt-2">
            <div class="col-lg-3 col-4">
                <label for="password_confirmation" class="form-label my-1">Confirm</label>
            </div>
            <div class="col-lg-7 col-8">
                <div class="input-group input-group-sm">
                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation"
                        value="{{ old('password') }}" autocomplete="password_confirmation" required>
                    <button type="button" class="btn bg-light"
                        onclick="show_password('password_confirmation','password_confirmation_icon')">
                        <i class="bi bi-eye-slash-fill" id="password_confirmation_icon"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="mt-4">
            <button class="btn btn-sm btn-primary w-100">
                {{ __('Reset Password') }}<i class="bi bi-unlock-fill border-start border-1 ms-3 ps-3"></i>
            </button>
        </div>
    </form>
</x-auth-layout>
