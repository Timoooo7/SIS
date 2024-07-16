<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="container py-3 px-10">
        <div class="row justify-content-evenly mx-auto sm:px-6 lg:px-8 ">
            <div class="col-md-5 mt-4 p-4 sm:p-8 bg-white shadow sm:rounded-lg border-top border-primary-subtle">
                {{-- <div class="max-w-xl"> --}}
                @include('pages.profile.partials.update-profile-information-form')
                {{-- </div> --}}
            </div>

            <div class="col-md-5 mt-4 p-4 sm:p-8 bg-white shadow sm:rounded-lg border-top border-primary-subtle">
                {{-- <div class="max-w-xl"> --}}
                @include('pages.profile.partials.update-password-form')
                {{-- </div> --}}
            </div>
        </div>
    </div>
</x-app-layout>
