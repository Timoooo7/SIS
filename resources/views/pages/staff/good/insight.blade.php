<?php
$auth_user = Auth::user();
?>
<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('good.product') }}" class="text-decoration-none text-primary-emphasis"><span
                class="fw-light">{{ 'Goods Product' }}</span></a>
        <i class="bi bi-chevron-compact-right me-2"></i>{{ 'Insight' }}
    </x-slot>

    <div class="container pb-4">
        <div class="row mt-4 gx-2">
            {{-- Sale List --}}
            <div class="col-12 col-lg-6">
                {{-- Sale Filter --}}
                <div class="row">
                    <div class="col-12 ">
                        <nav class="navbar rounded bg-white shadow p-2 mx-2">
                            <form method="post" id="searchSaleForm" role="search"
                                action="{{ route('good.insight.filter', ['filter_name' => 'sale']) }}">
                                @csrf
                                @method('put')
                            </form>
                            <form method="post" id="formSaleFilter2"
                                action="{{ route('good.insight.filter', ['filter_name' => 'sale']) }}">
                                @csrf
                                @method('put')
                                <?php
                                $is_sale_transaction = $filter['sale']['category'] == 'transaction';
                                $sale_transaction_order = $filter['sale']['order'] == 'desc' ? 'desc' : 'asc';
                                $sale_transaction_icon = $is_sale_transaction && $sale_transaction_order == 'asc' ? 'up' : 'down';
                                $sale_transaction_value = $is_sale_transaction && $sale_transaction_order == 'asc' ? 'desc' : 'asc';
                                ?>
                                <input type="hidden" name="category" value="transaction">
                            </form>
                            <form method="post" id="formSaleFilter"
                                action="{{ route('good.insight.filter', ['filter_name' => 'sale']) }}">
                                @csrf
                                @method('put')
                                <?php
                                $is_sale_date = $filter['sale']['category'] == 'created_at';
                                $sale_date_order = $filter['sale']['order'] == 'desc' ? 'desc' : 'asc';
                                $sale_date_icon = $is_sale_date && $sale_date_order == 'asc' ? 'up' : 'down';
                                $sale_date_value = $is_sale_date && $sale_date_order == 'asc' ? 'desc' : 'asc';
                                ?>
                                <input type="hidden" name="category" value="created_at">
                            </form>
                            <div class="container d-block px-0 ">
                                <div class="row mb-2 px-3">
                                    <div class="col-12 border-bottom border-primary pb-1">
                                        <i class="bi bi-box-arrow-in-down fs-5 me-2"></i>
                                        <span class="text-primary-emphasis fs-5 ">{{ 'Goods Income' }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 d-flex">
                                        <div class="input-group input-group-sm bg-body-tertiary rounded">
                                            <button type="button"
                                                class="btn btn-sm rounded-0 rounded-start text-light bg-secondary">
                                                <i class="bi bi-funnel-fill"></i>
                                                <span
                                                    class="ms-1 ps-2 border-start border-light d-none d-md-inline">{{ 'Filter' }}</span>
                                            </button>
                                            <button type="submit" form="formSaleFilter"
                                                class="btn btn-sm btn-outline-secondary border-0 rounded-0 my-0"
                                                name="order" value="{{ $sale_date_value }}">
                                                <span>{{ 'Date' }}</span>
                                                <i class="bi bi-arrow-{{ $sale_date_icon }}"></i>
                                                <i class="bi bi-calendar2-event"></i>
                                            </button>
                                            <button type="submit" form="formSaleFilter2"
                                                class="btn btn-sm btn-outline-secondary border-0 rounded-0 my-0"
                                                name="order" value="{{ $sale_transaction_value }}">
                                                <span>{{ 'Transaction' }}</span>
                                                <i class="bi bi-sort-numeric-{{ $sale_transaction_icon }}-alt"></i>
                                            </button>
                                            {{-- Search Filter --}}
                                            <?php $keyword_focus = $filter['sale']['keyword'] ? $filter['sale']['keyword'] : ''; ?>
                                            <input type="text" name="keyword"
                                                class="form-control form-control-sm py-0 px-2" form="searchSaleForm"
                                                {{ $keyword_focus }} placeholder="{{ 'Search customer name' }}"
                                                value="{{ $filter['sale']['keyword'] }}">
                                            <button class="btn btn-outline-secondary border-0" type="submit"
                                                form="searchSaleForm">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
                {{-- Sale Item --}}
                <div class="row mb-3 px-2">
                    <div class="col-12">
                        <div
                            class="scroll-container-2 scroll-container-lg-2 mt-3 pb-2 bg-secondary bg-opacity-25 rounded px-2 shadow-sm">
                            @foreach ($sale_list as $sale)
                                <div class="card mt-2 shadow-sm">
                                    <div class="row py-1">
                                        <div class="col-12 d-flex">
                                            <span
                                                class="my-auto ms-2 text-dark fw-light text-nowrap">{{ format_date_time($sale->created_at) }}</span>
                                            <span
                                                class="my-auto ms-2 text-primary-emphasis text-nowrap">{{ ($sale->customer ? ' - ' : '') . substr($sale->customer, 0, 15) . (strlen($sale->customer) > 15 ? '...' : '') }}</span>
                                            <span
                                                class="ms-auto my-auto fw-light d-none d-md-inline">{{ 'trsansaction :' }}</span>
                                            <span
                                                class="ms-2 me-2 my-auto text-primary-emphasis d-none d-md-inline">{{ format_currency($sale->transaction) }}</span>
                                            {{-- Order Trigger Button --}}
                                            <button class="ms-auto ms-md-0 btn btn-sm btn-light position-relative me-2"
                                                data-bs-toggle="modal" data-bs-target="#salesOrderModal"
                                                onclick="setSaleOrder({{ $sale }},'{{ format_date_time($sale->updated_at) }}' )">
                                                <i class="bi bi-bag-check"> </i>
                                                @if ($sale->operational_id <= 0)
                                                    <span
                                                        class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                                    </span>
                                                @endif
                                            </button>
                                            @if ($auth_user->roles_id == 3)
                                                <div>
                                                    <div class="input-group input-group-sm">
                                                        {{-- Validation Button --}}
                                                        <button
                                                            class="text-decoration-none btn btn-sm btn-{{ $sale->operational_id > 0 ? 'success' : 'secondary' }}"
                                                            onclick="confirmation('{{ route('good.sale.validate', ['id' => $sale->id, 'valid' => $sale->operational_id > 0 ? 0 : 1]) }}','Confirm {{ $sale->operational_id > 0 ? 'unvalidate' : 'validate' }} this sale? ')">
                                                            <i
                                                                class="bi bi-{{ $sale->operational_id > 0 ? 'check2' : 'ban' }}"></i>
                                                        </button>
                                                        {{-- Delete Button --}}
                                                        <button
                                                            class="me-2 btn btn-sm btn-danger {{ $sale->operational_id > 0 ? 'disabled' : '' }}"
                                                            onclick="confirmation('{{ route('good.sale.delete', ['id' => $sale->id]) }}', '{{ 'Are you sure want to delete ' . $sale->customer . ' transaction from Goods Sale?' }}')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @if ($sale_list->count() == 0)
                                <div class="card shadow-sm mt-2 border-0">
                                    <span class="fst-italic fw-light mx-3 my-1">{{ 'No goods income found.' }}</span>
                                </div>
                            @endif
                            {{-- Sales Order Modal --}}
                            <div class="modal fade" id="salesOrderModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg px-3">
                                    <div class="modal-content shadow mt-5">
                                        <div class="modal-header py-1 ps-3 pe-2">
                                            <span class="modal-title fs-5 text-primary-emphasis">
                                                <i class="bi bi-bag-check border-secondary border-end me-2 pe-2"></i>
                                                {{ 'Sales Order' }}
                                            </span>
                                            <button type="button" class="btn btn-sm ms-auto" data-bs-dismiss="modal"
                                                aria-label="Close"><i class="bi bi-x-lg"></i></button>
                                        </div>
                                        <div class="modal-body bg-light p-1 px-3">
                                            <div class="row mt-1 justify-content-between">
                                                <div class="col-12 col-lg-5">
                                                    <div class="row gx-1">
                                                        <div class="col-4 text-end fw-light">{{ 'Cashier :' }}</div>
                                                        <div class="col-8 text-dark" id="sales_cashier"></div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-5">
                                                    <div class="row gx-1">
                                                        <div class="col-4 text-end fw-light">{{ 'Status :' }}
                                                        </div>
                                                        <div class="col-8 text-dark text-nowrap scroll-x-hidden"><span
                                                                id="sales_operational"></span></div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-5">
                                                    <div class="row gx-1">
                                                        <div class="col-4 text-end fw-light">{{ 'Customer :' }}</div>
                                                        <div class="col-8 text-dark" id="sales_customer"></div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-5">
                                                    <div class="row gx-1">
                                                        <div class="col-4 text-end fw-light">{{ 'Date :' }}</div>
                                                        <div class="col-8 text-dark" id="sales_date"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4 col-lg-2 text-end fw-light">
                                                    {{ 'Orders Item :' }}
                                                </div>
                                                <div class="col-8 col-lg-10 d-flex">
                                                    <div class="border-secondary-subtle border-1 border-top w-100 my-auto"
                                                        style="height: 1px;"></div>
                                                </div>
                                            </div>
                                            {{-- Order list --}}
                                            <div class="row justify-content-end">
                                                <div class="col-lg-10 col-12">
                                                    <div class="scroll-container-2 scroll-container-lg-2 bg-light"
                                                        id="order_list_container">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row justify-content-end">
                                                <div class="col-lg-10 col-8 d-flex">
                                                    <div class="border-secondary-subtle border-1 border-top w-100 my-auto"
                                                        style="height: 1px;"></div>
                                                </div>
                                            </div>
                                            <div class="row justify-content-end my-2">
                                                <div class="col-12">
                                                    <div class="row gx-1">
                                                        <div class="col text-end fw-light">
                                                            {{ 'Total transaction :' }}</div>
                                                        <div class="col-auto">
                                                            <span class="text-white bg-primary rounded px-2"
                                                                id="sales_transaction"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer p-1 px-2">
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
            {{-- Capital List --}}
            <div class="col-12 col-lg-6">
                {{-- Capital Filter --}}
                <div class="row">
                    <div class="col-12 ">
                        <nav class="navbar rounded bg-white shadow p-2 mx-2">
                            <form method="post" id="formInsightFilter2"
                                action="{{ route('good.insight.filter', ['filter_name' => 'capital']) }}">
                                @csrf
                                @method('put')
                                <?php
                                $is_capital_price = $filter['capital']['category'] == 'total_price';
                                $capital_price_order = $filter['capital']['order'] == 'desc' ? 'desc' : 'asc';
                                $capital_price_icon = $is_capital_price && $capital_price_order == 'asc' ? 'up' : 'down';
                                $capital_price_value = $is_capital_price && $capital_price_order == 'asc' ? 'desc' : 'asc';
                                ?>
                                <input type="hidden" name="category" value="total_price">
                            </form>
                            <form method="post" id="formInsightFilter"
                                action="{{ route('good.insight.filter', ['filter_name' => 'capital']) }}">
                                @csrf
                                @method('put')
                                <?php
                                $is_capital_date = $filter['capital']['category'] == 'created_at';
                                $capital_date_order = $filter['capital']['order'] == 'desc' ? 'desc' : 'asc';
                                $capital_date_icon = $is_capital_date && $capital_date_order == 'asc' ? 'up' : 'down';
                                $capital_date_value = $is_capital_date && $capital_date_order == 'asc' ? 'desc' : 'asc';
                                ?>
                                <input type="hidden" name="category" value="created_at">
                            </form>
                            <form method="post" id="formInsightFilter3"
                                action="{{ route('good.insight.filter', ['filter_name' => 'capital']) }}">
                                @csrf
                                @method('put')
                                <?php
                                $is_capital_name = $filter['capital']['category'] == 'name';
                                $capital_name_order = $filter['capital']['order'] == 'desc' ? 'desc' : 'asc';
                                $capital_name_icon = $is_capital_name && $capital_name_order == 'asc' ? 'up' : 'down';
                                $capital_name_value = $is_capital_name && $capital_name_order == 'asc' ? 'desc' : 'asc';
                                ?>
                                <input type="hidden" name="category" value="name">
                            </form>
                            <div class="container d-block px-0 ">
                                <div class="row mb-2 px-3">
                                    <div class="col-12 border-bottom border-primary pb-1">
                                        <i class="bi bi-box-arrow-up fs-5 me-2"></i>
                                        <span class="text-primary-emphasis fs-5 ">{{ 'Goods Expense' }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 d-flex">
                                        <div class="input-group bg-body-tertiary rounded">
                                            <button type="button"
                                                class="btn btn-sm rounded-0 rounded-start text-light bg-secondary">
                                                <i class="bi bi-funnel-fill"></i>
                                                <span
                                                    class="ms-1 ps-2 border-start border-light d-none d-md-inline">{{ 'Filter' }}</span>
                                            </button>
                                            <button type="submit" form="formInsightFilter3"
                                                class="btn btn-sm btn-outline-secondary border-0 rounded-0 my-0"
                                                name="order" value="{{ $capital_name_value }}">
                                                <span>{{ 'Name' }}</span>
                                                <i class="bi bi-sort-alpha-{{ $capital_name_icon }}-alt"></i>
                                            </button>
                                            <button type="submit" form="formInsightFilter"
                                                class="btn btn-sm btn-outline-secondary border-0 rounded-0 my-0"
                                                name="order" value="{{ $capital_date_value }}">
                                                <span>{{ 'Date' }}</span>
                                                <i class="bi bi-arrow-{{ $capital_date_icon }}"></i>
                                                <i class="bi bi-calendar2-event"></i>
                                            </button>
                                            <button type="submit" form="formInsightFilter2"
                                                class="btn btn-sm btn-outline-secondary border-0 rounded-0 my-0"
                                                name="order" value="{{ $capital_price_value }}">
                                                <span>{{ 'Price' }}</span>
                                                <i class="bi bi-sort-numeric-{{ $capital_price_icon }}-alt"></i>
                                            </button>
                                        </div>
                                        @if ($auth_user->product)
                                            <!-- Button trigger Add Capital Modal -->
                                            <button class="ms-2 btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#addCapital">
                                                <i class="bi bi-plus-lg"></i>
                                            </button>
                                            <div class="modal fade" id="addCapital" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content shadow mx-3">
                                                        <div class="modal-header py-1 ps-3 pe-2">
                                                            <span class="modal-title fs-5 text-primary-emphasis"
                                                                id="exampleModalLabel"><i
                                                                    class="bi bi-cart-plus border-secondary border-end me-2 pe-2"></i>
                                                                New Goods Expense Item
                                                            </span>
                                                            <button type="button" class="btn btn-sm ms-auto"
                                                                data-bs-dismiss="modal" aria-label="Close"><i
                                                                    class="bi bi-x-lg"></i></button>
                                                        </div>
                                                        <form method="post" enctype="multipart/form-data"
                                                            action="{{ route('good.capital.add') }}">
                                                            @csrf
                                                            @method('put')
                                                            <div class="modal-body bg-light">
                                                                <div class="row justify-content-center">
                                                                    <div class="col-4 col-lg-3">
                                                                        <label for="add_capital_name"
                                                                            class="form-label d-inline-block">Name</label>
                                                                    </div>
                                                                    <div class="col-8 col-lg-7">
                                                                        <input type="text"
                                                                            class="form-control form-control-sm d-inline-block"
                                                                            name="name" id="add_capital_name"
                                                                            value="{{ old('name') }}" required>
                                                                        <x-input-error :messages="$errors->get('name')"
                                                                            class="mt-2" />
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-2 justify-content-center">
                                                                    <div class="col-4 col-lg-3">
                                                                        <label for="add_capital_price"
                                                                            class="form-label d-inline-block">Price</label>
                                                                    </div>
                                                                    <div class="col-8 col-lg-7">
                                                                        <input type="number"
                                                                            class="form-control form-control-sm d-inline-block"
                                                                            name="price" id="add_capital_price"
                                                                            value="{{ old('price') }}"
                                                                            oninput="return setTotalPrice('capital')"
                                                                            placeholder="Type numbers only" required>
                                                                        <x-input-error :messages="$errors->get('price')"
                                                                            class="mt-2" />
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-2 justify-content-center">
                                                                    <div class="col-4 col-lg-3">
                                                                        <label for="add_capital_unit"
                                                                            class="form-label d-inline-block">Unit</label>
                                                                    </div>
                                                                    <div class="col-8 col-lg-7">
                                                                        <input type="text"
                                                                            class="form-control form-control-sm d-inline-block"
                                                                            name="unit"
                                                                            placeholder="gram, ml, pcs, etc.."
                                                                            id="add_capital_unit"
                                                                            value="{{ old('unit') }}" required>
                                                                        <x-input-error :messages="$errors->get('unit')"
                                                                            class="mt-2" />
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-2 justify-content-center">
                                                                    <div class="col-4 col-lg-3">
                                                                        <label for="add_capital_qty"
                                                                            class="form-label d-inline-block">Quantity</label>
                                                                    </div>
                                                                    <div class="col-8 col-lg-7">
                                                                        <input type="number"
                                                                            class="form-control form-control-sm d-inline-block"
                                                                            name="qty" id="add_capital_qty"
                                                                            value="{{ old('qty') }}"
                                                                            oninput="return setTotalPrice('capital')"
                                                                            placeholder="Type numbers only" required>
                                                                        <x-input-error :messages="$errors->get('qty')"
                                                                            class="mt-2" />
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-2 justify-content-center">
                                                                    <div class="col-4 col-lg-3">
                                                                        <label
                                                                            class="form-label">{{ 'Total Price' }}</label>
                                                                    </div>
                                                                    <div class="col-8 col-lg-7">
                                                                        <span id="add_capital_total"></span>
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-2 justify-content-center">
                                                                    <div class="col-4 col-lg-3">
                                                                        <label for="add_capital_receipt"
                                                                            class="form-label d-inline-block">{{ 'Receipt' }}</label>
                                                                    </div>
                                                                    <div class="col-8 col-lg-7">
                                                                        <input type="file"
                                                                            class="form-control form-control-sm"
                                                                            name="receipt" id="add_capital_receipt"
                                                                            value="{{ old('receipt') }}">
                                                                        <x-input-error :messages="$errors->get('receipt')"
                                                                            class="mt-2" />
                                                                        <div class="input-group input-group-sm"
                                                                            id="add_capital_receipt_same_container"
                                                                            hidden>
                                                                            <label for="add_capital_receipt_same"
                                                                                class="input-group-text">Same
                                                                                as
                                                                                item</label>
                                                                            <select name="receipt_same"
                                                                                class="form-select py-0 d-inline"
                                                                                id="add_capital_receipt_same">
                                                                                <?php $i = 1; ?>
                                                                                @foreach ($capital_list as $capital)
                                                                                    <?php $default = $i === 1 ? 'selected' : ''; ?>
                                                                                    <option
                                                                                        value="{{ $capital->id }}"
                                                                                        {{ $default }}>
                                                                                        {{ $capital->name }}
                                                                                    </option>
                                                                                    <?php $i++; ?>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        @if ($capital_list->count() > 0)
                                                                            <label for="same_receipt_check"
                                                                                class="inline-flex items-center mt-1"
                                                                                onclick="sameReceipt()">
                                                                                <input id="same_receipt_check"
                                                                                    type="checkbox"
                                                                                    name="same_receipt_check"
                                                                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                                                                <span
                                                                                    class="ms-2 text-sm text-gray-600">{{ __('same as exist item') }}</span>
                                                                            </label>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer p-1">
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-primary ">{{ 'Add' }}</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
                {{-- Capital Item --}}
                <div class="row mb-3 px-2">
                    <div class="col-12">
                        <div
                            class="scroll-container-2 scroll-container-lg-3 mt-3 pb-2 bg-secondary bg-opacity-25 rounded shadow-sm px-2">
                            <?php $i = 1; ?>
                            @foreach ($capital_list as $capital)
                                <div class="card shadow-sm mt-2 border-0 bg-white">
                                    <div class="row py-1">
                                        <div class="col-12 d-flex">
                                            <span
                                                class="my-auto text-primary-emphasis ms-2">{{ $capital->name }}</span>
                                            <span
                                                class="fw-light my-auto  ms-2 d-none d-lg-flex">{{ '- ' . format_currency($capital->price) . ' /' . $capital->unit }}</span>
                                            <span
                                                class="fw-light my-auto  ms-auto d-none d-lg-flex">{{ 'total (' . $capital->qty . ') : ' }}</span>
                                            <span
                                                class="fw-normal my-auto  mx-2 d-none d-lg-flex">{{ format_currency($capital->total_price) }}</span>
                                            {{-- Receipt Trigger Button --}}
                                            <button class="ms-auto ms-lg-1 me-2 btn btn-sm btn-light position-relative"
                                                data-bs-toggle="modal" data-bs-target="#receiptCapitalModal"
                                                onclick="setCapitalReceipt({{ $capital }})">
                                                @if ($capital->operational_id == null)
                                                    <span
                                                        class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                                                @endif
                                                <i class="bi bi-receipt"> </i>
                                            </button>
                                            @if ($auth_user->product)
                                                {{-- Delete Button --}}
                                                <div class="me-2"
                                                    onclick="{{ $capital->operational_id <= 0 ? '' : 'notif("This goods expense has been validated by Operational Officer. You can not delete it, please contact Operational Officer.")' }}">
                                                    <button
                                                        class="btn btn-sm btn-danger {{ $capital->operational_id > 0 ? 'disabled' : '' }}"
                                                        onclick="confirmation('{{ route('good.capital.delete', ['id' => $capital->id]) }}', '{{ 'Are you sure want to delete ' . $capital->name . ' from Goods Expense?' }}')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row d-lg-none">
                                        <div class="col-12 d-flex">
                                            <span
                                                class="fw-light ms-2 ">{{ format_currency($capital->price) . ' /' . $capital->unit }}</span>
                                            <span
                                                class="fw-light ms-auto">{{ 'total (' . $capital->qty . ') : ' }}</span>
                                            <span
                                                class="fw-normal mx-2">{{ format_currency($capital->total_price) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <?php $i++; ?>
                            @endforeach
                            @if ($capital_list->count() == 0)
                                <div class="card">
                                    <span class="fst-italic fw-light">{{ 'No goods capital found.' }}</span>
                                </div>
                            @endif
                            <!-- Capital Receipt Modal -->
                            <div class="modal fade" id="receiptCapitalModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content shadow mx-3 mt-5">
                                        <div class="modal-header py-1 ps-3 pe-2">
                                            <span class="modal-title fs-5 text-primary-emphasis">
                                                <i class="bi bi-receipt border-secondary border-end me-2 pe-2"></i>
                                                {{ 'Goods Expense Receipt' }}
                                            </span>
                                            <button type="button" class="btn btn-sm ms-auto" data-bs-dismiss="modal"
                                                aria-label="Close"><i class="bi bi-x-lg"></i></button>
                                        </div>
                                        <div class="modal-body bg-light p-1 px-3">
                                            <div class="row justify-content-center mt-2">
                                                <div class="col-12 d-flex">
                                                    <img src="" alt="image" class="rounded mx-auto"
                                                        style="width: 100%; height 100%;  object-fit: contain; max-height:320px;"
                                                        id="capital_receipt_image">
                                                </div>
                                            </div>
                                            <div class="row mt-2 justify-content-center">
                                                <div class="col-12 d-flex">
                                                    <span class="fw-light ms-auto">{{ 'Status : ' }}</span>
                                                    <span class="fw-normal text-primary-emphasis me-auto ms-1"
                                                        id="capital_receipt_status"></span>
                                                </div>
                                            </div>
                                            <div class="row justify-content-center">
                                                <div class="col-12 d-flex">
                                                    <a href="" target="blank" style="text-decoration-none"
                                                        class="mx-auto" id="capital_receipt_download" download>
                                                        <button class="btn btn-sm btn-light">
                                                            <span class="fw-light" id="capital_receipt_name"></span>
                                                            <i class="bi bi-download text-primary"></i>
                                                        </button>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer p-1 px-2">
                                            @if ($auth_user->roles_id == 3)
                                                <form method="post" action="{{ route('good.capital.validate') }}"
                                                    enctype="multipart/form-data" id="formCapitalReceiptValidation">
                                                    @csrf
                                                    @method('put')
                                                    <input type="hidden" name="receipt_id" id="capital_receipt_id">
                                                </form>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="submit" form="formCapitalReceiptValidation"
                                                        class="btn btn-sm btn-primary"
                                                        id="capital_receipt_button"></button>
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
        </div>
    </div>

    <script>
        const auth_user = <?php echo $auth_user; ?>;

        function sameReceipt() {
            let receiptUpload = document.getElementById('add_capital_receipt');
            let receiptSame = document.getElementById('add_capital_receipt_same_container');
            let sameReceiptCheck = document.getElementById('same_receipt_check');
            if (sameReceiptCheck.checked == true) {
                receiptUpload.setAttribute('hidden', '');
                receiptSame.removeAttribute('hidden');
            } else {
                receiptUpload.removeAttribute('hidden');
                receiptSame.setAttribute('hidden', '');
            }
        }

        function setSaleOrder(sale, updated_at) {
            console.log(sale.customer);
            // Set Sales Detail
            const operational = document.getElementById('sales_operational');
            const cashier = document.getElementById('sales_cashier');
            const customer = document.getElementById('sales_customer');
            const date = document.getElementById('sales_date');
            const transaction = document.getElementById('sales_transaction');

            operational.innerHTML = sale.operational_id > 0 ? 'validated by ' + sale.operational.name : 'unvalidated';
            cashier.innerHTML = sale.cashier.name;
            customer.innerHTML = sale.customer;
            date.innerHTML = updated_at;
            transaction.innerHTML = formatRupiah(sale.transaction);

            // Set Order List
            const container = document.getElementById('order_list_container');
            container.innerHTML = '';

            var order_list = sale.order;
            for (let i = 0; i < order_list.length; i++) {
                const order = order_list[i];

                const row = document.createElement('div');
                row.setAttribute('class', 'row mb-1');

                const col = document.createElement('div');
                col.setAttribute('class', 'col-12 d-flex');
                row.appendChild(col);

                const span_amount = document.createElement('span');
                span_amount.setAttribute('class', 'ms-2 px-1 rounded bg-white');
                span_amount.innerHTML = order.amount;
                col.appendChild(span_amount);

                const div = document.createElement('div');
                div.setAttribute('class', 'scroll-x-hidden mx-2 text-nowrap');
                col.appendChild(div);

                const span_product = document.createElement('span');
                span_product.setAttribute('class', 'text-secondary me-2');
                span_product.innerHTML = order.variant.product.name;
                div.appendChild(span_product);

                const span_variant = document.createElement('span');
                span_variant.setAttribute('class', 'text-dark');
                span_variant.innerHTML = order.variant.name + ' - ';
                div.appendChild(span_variant);

                const span_price = document.createElement('span');
                span_price.setAttribute('class', 'text-dark fw-light');
                span_price.innerHTML = formatRupiah(order.variant.price);
                div.appendChild(span_price)

                const span_total = document.createElement('span');
                span_total.setAttribute('class', 'text-dark ms-auto me-2');
                span_total.innerHTML = formatRupiah(order.amount * order.variant.price);
                col.appendChild(span_total);

                // insert order item to order container
                container.appendChild(row);
            }
        }

        function setCapitalReceipt(capital) {
            let image = document.getElementById('capital_receipt_image');
            let download = document.getElementById('capital_receipt_download');
            let name = document.getElementById('capital_receipt_name');
            let status = document.getElementById('capital_receipt_status');

            status.innerHTML = capital.operational_id == null ? 'Unvalidated' : 'Validated by ' + capital.operational.name;
            image.setAttribute('src', '/storage/images/receipt/goods/expense/' + capital.receipt);
            download.setAttribute('href', '/storage/images/receipt/goods/expense/' + capital.receipt);
            name.innerHTML = capital.receipt;

            if (auth_user.roles_id == 3) {
                let id = document.getElementById('capital_receipt_id');
                let button = document.getElementById('capital_receipt_button');

                id.value = capital.id;
                button.innerHTML = capital.operational_id == null ? 'Validate' : 'Unvalidate';
            }
        }

        function setTotalPrice(type) {
            const price = document.getElementById('add_' + type + '_price');
            const qty = document.getElementById('add_' + type + '_qty');
            const total = document.getElementById('add_' + type + '_total');

            total.innerHTML = formatRupiah(price.value * qty.value);
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
