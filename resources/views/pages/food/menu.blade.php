<x-app-layout>
    <x-slot name="header">
        <nav aria-label="breadcrumb w-auto b-0" style="--bs-breadcrumb-divider: '>';">
            <ol class="breadcrumb">
                <li class="breadcrumb-item " aria-current="page"><span
                        class="text-decoration-none text-black">{{ 'Foods' }}</span></li>
                <li class="breadcrumb-item font-semibold text-xl text-gray-800 leading-tight" aria-current="page">
                    {{ __('Menu') }}</li>
            </ol>
        </nav>
    </x-slot>

    <div class="container py-3 px-10">
        <ul class="nav nav-tabs">
            <li class="nav-item"><a id="tab_1" onclick="show_tab(1)" class="nav-link active">Cashflow</a></li>
            <li class="nav-item"><a id="tab_2" onclick="show_tab(2)" class="nav-link">Stand</a></li>
            <li class="nav-item"><a id="tab_3" onclick="show_tab(3)" class="nav-link">Sales</a></li>
        </ul>
        {{-- Cashflow Tab --}}
        <div id="content_1" class="row pt-0 mx-auto sm:px-6 px-2 bg-primary shadow rounded-lg"
            style="--bs-bg-opacity: .02">
            {{-- Dashboard --}}
            <div class="col-lg-12 mt-4 px-2">
                <div class="h5 text-dark border-primary border-bottom pb-2 mx-2">
                    {{ __('Food Cashflow Dashboard') }}
                    <span class="fw-light fs-6 fst-italic text-secondary border-start ps-2"> Only
                        Operational Officer can modify.</span>
                </div>
                <div class="row mx-auto sm:px-6 px-0 space-y-3">
                    <div class="col-lg-3 pt-2 bg-white rounded-lg">
                        <h6 class="fw-bold">Date Time <span
                                class="text-center fw-light fst-italic float-end">{{ now()->format('d/M/y H:i:s') }}</span>
                        </h6>
                        <p class="text-secondary mb-0"> All data on the display are based on this date and time.
                            Reload
                            the
                            page to update the newest data.</p>
                    </div>
                    <div class="col-lg-7">
                        <div class="fs-6 my-1">Chief Executive Officer & Co :
                            <span class="float-end">Data</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stand Tab --}}
        <div id="content_2" hidden class="row pt-0 mx-auto sm:px-6 px-2 bg-primary shadow rounded-lg"
            style="--bs-bg-opacity: .02">
            {{-- Dashboard --}}
            <div class="col-lg-12 mt-4 px-2">
                <div class="h5 text-dark border-primary border-bottom pb-2 mx-2">
                    {{ __('Stand') }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-lg-6">
                    <div class="card border-0">
                        <div class="card-header bg-white border-0">{{ __('Stand Blaterian 1') }}</div>
                        <div class="card-body py-2">
                            <div class="row m-0">
                                <div class="col-lg-6 border-end border-primary">
                                    <p class="my-1">{{ __('Date :') }} <span
                                            class="float-end">{{ '23 Apr 2024' }}</span> </p>
                                    <p class="my-1">{{ __('Place :') }} <span
                                            class="float-end">{{ 'Lapangan Sidakangen' }}</span> </p>
                                    <p class="my-1">{{ __('In Charge :') }} <span
                                            class="float-end">{{ 'Timothy Arella Harsono' }}</span> </p>
                                </div>
                                <div class="col-lg-6">
                                    <p class="my-1">{{ __('Expense :') }} <span
                                            class="float-end">{{ 'IDR 325,800.00' }}</span> </p>
                                    <p class="my-1">{{ __('Income :') }} <span
                                            class="float-end">{{ 'IDR 425,800.00' }}</span> </p>
                                    <p class="my-1">{{ __('Profit (20%) :') }} <span
                                            class="float-end">{{ 'IDR 100,00.00' }}</span> </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sales Tab --}}
        <div id="content_3" hidden class="row pt-0 mx-auto sm:px-6 px-2 bg-primary shadow rounded-lg"
            style="--bs-bg-opacity: .02">
            {{-- Dashboard --}}
            <div class="col-lg-12 mt-4 px-2">
                <div class="h5 text-dark border-primary border-bottom pb-2 mx-2">
                    {{ __('Sales') }}
                </div>

            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            show_tab(1);
        };

        function show_tab(target) {
            const tabs = 3;
            for (let number = 1; number <= tabs; number++) {
                let tab = document.getElementById('tab_' + number);
                let content = document.getElementById('content_' + number);
                if (target != number) {
                    // set tab to deactive
                    tab.setAttribute('class', 'nav-link');
                    // set content to hide
                    content.setAttribute('hidden', '');
                } else {
                    // set tab to active
                    tab.setAttribute('class', 'nav-link active');
                    // set content to show
                    content.removeAttribute('hidden');
                }
            }

        }
    </script>
</x-app-layout>
