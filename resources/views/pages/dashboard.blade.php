<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="container py-3 px-10">
        <div class="row mx-auto sm:px-6 px-2">
            <div class="col-lg-9 mt-0 mb-4 px-2">
                <div class="row">
                    <h4 class="text-warning-enhanced">Hello, {{ Auth::user()->name }}</h4>
                    <p class="text-secondary fw-light">Today is {{ now()->format('l , d F y') }}</p>
                </div>
                {{-- Highlight --}}
                <div class="row justify-content-center ">
                    {{-- Cash Flow --}}
                    <div class="col-sm-4 mb-3">
                        <div class="rounded-lg pt-3 pb-1" style="background-color: #4287f5">
                            <div class="row m-1 me-3">
                                <div class="col">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100"
                                        fill="currentColor" class="bi bi-cash-coin text-white d-inline"
                                        viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M11 15a4 4 0 1 0 0-8 4 4 0 0 0 0 8m5-4a5 5 0 1 1-10 0 5 5 0 0 1 10 0" />
                                        <path
                                            d="M9.438 11.944c.047.596.518 1.06 1.363 1.116v.44h.375v-.443c.875-.061 1.386-.529 1.386-1.207 0-.618-.39-.936-1.09-1.1l-.296-.07v-1.2c.376.043.614.248.671.532h.658c-.047-.575-.54-1.024-1.329-1.073V8.5h-.375v.45c-.747.073-1.255.522-1.255 1.158 0 .562.378.92 1.007 1.066l.248.061v1.272c-.384-.058-.639-.27-.696-.563h-.668zm1.36-1.354c-.369-.085-.569-.26-.569-.522 0-.294.216-.514.572-.578v1.1zm.432.746c.449.104.655.272.655.569 0 .339-.257.571-.709.614v-1.195z" />
                                        <path
                                            d="M1 0a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h4.083q.088-.517.258-1H3a2 2 0 0 0-2-2V3a2 2 0 0 0 2-2h10a2 2 0 0 0 2 2v3.528c.38.34.717.728 1 1.154V1a1 1 0 0 0-1-1z" />
                                        <path d="M9.998 5.083 10 5a2 2 0 1 0-3.132 1.65 6 6 0 0 1 3.13-1.567" />
                                    </svg>
                                </div>
                                <div class="col">
                                    <div class="row fs-4 fw-bold mt-3 text-white">CASH </div>
                                    <div class="row fs-4 fw-bold mb-0 text-white">FLOW </div>
                                </div>
                            </div>
                            <div class="row m-2">
                                <span class="text-white text-sm"><span class="text-light fw-lighter fst-italic">in
                                    </span> :
                                    {{ Number::currency($cash_in, in: 'IDR') }}</span>
                                <span class="text-white text-sm"><span class="text-light fw-lighter fst-italic">out
                                    </span>
                                    :
                                    {{ Number::currency($cash_out, in: 'IDR') }}</span>
                            </div>
                        </div>
                    </div>
                    {{-- Staff --}}
                    <div class="col-sm-4 mb-3 ">
                        <div class="rounded-lg pt-3 pb-1" style="background-color: #c98a02">
                            <div class="row m-1">
                                <div class="col">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100"
                                        fill="currentColor" class="bi bi-people text-white" viewBox="0 0 16 16">
                                        <path
                                            d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4" />
                                    </svg>
                                </div>
                                <div class="col">
                                    <div class="row fs-3 fw-bold mt-3 text-white">STAFF </div>
                                </div>
                            </div>
                            <div class="row m-2">
                                <span class="text-white text-sm fw-bold"><span class="text-light fst-italic">
                                        (including Chief & Manager)
                                    </span> :
                                    {{ count($staff) }}</span>
                            </div>
                        </div>
                    </div>
                    {{-- Structural --}}
                    <div class="col-sm-4 mb-3">
                        <div class="rounded-lg pt-3 pb-1" style="background-color: #e8c901">
                            <div class="row m-1 ">
                                <div class="col">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100"
                                        fill="currentColor" class="bi bi-people text-white" viewBox="0 0 16 16">
                                        <path
                                            d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4" />
                                    </svg>
                                </div>
                                <div class="col">
                                    <div class="row fs-4 fw-bold mt-3 text-white">DEPARTMENT </div>
                                    <div class="row fs-4 fw-bold text-white">& PROGRAM </div>
                                </div>
                            </div>
                            <div class="row m-2">
                                <span class="text-white text-sm fw-bold"><span class="text-light fst-italic">department
                                    </span> :
                                    {{ count($department) }}</span>
                                <span class="text-white text-sm fw-bold"><span class="text-light fst-italic">program
                                    </span> :
                                    {{ count($program) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Tables --}}
                <div class="row mt-3">
                    <div class="col-lg-7">
                        <div class="bg-white border-primary shadow border-top rounded-lg mb-3 p-2">
                            <div class="h5 fw-bold d-inline-block">{{ __('Department Disbursement') }}</div>
                            @foreach ($department as $item)
                                <?php $percent = $cash_out != 0 ? round(($item->disbursement * 100) / $cash_out) : 0; ?>
                                <div>
                                    <p class="my-0 pt-0 ms-2 float-end">
                                        {{ $item->name . ' (' }}
                                        <span class="text-danger">{{ $percent }}</span>%
                                        {{ ')' }}
                                    </p>
                                    <div class="progress mb-3 mt-1" role="progressbar"
                                        aria-label="Cash Out Item Percentage" aria-valuenow="{{ $percent }}"
                                        aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar progress-bar-striped bg-warning"
                                            style="width: {{ $percent }}%">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="bg-white border-warning shadow border-top mb-3 rounded-lg p-2">
                            <div class="h5 fw-bold d-inline-block">{{ __('User Roles') }}</div>
                            <?php
                            $chiefs = $staff->where('roles_id', 1);
                            $financials = $staff->where('roles_id', 2);
                            $operationals = $staff->where('roles_id', 3);
                            $staffs = $staff->where('roles_id', 4);
                            function person($array)
                            {
                                return count($array) > 1 ? ' persons' : ' person';
                            }
                            ?>
                            <div class="fs-6 my-1">Chief Executive Officer & Co :
                                <span class="float-end">{{ count($chiefs) . person($chiefs) }}</span>
                            </div>
                            <div class="fs-6 my-1">Financial Officer :
                                <span class="float-end">{{ count($financials) . person($financials) }}</span>
                            </div>
                            <div class="fs-6 my-1">Operational Officer :
                                <span class="float-end">{{ count($operationals) . person($operationals) }}</span>
                            </div>
                            <div class="fs-6 my-1">Staff :
                                <span class="float-end">{{ count($staffs) . person($staffs) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 bg-secondary border-secondary border-start" style="--bs-bg-opacity: .07;">

            </div>
        </div>
    </div>
</x-app-layout>
