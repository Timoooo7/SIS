<x-auth-layout>
    <div class="row px-2 ">
        <div class="col-12 border-secondary border-bottom d-flex pb-1 mb-3 px-0">
            <span class="h4 text-primary-emphasis my-auto me-auto">{{ 'Complete Registration' }}</span>
        </div>
    </div>
    <p class="fw-normal text-dark bg-danger-subtle border text-center rounded border-danger pb-1 mb-1 ">
        {{ 'Do not leave or refresh this page!' }}
    </p>
    <p class="fw-light text-secondary" style="text-align: justify">
        {{ 'You are registering new account using google. Please insert your phone and password information to complete the registration.' }}

    <form method="POST" action="{{ route('complete.register.google') }}">
        @csrf

        <!-- Google User Id -->
        <input type="hidden" name="id_google" value="{{ $id }}">

        <!-- Phone -->
        <div class="row justify-content-center mt-2">
            <div class="col-4 col-lg-3">
                <label for="phone" class="form-label my-1">Phone</label>
            </div>
            <div class="col-8 col-lg-7">
                <input type="number" class="form-control" name="phone" id="phone" value="{{ old('phone') }}"
                    autocomplete="username" autofocus required>
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>
        </div>
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
        <!-- Confirm Password -->
        <div class="row justify-content-center mt-2">
            <div class="col-4 col-lg-3">
                <label for="password_confirmation" class="form-label my-1">Confirm</label>
            </div>
            <div class="col-8 col-lg-7">
                <div class="input-group input-group-sm">
                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation"
                        value="{{ old('password') }}" autocomplete="password_confirmation" required style="width:auto;">
                    <button type="button" class="btn bg-light"
                        onclick="show_password('password_confirmation','password_confirmation_icon')">
                        <i class="bi bi-eye-slash-fill" id="password_confirmation_icon"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="mt-4">
            <button class="btn btn-primary w-100">
                {{ __('Register') }}<i class="bi bi-person-fill-add border-start border-1 ms-3 ps-3"></i>
            </button>
        </div>
    </form>
</x-auth-layout>
