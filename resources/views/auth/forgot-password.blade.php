<x-auth-layout>
    <div class="row px-2 ">
        <div class="col-12 border-secondary border-bottom d-flex pb-1 mb-3 px-0">
            <span class="h4 text-primary-emphasis my-auto me-auto">{{ 'Reset Password' }}</span>
        </div>
    </div>
    <div class="text-secondary" style="text-align: justify;">
        {{ __('Forgot your password? No problem.') }} <br>
        {{ __('Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="row justify-content-center mt-2">
            <div class="col-4 col-lg-3">
                <label for="email" class="form-label my-1">Email</label>
            </div>
            <div class="col-8 col-lg-7">
                <input type="text" class="form-control form-control-sm" name="email" id="email"
                    placeholder="your@mail.com" value="{{ old('email') }}" autocomplete="username" autofocus required>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-sm btn-primary w-100">
                {{ __('Send Password Reset Link') }} <i class="bi bi-envelope-arrow-up border-start ms-3 ps-3"></i>
            </button>
        </div>
    </form>
</x-auth-layout>
