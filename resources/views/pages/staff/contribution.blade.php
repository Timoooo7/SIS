<?php
use Carbon\Carbon;

$auth_user = Auth::user();
$min_contribution_months = $contribution_config->financial ? date('n') + 1 - $contribution_config->start : 0;
$contribution_charge = $contribution_config->financial ? $contribution_config->price : 0;
$contribution_start = $contribution_config->financial ? $contribution_config->start : 0;
?>

<x-app-layout>
    <x-slot name="header">
        {{ __('Contribution Charge') }}
    </x-slot>

    <div class="container">
        <div class="row gx-3 pb-4 px-2">
            <div class="col-lg-6">
                {{-- Contribution Settings --}}
                <div class="card border-0 shadow-sm p-3 mt-4 ">
                    {{-- Header --}}
                    <div class="d-flex border-secondary border-bottom pb-2">
                        <span class="text-primary-emphasis ms-2 me-auto my-auto h4">
                            @if ($auth_user->roles_id == 2)
                                <button class="btn btn-sm btn-light d-inline-block" data-bs-toggle="modal"
                                    data-bs-target="#contributionSettingsModal">
                                    <i class="bi bi-gear"></i>
                                </button>
                            @else
                                <i class="bi bi-gear me-2 "></i>
                            @endif
                            <span class="d-none d-lg-inline">{{ 'Contribution ' }}</span>
                            {{ __('Settings') }}
                        </span>
                        <!-- Button trigger Contribution Info Modal -->
                        <button class="btn btn-sm btn-light ms-auto d-inline-block" data-bs-toggle="modal"
                            data-bs-target="#contributionInfo">
                            <i class="bi bi-info-circle"></i>
                        </button>
                        <!-- Contribution Info Modal -->
                        <div class="modal fade" id="contributionInfo" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content shadow mx-3">
                                    <div class="modal-header py-1 ps-3 pe-2">
                                        <span class="modal-title fs-5 text-primary-emphasis" id="exampleModalLabel"><i
                                                class="bi bi-info-circle border-secondary border-end me-2 pe-2"></i>
                                            {{ 'Information' }}
                                        </span>
                                        <button type="button" class="btn btn-sm ms-auto" data-bs-dismiss="modal"
                                            aria-label="Close"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                    <div class="modal-body bg-light">
                                        <p class="fs-6 my-0 mx-3" style="text-align: justify">
                                            {{ 'Conttribution Charge (' }}<i>{{ 'Iuran Wajib Pengurus' }}</i>{{ ') are mandatory charge for all active employees, including all board of directors to support Seodirman Engineering Entrepreneurship Organization programs and activities. Charge amount are set by Financial Officers who has Financial role. If Contribution Settings are not set, please inform Financial Officers.' }}
                                        </p>
                                    </div>
                                    <div class="modal-footer p-2">
                                        <button type="button" class="btn btn-primary btn-sm ms-auto"
                                            data-bs-dismiss="modal" aria-label="Close">{{ 'Understand' }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Contribution Settings Modal -->
                        <div class="modal fade" id="contributionSettingsModal" tabindex="-1"
                            aria-labelledby="contributionSettingsModal" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content shadow mx-3">
                                    <div class="modal-header py-1 ps-3 pe-2">
                                        <span class="modal-title fs-5 text-primary-emphasis" id="exampleModalLabel"><i
                                                class="bi bi-gear border-secondary border-end me-2 pe-2"></i>
                                            {{ 'Contribution Settings' }}
                                        </span>
                                        <button type="button" class="btn btn-sm ms-auto" data-bs-dismiss="modal"
                                            aria-label="Close"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                    <form method="post" action="{{ route('contribution_settings.update') }}">
                                        @csrf
                                        @method('put')
                                        <div class="modal-body bg-light">
                                            <div class="row justify-content-center">
                                                <div class="col-4 col-lg-3">
                                                    <label for="settings_price"
                                                        class="form-label d-inline-block">Price</label>
                                                </div>
                                                <div class="col-8 col-lg-7">
                                                    <input type="number"
                                                        class="form-control form-control-sm d-inline-block"
                                                        name="price" id="settings_price"
                                                        placeholder="Type numbers only" required>
                                                    <x-input-error :messages="$errors->get('price')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="row mt-2 justify-content-center">
                                                <div class="col-4 col-lg-3">
                                                    <label for="settings_start" class="form-label d-inline-block">Start
                                                        Month</label>
                                                </div>
                                                <div class="col-8 col-lg-7">
                                                    <select name="start_month" id="settings_start"
                                                        class="form-select form-select-sm"
                                                        aria-label="Default select example" required>
                                                        <?php
                                                        $months_name = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                                        ?>
                                                        @for ($i = 0; $i < 12; $i++)
                                                            <option value="{{ $i + 1 }}">{{ $months_name[$i] }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                    <x-input-error :messages="$errors->get('start_month')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="row mt-2 justify-content-center">
                                                <div class="col-4 col-lg-3">
                                                    <label for="settings_end" class="form-label d-inline-block">End
                                                        Month</label>
                                                </div>
                                                <div class="col-8 col-lg-7">
                                                    <select name="end_month" id="settings_end"
                                                        class="form-select form-select-sm"
                                                        aria-label="Default select example" required>
                                                        @for ($i = 0; $i < 12; $i++)
                                                            <option value="{{ $i + 1 }}">{{ $months_name[$i] }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                    <x-input-error :messages="$errors->get('end_month')" class="mt-2" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer p-1">
                                            <button type="submit"
                                                class="btn btn-sm btn-primary ">{{ 'Set Contribution' }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-5 col-lg-4 text-end">{{ 'Charge :' }}</div>
                        <div class="col-7 col-lg-6">
                            {{ $contribution_config->financial ? format_currency($contribution_config->price) : '' }}
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-5 col-lg-4 text-end">{{ 'Start on :' }}</div>
                        <div class="col-7 col-lg-6">
                            {{ $contribution_config->financial ? Carbon::createFromDate(null, $contribution_config->start)->format('F') : '' }}
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-5 col-lg-4 text-end">{{ 'End on :' }}</div>
                        <div class="col-7 col-lg-6">
                            {{ $contribution_config->financial ? Carbon::createFromDate(null, $contribution_config->start + $contribution_config->period - 1)->format('F') : '' }}
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-5 col-lg-4 text-end">{{ 'Total Charge :' }}</div>
                        <div class="col-7 col-lg-6">
                            {{ $contribution_config->financial ? format_currency($contribution_config->period * $contribution_config->price) : '' }}
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-5 col-lg-4 text-end">{{ 'Set by :' }}</div>
                        <div class="col-7 col-lg-6">
                            {{ $contribution_config->financial ? $contribution_config->financial->name : '' }}
                        </div>
                    </div>
                </div>
                {{-- Contribution Detail --}}
                <div class="card border-0 shadow-sm p-3 mt-4">
                    <div class="d-flex border-bottom border-secondary pb-2">
                        <span class="h4 text-dark mx-auto my-auto">
                            <i class="bi bi-person-vcard me-2"></i>
                            <span id="contribution_employee_name" class="">
                                {{ 'Select Employee' }}
                            </span>
                        </span>
                        <!-- Button trigger Insert Contribution Modal -->
                        <button class="btn btn-sm btn-primary d-none shadow-sm" data-bs-toggle="modal"
                            data-bs-target="#insertContributionModal" id="insertContributionTrigger">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                        <!-- Insert Contribution Modal -->
                        <div class="modal fade" id="insertContributionModal" tabindex="-1"
                            aria-labelledby="newContributionModal" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content shadow mx-3">
                                    <div class="modal-header py-1 ps-3 pe-2">
                                        <span class="modal-title fs-5 text-primary-emphasis"
                                            id="newContributionModal">
                                            <i class="bi bi-wallet border-secondary border-end me-2 pe-2"></i>
                                            {{ 'New Contribution' }}
                                        </span>
                                        <button type="button" class="btn btn-sm ms-auto" data-bs-dismiss="modal"
                                            aria-label="Close"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                    <form method="post"
                                        action="{{ $contribution_config->financial_id > 0 ? route('contribution.insert') : '' }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('put')
                                        <div class="modal-body bg-light">
                                            <div class="row justify-content-center">
                                                <div class="col-4 col-lg-3">
                                                    <label for="insert_contribution_name"
                                                        class="form-label d-inline-block">{{ 'Name' }}</label>
                                                </div>
                                                <div class="col-8 col-lg-7">
                                                    <span id="insert_contribution_name"
                                                        id="insert_contribution_name"></span>
                                                </div>
                                            </div>
                                            <div class="row mt-2 justify-content-center">
                                                <div class="col-4 col-lg-3">
                                                    <label for="insert_contribution_month"
                                                        class="form-label d-inline-block">{{ 'Charge for' }}</label>
                                                </div>
                                                <div class="col-8 col-lg-7">
                                                    <select name="insert_contribution_month"
                                                        id="insert_contribution_month"
                                                        class="form-select form-select-sm"
                                                        aria-label="Default select example" required>
                                                        @for ($i = 1; $i <= 12; $i++)
                                                            <option value="{{ $i }}">
                                                                {{ $i . ' - ' . format_currency($i * $contribution_charge) }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                    <x-input-error :messages="$errors->get('insert_contribution_month')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="row mt-2 justify-content-center">
                                                <div class="col-4 col-lg-3">
                                                    <label for="settings_end"
                                                        class="form-label d-inline-block">{{ 'Receipt' }}</label>
                                                </div>
                                                <div class="col-8 col-lg-7">
                                                    <input type="file" class="form-control form-control-sm"
                                                        name="insert_contribution_receipt"
                                                        id="insert_contribution_receipt"
                                                        value="{{ old('insert_contribution_receipt') }}" required>
                                                    <x-input-error :messages="$errors->get('insert_contribution_receipt')" class="mt-2" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer p-1">
                                            <button type="submit"
                                                class="btn btn-sm btn-primary ">{{ 'Add Contribution' }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center mt-3">
                        <div class="col-5 col-lg-4 d-flex">
                            <span class="ms-auto fw-light">{{ 'Paid up to :' }}</span>
                        </div>
                        <div class="col-7 col-lg-6">
                            <div class="span" id="contribution_paid">
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center mt-3">
                        <div class="col-5 col-lg-4 d-flex">
                            <span class="ms-auto fw-light">{{ 'Unpaid bill :' }}</span>
                        </div>
                        <div class="col-7 col-lg-6">
                            <div class="span" id="contribution_unpaid">
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center mt-3">
                        <div class="col-5 col-lg-4 d-flex">
                            <span class="ms-auto fw-light">{{ 'Receipt :' }}</span>
                        </div>
                        <div class="col-7 col-lg-6" id="contribution_receipt_container">
                            <!-- Button trigger Contribution Receipt Modal -->
                            <!-- Contribution Receipt Modal -->
                            <div class="modal fade" id="receiptContributionModal" tabindex="-1"
                                aria-labelledby="receiptContributionModal" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content shadow mx-3 mt-5">
                                        <div class="modal-header py-1 ps-3 pe-2">
                                            <span class="modal-title fs-5 text-primary-emphasis"
                                                id="newContributionModal">
                                                <i class="bi bi-receipt border-secondary border-end me-2 pe-2"></i>
                                                {{ 'Contribution Receipt' }}
                                            </span>
                                            <button type="button" class="btn btn-sm ms-auto" data-bs-dismiss="modal"
                                                aria-label="Close"><i class="bi bi-x-lg"></i></button>
                                        </div>
                                        <div class="modal-body bg-light p-1 px-3">
                                            <div class="row justify-content-center mt-2">
                                                <div class="col-12 d-flex">
                                                    <img src="" alt="image" class="rounded mx-auto"
                                                        style="width: 100%; height 100%;  object-fit: contain; max-height:320px;"
                                                        id="receipt_image">
                                                </div>
                                            </div>
                                            <div class="row justify-content-center">
                                                <div class="col-12 d-flex mt-2">
                                                    <span class="mx-auto">{{ 'Contribution for ' }} <span
                                                            class="text-primary-emphasis" id="receipt_months"></span>
                                                        {{ ' months' }}</span>
                                                </div>
                                                <div class="col-12 d-flex">
                                                    <a href="" target="blank" style="text-decoration-none"
                                                        class="mx-auto" id="receipt_download" download>
                                                        <button class="btn btn-sm btn-light">
                                                            <span class="fw-light" id="receipt_name"></span>
                                                            <i class="bi bi-download text-primary"></i>
                                                        </button>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer p-1 px-2">
                                            @if ($auth_user->roles_id == 2)
                                                <form method="post" action="{{ route('contribution.validation') }}"
                                                    enctype="multipart/form-data"
                                                    id="formContributionReceiptValidation">
                                                    @csrf
                                                    @method('put')
                                                    <input type="hidden" name="receipt_id"
                                                        id="validation_receipt_id">
                                                    <input type="hidden" name="validate"
                                                        id="validation_receipt_value">
                                                </form>
                                                <form method="post" action="{{ route('contribution.delete') }}"
                                                    enctype="multipart/form-data" id="formContributionReceiptDelete">
                                                    @csrf
                                                    @method('put')
                                                    <input type="hidden" name="receipt_id" id="delete_receipt_id">
                                                </form>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="submit" form="formContributionReceiptValidation"
                                                        class="btn btn-sm btn-primary"
                                                        id="validation_button">{{ 'Validate' }}</button>
                                                    <button type="submit" form="formContributionReceiptDelete"
                                                        class="btn btn-sm btn-secondary ">{{ 'Delete' }}</button>
                                                </div>
                                            @else
                                                <button data-bs-dismiss="modal" aria-label="Close"
                                                    class="btn btn-sm btn-secondary ">{{ 'Close' }}</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                {{-- Contribution Filter --}}
                <div class="card shadow p-3 mt-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex border-primary border-bottom pb-2">
                                <span class="text-primary-emphasis ms-2 me-auto my-auto h4">
                                    <i class="bi bi-wallet me-2"></i>
                                    {{ 'Contribution List' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- Filter --}}
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <nav class="navbar py-0 mt-2">
                                <form method="post" id="searchContributonForm" role="search"
                                    action="{{ route('contribution.filter') }}">
                                    @csrf
                                    @method('put')
                                </form>
                                <form method="post" id="formNameFilter"
                                    action="{{ route('contribution.filter') }}">
                                    @csrf
                                    @method('put')
                                    <?php
                                    $is_name = $filter['category'] == 'name';
                                    $name_order = $filter['order'] == 'desc' ? 'desc' : 'asc';
                                    $name_icon = $is_name && $name_order == 'asc' ? 'up' : 'down';
                                    $name_value = $is_name && $name_order == 'asc' ? 'desc' : 'asc';
                                    $name_button = $is_name && $is_name ? '' : '';
                                    ?>
                                    <input type="hidden" name="category" value="name">
                                </form>
                                <div class="container d-block px-0 bg-body-tertiary rounded">
                                    <?php $keyword_focus = $filter['keyword'] ? 'autofocus' : ''; ?>
                                    <div class="row">
                                        <div class="input-group input-group-sm">
                                            {{-- Name Filter --}}
                                            <button type="button"
                                                class="btn btn-sm rounded-0 rounded-start text-light bg-secondary">
                                                <i class="bi bi-funnel-fill"></i>
                                                <span
                                                    class="ms-1 ps-2 border-start border-light d-none d-md-inline">{{ 'Filter' }}</span>
                                            </button>
                                            <button type="submit" form="formNameFilter"
                                                class="btn btn-sm btn-outline-secondary border-0 {{ $name_button }} rounded-0 my-0"
                                                name="order" value="{{ $name_value }}">
                                                <i class="bi bi-sort-alpha-{{ $name_icon }}-alt"></i>
                                                <span class="d-none d-md-inline">{{ 'Name' }}</span>
                                            </button>
                                            {{-- Search Filter --}}
                                            <input type="text" name="keyword" class="form-control form-control-sm"
                                                form="searchContributonForm" {{ $keyword_focus }}
                                                value="{{ $filter['keyword'] }}"
                                                placeholder="{{ 'Search by name' }}">
                                            <button class="btn btn-sm btn-outline-secondary border-0" type="submit"
                                                form="searchContributonForm">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
                {{-- Contribution List --}}
                <div class="scroll-container-2 scroll-container-lg-2 pt-2 bg-secondary bg-opacity-25 rounded mt-3"
                    id="contributionListScrollContainer">
                    <?php $i = 1; ?>
                    @foreach ($users as $user)
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="card py-1 mx-2 fw-light shadow-sm card-bg-hover text-dark"
                                    id="contribution_card_{{ $user->id }}"
                                    onclick="return set_contribution({{ $user }})">
                                    <span class="d-flex">
                                        <span
                                            class="mx-2 border-end border-secondary text-secondary fw-normal pe-2">{{ $i }}</span>
                                        <span class="fw-normal">{{ $user->name }}</span>
                                        <span class="ms-auto me-2 fst-italic fw-light">
                                            @if ($user->contribution && $user->contribution->months >= $min_contribution_months)
                                                <span class="text-success fw-light">{{ 'On track' }}</span>
                                                <i class="text-success bi bi-check-circle-fill"></i>
                                            @else
                                                <span class="text-secondary fw-light">{{ 'Late' }}</span>
                                                <i class="text-danger bi bi-exclamation-circle-fill"></i>
                                            @endif
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <?php $i++; ?>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        var available_receipt_index = [];
        var selected_contribution_id;
        var min_contribution_months = {{ $min_contribution_months }};
        var contribution_charge = {{ $contribution_charge }};
        var contribution_start = {{ $contribution_start }};
        var auth_user_id = {{ $auth_user->id }};
        var auth_user_roles_id = {{ $auth_user->roles_id }};

        function set_contribution(user) {
            // set contribution details
            var contribution = user.contribution;
            var contribution_card = document.getElementById('contribution_card_' + user.id);
            var contribution_paid = document.getElementById('contribution_paid');
            var contribution_unpaid = document.getElementById('contribution_unpaid');
            var contribution_paid = document.getElementById('contribution_paid');
            var contribution_receipt = document.getElementById('contribution_receipt');
            var contribution_employee_name = document.getElementById('contribution_employee_name');

            contribution_employee_name.innerHTML = user.name;
            contribution_paid.innerHTML = contribution && contribution.months ? getMonthName(contribution_start +
                contribution.months - 1) : 'Not started yet';
            if (contribution_start !== 0) {
                if (contribution) {
                    var unpaid_month = contribution.months < min_contribution_months ?
                        min_contribution_months - contribution.months + ' months' : '0 month';
                    var unpaid_bill = contribution.months < min_contribution_months ? formatRupiah((
                        min_contribution_months - contribution.months) * contribution_charge) : formatRupiah(0);
                    var paid_bill = contribution.months ? formatRupiah(
                        contribution.months * contribution_charge) : formatRupiah(0);
                    contribution_unpaid.innerHTML = unpaid_month + ' - ' + unpaid_bill;
                    contribution_paid.innerHTML += ' - ' + paid_bill;
                } else {
                    contribution_unpaid.innerHTML = min_contribution_months + ' month - ' + formatRupiah(
                        min_contribution_months * contribution_charge);
                    contribution_paid.innerHTML = '0 month - ' + formatRupiah(0);
                }
            }

            if (selected_contribution_id) {
                var selected_contribution_card = document.getElementById('contribution_card_' + selected_contribution_id);
                selected_contribution_card.classList.remove('shadow', 'fw-normal');
                selected_contribution_card.classList.add('shadow-sm', 'fw-light');
            }

            contribution_card.classList.remove('shadow-sm', 'fw-light');
            contribution_card.classList.add('shadow', 'fw-normal');
            selected_contribution_id = user.id;

            if (auth_user_id == user.id && contribution_start > 0) {
                set_insert_trigger();
                set_insert_modal(user);
            } else {
                unset_insert_trigger();
                unset_insert_modal();
            }

            unset_receipt_trigger();
            if (contribution) {
                var index = 1;
                contribution.receipt.forEach(receipt => {
                    set_receipt_trigger(receipt, index);
                    available_receipt_index.push(index);
                    assign_set_receipt_modal(receipt, index);
                    index++;
                });
            }
        }

        function unset_receipt_trigger() {
            available_receipt_index.forEach(index => {
                var receipt = document.getElementById('receipt_modal_trigger_' + index);
                if (receipt) {
                    receipt.remove();
                }
            });
        }

        function set_receipt_trigger(receipt, index) {
            if (document.getElementById('receipt_modal_trigger_' + index)) {
                return;
            }
            const btnReceipt = document.createElement('button');
            btnReceipt.className = 'btn btn-sm btn-light position-relative me-2 mb-2';
            btnReceipt.innerHTML = index;
            btnReceipt.setAttribute('id', 'receipt_modal_trigger_' + index);
            btnReceipt.setAttribute('data-bs-toggle', 'modal');
            btnReceipt.setAttribute('data-bs-target', '#receiptContributionModal');

            const icon = document.createElement('i');
            icon.className = 'bi bi-receipt border-start ms-1 ps-1';
            btnReceipt.appendChild(icon);

            if (receipt.financial_id == null) {
                const badge = document.createElement('span');
                badge.className =
                    'position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle';
                btnReceipt.appendChild(badge);
            }

            const container = document.getElementById('contribution_receipt_container');
            container.appendChild(btnReceipt);
        }

        function assign_set_receipt_modal(receipt, index) {
            var trigger = document.getElementById('receipt_modal_trigger_' + index);
            trigger.onclick = function() {
                set_receipt_modal(receipt);
            };
        }

        function set_receipt_modal(receipt) {
            const image = document.getElementById('receipt_image');
            const months = document.getElementById('receipt_months');
            const download = document.getElementById('receipt_download');
            const name = document.getElementById('receipt_name');

            image.setAttribute('src', '/storage/images/receipt/contribution/' + receipt.receipt);
            download.setAttribute('href', '/storage/images/receipt/contribution/' + receipt.receipt);
            name.innerHTML = receipt.receipt;
            months.innerHTML = receipt.months;

            if (auth_user_roles_id == 2) {
                const validation_button = document.getElementById('validation_button');
                const validation_receipt_id = document.getElementById('validation_receipt_id');
                const validation_receipt_value = document.getElementById('validation_receipt_value');
                const delete_receipt_id = document.getElementById('delete_receipt_id');

                validation_button.innerHTML = receipt.financial_id == null ? 'Validate' : 'Unvalidate';
                validation_receipt_value.value = receipt.financial_id == null ? 'validate' : 'unvalidate';
                validation_receipt_id.value = receipt.id;
                delete_receipt_id.value = receipt.id;
            }
        }

        function set_insert_modal(user) {
            var modal_name = document.getElementById('insert_contribution_name');
            modal_name.innerHTML = user.name;
        }

        function unset_insert_modal() {
            var modal_name = document.getElementById('insert_contribution_name');
            modal_name.innerHTML = '';
        }

        function set_insert_trigger() {
            var insertTrigger = document.getElementById('insertContributionTrigger');

            if (insertTrigger.classList.contains('d-none')) {
                insertTrigger.classList.remove('d-none');
            }
        }

        function unset_insert_trigger() {
            var insertTrigger = document.getElementById('insertContributionTrigger');
            insertTrigger.classList.add('d-none');
        }

        function formatRupiah(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                currencyDisplay: 'code', // Use 'IDR' instead of 'Rp'
                minimumFractionDigits: 0, // Set to 0 to remove decimals
                maximumFractionDigits: 0 // Set to 0 to remove decimals
            }).format(value);
        }
    </script>
</x-app-layout>
