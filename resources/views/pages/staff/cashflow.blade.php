<?php
$auth_user = Auth::user();
?>

<x-app-layout>
    <x-slot name="header">
        {{ __('Cash Flow') }}
    </x-slot>

    <div class="container">
        <div class="row mt-4 pb-4 gx-3">
            <div class="col-12 col-lg-6">
                {{-- Cash Accounts --}}
                <div class="row gx-2">
                    <div class="col-6 d-flex">
                        <div class="card text-light bg-primary border-primary shadow ms-2 d-none d-lg-block w-100">
                            <div class="d-flex">
                                <i class="bi bi-journal-plus text-light ms-3 border-end border-light pe-3 my-3"
                                    style="font-size: 5em;"></i>
                                <span class="d-inline-block mb-0">
                                    <p class="fs-5 text-center fw-bold mb-2 mt-5 mx-4">{{ 'Cash In' }}</p>
                                    <p class="fs-6 text-center mb-3 mx-4">
                                        {{ format_currency($cash_in_items->where('financial_id', '!=', null)->sum('price')) }}
                                    </p>
                                </span>
                            </div>
                        </div>
                        <div class="card text-light bg-primary shadow ms-2 d-lg-none">
                            <i class="bi bi-journal-plus text-light mx-5 " style="font-size: 5em;"></i>
                            <p class="fs-5 text-center fw-bold mb-1 mt-0 mx-4">{{ 'Cash In' }}</p>
                            <p class="fs-6 text-center mb-3 mx-4">
                                {{ format_currency($cash_in_items->where('financial_id', '!=', null)->sum('price')) }}
                            </p>
                        </div>
                    </div>
                    <div class="col-6 d-flex">
                        <div class="card text-light bg-secondary shadow me-2 d-none d-lg-block w-100">
                            <div class="d-flex">
                                <i class="bi bi-journal-minus ms-3 border-end border-light pe-3 my-3"
                                    style="font-size: 5em;"></i>
                                <span class="d-inline-block mb-0">
                                    <p class="fs-5 text-center fw-bold mb-2 mt-5 mx-4">{{ 'Cash Out' }}</p>
                                    <p class="fs-6 text-center mb-3 mx-4">
                                        {{ format_currency($cash_out_items->sum('disbursement')) }}
                                    </p>
                                </span>
                            </div>
                        </div>
                        <div class="card bg-secondary text-light shadow me-2 d-lg-none">
                            <i class="bi bi-journal-minus text-light mx-5 " style="font-size: 5em;"></i>
                            <p class="fs-5 text-center fw-bold mb-1 mt-0 mx-4">{{ 'Cash Out' }}</p>
                            <p class="fs-6 text-center mb-3 mx-4">
                                {{ format_currency($cash_out_items->sum('disbursement')) }}
                            </p>
                        </div>
                    </div>
                </div>
                {{-- Cash In Filter --}}
                <div class="row px-2 mt-4">
                    <div class="col-12">
                        <div class="card shadow p-3">
                            <div class="row px-2">
                                <div class="col-12 d-flex border-primary border-bottom px-1 pb-2">
                                    <span class="text-primary-emphasis ms-0 me-auto my-auto h4">
                                        <i
                                            class="bi bi-journal-plus me-0 border-end border-secondary border-1 pe-2"></i>
                                        {{ 'Cash In List' }}
                                    </span>
                                    @if ($auth_user->roles_id == 2)
                                        <!-- Button trigger Insert Cash In Modal -->
                                        <button class="btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal"
                                            data-bs-target="#insertCashInModal" id="insertCashInTrigger">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>
                                        <!-- Insert Cash In Modal -->
                                        <div class="modal fade" id="insertCashInModal" tabindex="-1"
                                            aria-labelledby="newCashInModal" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content shadow mx-3">
                                                    <div class="modal-header py-1 ps-3 pe-2">
                                                        <span class="modal-title fs-5 text-primary-emphasis"
                                                            id="newCashInModal">
                                                            <i
                                                                class="bi bi-wallet border-secondary border-end me-2 pe-2"></i>
                                                            {{ 'New Cash In' }}
                                                        </span>
                                                        <button type="button" class="btn btn-sm ms-auto"
                                                            data-bs-dismiss="modal" aria-label="Close"><i
                                                                class="bi bi-x-lg"></i></button>
                                                    </div>
                                                    <form method="post"
                                                        action="{{ $auth_user->roles_id == 2 ? route('cashIn.add') : '' }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        @method('put')
                                                        <div class="modal-body bg-light">
                                                            <div class="row justify-content-center">
                                                                <div class="col-4 col-lg-3">
                                                                    <label for="cash_in_name"
                                                                        class="form-label d-inline-block">{{ 'Name' }}</label>
                                                                </div>
                                                                <div class="col-8 col-lg-7">
                                                                    <input type="text" name="cash_in_name"
                                                                        class="form-control form-control-sm"
                                                                        id="cash_in_name"
                                                                        value="{{ old('cash_in_name') }}"
                                                                        placeholder="Dana Fakultas or etc...">
                                                                    <x-input-error :messages="$errors->get('cash_in_name')" class="mt-2" />
                                                                </div>
                                                            </div>
                                                            <div class="row mt-2 justify-content-center">
                                                                <div class="col-4 col-lg-3">
                                                                    <label for="cash_in_price"
                                                                        class="form-label d-inline-block">{{ 'Price' }}</label>
                                                                </div>
                                                                <div class="col-8 col-lg-7">
                                                                    <input type="number" name="cash_in_price"
                                                                        class="form-control form-control-sm"
                                                                        value="{{ old('cash_in_price') }}"
                                                                        id="cash_in_price">
                                                                    <x-input-error :messages="$errors->get('cash_in_price')" class="mt-2" />
                                                                </div>
                                                            </div>
                                                            <div class="row mt-2 justify-content-center">
                                                                <div class="col-4 col-lg-3">
                                                                    <label for="cash_in_receipt"
                                                                        class="form-label d-inline-block">{{ 'Receipt' }}</label>
                                                                </div>
                                                                <div class="col-8 col-lg-7">
                                                                    <input type="file"
                                                                        class="form-control form-control-sm"
                                                                        name="cash_in_receipt" id="cash_in_receipt"
                                                                        value="{{ old('cash_in_receipt') }}" required>
                                                                    <x-input-error :messages="$errors->get('cash_in_receipt')" class="mt-2" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer p-1">
                                                            <button type="submit"
                                                                class="btn btn-sm btn-primary ">{{ 'Add Cash In' }}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <nav class="navbar py-0 mt-2">
                                        <form method="post" id="formCashInFilter"
                                            action="{{ route('cashIn.filter') }}">
                                            @csrf
                                            @method('put')
                                            <?php
                                            $is_price = $filter['cash_in']['category'] == 'price';
                                            $price_order = $filter['cash_in']['order'] == 'desc' ? 'desc' : 'asc';
                                            $price_icon = $is_price && $price_order == 'asc' ? 'up' : 'down';
                                            $price_value = $is_price && $price_order == 'asc' ? 'desc' : 'asc';
                                            $price_button = $is_price && $is_price ? '' : '';
                                            ?>
                                            <input type="hidden" name="category" value="price">
                                        </form>
                                        <div class="container d-block px-0 bg-body-tertiary rounded">
                                            <div class="row">
                                                <div class="input-group">
                                                    {{-- Price Filter --}}
                                                    <button type="button" form="formCashInFilter"
                                                        class="btn btn-sm rounded-0 rounded-start text-light bg-secondary">
                                                        <i class="bi bi-funnel-fill"></i>
                                                        <span
                                                            class="ms-1 ps-2 border-start border-light d-none d-md-inline">{{ 'Filter' }}</span>
                                                    </button>
                                                    <button type="submit" form="formCashInFilter"
                                                        class="btn btn-sm btn-outline-secondary border-0 {{ $price_button }} rounded-0 my-0"
                                                        name="order" value="{{ $price_value }}">
                                                        <span class="">{{ 'Price' }}</span>
                                                        <i class="bi bi-sort-numeric-{{ $price_icon }}-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Cash In List --}}
                <div class="row mt-4 px-2 ">
                    <div class="col-12">
                        <div class="scroll-container-2 scroll-container-lg pt-2 bg-secondary bg-opacity-25 rounded">
                            <?php $i = 1; ?>
                            @if ($cash_in_items->count() == 0)
                                <div class="row mb-2">
                                    <div class="col">
                                        <span class="ms-3 text-secondary">{{ 'No cash in found.' }}</span>
                                    </div>
                                </div>
                            @endif
                            @foreach ($cash_in_items as $cash_in_item)
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <div class="card py-1 mx-2 shadow-sm card-bg-hover text-dark"
                                            id="cash_in_item_card_{{ $cash_in_item->id }}">
                                            <div class="d-flex">
                                                <div class="d-flex">
                                                    <div class="d-flex border-end border-secondary me-1 my-2">
                                                        <span
                                                            class="mx-3 text-secondary fs-1 fw-normal my-auto">{{ $i }}</span>
                                                    </div>
                                                </div>
                                                <div class="d-block" style="width: 100%">
                                                    <div class="d-flex">
                                                        <p class="fw-normal mt-2 ms-2 mb-0 me-auto fs-5">
                                                            {{ $cash_in_item->name }}
                                                        </p>
                                                        <button class="btn btn-sm btn-light ms-auto me-2 my-auto"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#cashInReceiptModal"
                                                            onclick="return setCashInReceipt({{ $cash_in_item }})">
                                                            <i class="bi bi-receipt"></i>
                                                        </button>
                                                        @if ($auth_user->roles_id == 2)
                                                            <button
                                                                class="text-decoration-none btn btn-sm btn-secondary me-2 my-auto"
                                                                onclick="return confirmation('{{ route('cashIn.delete', ['id' => $cash_in_item->id]) }}','{{ 'Please confirm to delete this cash in item with name ' . $cash_in_item->name . '.' }}')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                    <div class="d-flex">
                                                        <p class="fw-light mb-0 ms-2" data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            data-bs-custom-class="custom-tooltip"
                                                            data-bs-title="{{ $cash_in_item->financial ? 'Financial Officer who in charge of this Cash In Item.' : 'Validation required!' }}">
                                                            <i
                                                                class="bi bi-person{{ $cash_in_item->financial ? '' : '-exclamation text-danger' }} me-2"></i>
                                                            @if ($cash_in_item->financial)
                                                                {{ $cash_in_item->financial->name }}
                                                            @else
                                                                @if ($auth_user->roles_id == 2)
                                                                    <button
                                                                        class="btn btn-sm btn-outline-danger border-0"
                                                                        onclick="confirmation('{{ route('cashIn.validate', ['id' => $cash_in_item->id]) }}', 'Click the button to confirm that {{ $cash_in_item->name }} price is valid for {{ format_currency($cash_in_item->price) }}.')">{{ 'Click here to validate' }}</button>
                                                                @else
                                                                    {{ 'Required validation from Financial Officer.' }}
                                                                @endif
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="d-flex">
                                                        <span class="fw-light mb-2 ms-2 ">
                                                            <i class="bi bi-journal-plus me-2"></i>
                                                            {{ format_currency($cash_in_item->price) }}
                                                        </span>
                                                        <span class="mb-2 ms-auto me-2 fst-italic text-end">
                                                            {{ format_date_time($cash_in_item->created_at) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php $i++; ?>
                            @endforeach

                            {{-- Cash In Receipt Modal --}}
                            <div class="modal fade" id="cashInReceiptModal" tabindex="-1"
                                aria-labelledby="cashInReceiptModal" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content shadow mx-3 mt-5">
                                        <div class="modal-header py-1 ps-3 pe-2">
                                            <span class="modal-title fs-5 text-primary-emphasis">
                                                <i class="bi bi-receipt border-secondary border-end me-2 pe-2"></i>
                                                {{ 'Cash In Receipt' }}
                                            </span>
                                            <button type="button" class="btn btn-sm ms-auto" data-bs-dismiss="modal"
                                                aria-label="Close"><i class="bi bi-x-lg"></i></button>
                                        </div>
                                        <div class="modal-body bg-light p-1 px-3">
                                            <div class="row justify-content-center mt-2">
                                                <div class="col-12 d-flex">
                                                    <img src="" alt="image" class="rounded mx-auto"
                                                        style="width: 100%; height 100%;  object-fit: contain; max-height:320px;"
                                                        id="ci_receipt_image">
                                                </div>
                                            </div>
                                            <div class="row justify-content-center">
                                                <div class="col-12 d-flex">
                                                    <a href="" target="blank" style="text-decoration-none"
                                                        class="mx-auto" id="ci_receipt_download" download>
                                                        <button class="btn btn-sm btn-light">
                                                            <span class="fw-light" id="ci_receipt_name"></span>
                                                            <i class="bi bi-download text-primary"></i>
                                                        </button>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer p-1">
                                            <button data-bs-dismiss="modal" aria-label="Close"
                                                class="btn btn-sm btn-secondary ">{{ 'Close' }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                {{-- Cash Out Filter --}}
                <div class="row mt-lg-0 mt-3 px-2">
                    <div class="col-12">
                        <div class="card shadow p-3">
                            <div class="row px-2">
                                <div class="d-flex border-primary border-bottom px-1 pb-2">
                                    <span class="text-primary-emphasis ms-0 me-auto my-auto h4">
                                        <i
                                            class="bi bi-journal-minus me-0 border-end border-secondary border-1 pe-2"></i>
                                        {{ 'Cash Out List' }}
                                    </span>
                                    {{-- Button contribution collapse trigger --}}
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-12 mt-2">
                                    <nav class="navbar py-0">
                                        <form method="post" id="formCashOutFilter"
                                            action="{{ route('cashOut.filter') }}">
                                            @csrf
                                            @method('put')
                                            <?php
                                            $is_disbursement = $filter['cash_out']['category'] == 'disbursement';
                                            $disbursement_order = $filter['cash_out']['order'] == 'desc' ? 'desc' : 'asc';
                                            $disbursement_icon = $is_disbursement && $disbursement_order == 'asc' ? 'up' : 'down';
                                            $disbursement_value = $is_disbursement && $disbursement_order == 'asc' ? 'desc' : 'asc';
                                            $disbursement_button = $is_disbursement && $is_disbursement ? '' : '';
                                            ?>
                                            <input type="hidden" name="category" value="disbursement">
                                        </form>
                                        <form method="post" id="formCashOutFilter2"
                                            action="{{ route('cashOut.filter') }}">
                                            @csrf
                                            @method('put')
                                            <?php
                                            $is_name = $filter['cash_out']['category'] == 'name';
                                            $name_order = $filter['cash_out']['order'] == 'desc' ? 'desc' : 'asc';
                                            $name_icon = $is_name && $name_order == 'asc' ? 'up' : 'down';
                                            $name_value = $is_name && $name_order == 'asc' ? 'desc' : 'asc';
                                            $name_button = $is_name && $is_name ? '' : '';
                                            ?>
                                            <input type="hidden" name="category" value="name">
                                        </form>
                                        <div class="container d-block px-0 bg-body-tertiary rounded">
                                            <div class="row">
                                                <div class="input-group">
                                                    {{-- Disbursement Filter --}}
                                                    <button type="button" form="formCashOutFilter"
                                                        class="btn btn-sm rounded-0 rounded-start text-light bg-secondary">
                                                        <i class="bi bi-funnel-fill"></i>
                                                        <span
                                                            class="ms-1 ps-2 border-start border-light d-none d-md-inline">{{ 'Filter' }}</span>
                                                    </button>
                                                    <button type="submit" form="formCashOutFilter2"
                                                        class="btn btn-sm btn-outline-secondary border-0 {{ $name_button }} rounded-0 my-0"
                                                        name="order" value="{{ $name_value }}">
                                                        <span class="">{{ 'Name' }}</span>
                                                        <i class="bi bi-sort-alpha-{{ $name_icon }}-alt"></i>
                                                    </button>
                                                    <button type="submit" form="formCashOutFilter"
                                                        class="btn btn-sm btn-outline-secondary border-0 {{ $disbursement_button }} rounded-0 my-0"
                                                        name="order" value="{{ $disbursement_value }}">
                                                        <span class="">{{ 'Disbursement' }}</span>
                                                        <i class="bi bi-sort-alpha-{{ $disbursement_icon }}-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Cash Out List --}}
                <div class="row mt-4 px-2">
                    <div class="col-12">
                        <div class="scroll-container-2 scroll-container-lg-2 pt-2 bg-secondary bg-opacity-25 rounded">
                            <?php $i = 1; ?>
                            @if ($cash_out_items->count() == 0)
                                <div class="row mb-2">
                                    <div class="col">
                                        <span class="ms-3 text-secondary">{{ 'No department found.' }}</span>
                                    </div>
                                </div>
                            @endif
                            @foreach ($cash_out_items as $cash_out_item)
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <div class="card py-1 mx-2 shadow-sm card-bg-hover text-dark"
                                            id="cash_out_item_card_{{ $cash_out_item->id }}">
                                            <div class="d-flex">
                                                <div class="d-flex">
                                                    <div class="d-flex border-end border-secondary me-1 my-2">
                                                        <span
                                                            class="mx-3 text-secondary fs-1 fw-normal my-auto">{{ $i }}</span>
                                                    </div>
                                                </div>
                                                <div class="d-block" style="width: 100%">
                                                    <div class="d-flex">
                                                        <p class="fw-normal mt-2 ms-2 mb-0 me-auto fs-5">
                                                            {{ $cash_out_item->name }}
                                                        </p>
                                                        <div class="btn-group btn-group-sm mt-2 ms-auto me-2">
                                                            <a class="btn btn-sm btn-light text-decoration-none"
                                                                href="{{ route('department', ['array_id' => 0, 'department_name' => $cash_out_item->name]) }}">
                                                                <i class="bi bi-info-circle text-primary"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex">
                                                        <p class="fw-light mb-0 ms-2" data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            data-bs-custom-class="custom-tooltip"
                                                            data-bs-title="Department Manager who in charge of this Cash Out Item.">
                                                            <i class="bi bi-person me-2"></i>
                                                            {{ $cash_out_item->manager->name }}
                                                        </p>
                                                    </div>
                                                    <div class="d-flex">
                                                        <span class="fw-light mb-2 ms-2 ">
                                                            <i class="bi bi-journal-minus me-2"></i>
                                                            {{ format_currency($cash_out_item->disbursement) }}
                                                        </span>
                                                        <span class="mb-2 ms-auto me-2 fst-italic text-end">
                                                            {{ format_date_time($cash_out_item->created_at) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php $i++; ?>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function setCashInReceipt(receipt) {
            const image = document.getElementById('ci_receipt_image');
            const download = document.getElementById('ci_receipt_download');
            const name = document.getElementById('ci_receipt_name');

            image.setAttribute('src', '/storage/images/receipt/cash_in/' + receipt.reciept);
            download.setAttribute('href', '/storage/images/receipt/cash_in/' + receipt.reciept);
            name.innerHTML = receipt.reciept;
        }

        function collapse_toggle(trigger_id) {
            if (trigger_id == 'collapseCashOutTrigger') {
                var container = document.getElementById('collapseCashInList');
                var container2 = document.getElementById('collapseCashInFilter');
                var trigger = document.getElementById('collapseCashInTrigger');
                if (container.classList.contains('show')) {
                    container.classList.remove('show');
                    container2.classList.remove('show');
                    trigger.classList.remove('bi-chevron-up');
                    trigger.classList.add('bi-chevron-down');
                }
            } else {
                var container = document.getElementById('collapseCashOutList');
                var container2 = document.getElementById('collapseCashOutFilter');
                var trigger = document.getElementById('collapseCashOutTrigger');
                if (container.classList.contains('show')) {
                    container.classList.remove('show');
                    container2.classList.remove('show');
                    trigger.classList.remove('bi-chevron-up');
                    trigger.classList.add('bi-chevron-down');
                }
            }

            var trigger = document.getElementById(trigger_id);
            var container_id = trigger_id == 'collapseCashOutTrigger' ? 'collapseCashOutFilter' : 'collapseCashInFilter';
            var container2 = document.getElementById(container_id);
            if (trigger.classList.contains('bi-chevron-up')) {
                trigger.classList.remove('bi-chevron-up');
                trigger.classList.add('bi-chevron-down');
                container2.classList.remove('show');
            } else {
                trigger.classList.remove('bi-chevron-down');
                trigger.classList.add('bi-chevron-up');
                container2.classList.add('show');
            }
        }
    </script>
</x-app-layout>
