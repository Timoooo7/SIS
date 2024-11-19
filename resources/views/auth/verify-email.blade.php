<x-auth-layout>
    <h4 class="text-primary-emphasis border-secondary border-bottom pb-3">{{ 'Welcome ' . Auth::user()->name . '!' }}
    </h4>

    <div class="mb-4" style="text-align: justify;">
        {{ 'Thanks for signing up! You are logged in. ' }}
        {{ 'Before getting started, please confirm your email by ' }} <b>{{ 'click the link' }}</b>
        {{ ' we have sent to ' }} <b>{{ Auth::user()->email . '.' }}</b>
        {{ ' If you didn\'t receive the email, we will gladly send you another.' }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4">
        <form id="formLogout" method="POST" action="{{ route('logout') }}">
            @csrf

        </form>

        <form id="formVerification" method="POST" action="{{ route('verification.send') }}">
            @csrf

        </form>
        <div class="btn-group btn-group-sm" style="width: 100%">
            <button form="formLogout" type="submit" class="btn btn-secondary px-3">
                {{ __('Log Out') }}
            </button>
            <button form="formVerification" class="btn btn-primary px-3" type="submit">
                {{ __('Resend Verification Email') }}
            </button>
        </div>
    </div>
</x-auth-layout>
