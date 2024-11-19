<?php
use Illuminate\Support\Carbon;

$auth_user = Auth::user();
?>
<x-app-layout>
    <x-slot name="header">
        {{ 'Blaterian Goods Balance' }}
    </x-slot>

    <div class="container pb-4">
        {{-- Dashboard --}}
        <div class="row px-2">
            <div class="col-12">
                <div class="scroll-container-horizontal-lg scroll-container-horizontal bg-light rounded shadow mt-4">
                    {{-- Balance --}}
                    <div class="col-12 col-lg-6 col-xl-4 mt-3 mx-3 d-inline-block">
                        <div class="card border-0 shadow text-bg-primary">
                            <div class="card-body">
                                <div class="row m-0 p-0">
                                    <div class="col-auto">
                                        <div class="bi bi-wallet2 my-auto" style="font-size:300%"> </div>
                                    </div>
                                    <div class="col-1 d-flex">
                                        <div class="border-start border-secondary-emphasis h-100"></div>
                                    </div>
                                    <div class="col">
                                        <p class="text-white mb-1 text-opacity-75 fs-6 fw-light ">{{ 'Balance' }}
                                        </p>
                                        <span class="fs-3 d-flex">
                                            {{ format_currency($balance->balance, 'IDR') }}
                                            <a href="{{ route('good.balance', ['default_tab' => 1, 'refresh' => true]) }}"
                                                class="btn btn-sm btn-primary my-auto ms-auto">
                                                <i class="bi bi-arrow-clockwise fs-4"></i>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Cash In --}}
                    <div class="col-12 col-lg-6 col-xl-4 mt-3 me-3 d-inline-block">
                        <div class="card border-0 shadow text-bg-secondary">
                            <div class="card-body">
                                <div class="row m-0 p-0">
                                    <div class="col-auto">
                                        <div class="bi bi-box-arrow-in-down my-auto" style="font-size:300%"> </div>
                                    </div>
                                    <div class="col-1 d-flex">
                                        <div class="border-start border-secondary-emphasis h-100"></div>
                                    </div>
                                    <div class="col-auto">
                                        <p class="text-white mb-1 text-opacity-75 fs-6 fw-light ">{{ 'Cash In' }}
                                        </p>
                                        <span class="fs-3">{{ format_currency($balance->income, 'IDR') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Cash Out --}}
                    <div class="col-12 col-lg-6 col-xl-4 mt-3 me-3 d-inline-block">
                        <div class="card border-0 shadow text-bg-secondary">
                            <div class="card-body">
                                <div class="row m-0 p-0">
                                    <div class="col-auto">
                                        <div class="bi bi-box-arrow-up my-auto" style="font-size:300%"> </div>
                                    </div>
                                    <div class="col-1 d-flex">
                                        <div class="border-start border-secondary-emphasis h-100"></div>
                                    </div>
                                    <div class="col-auto">
                                        <p class="text-white mb-1 text-opacity-75 fs-6 fw-light ">{{ 'Cash Out' }}
                                        </p>
                                        <span class="fs-3">{{ format_currency($balance->expense, 'IDR') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Profit --}}
                    <div class="col-12 col-lg-6 col-xl-4 mt-3 me-3 d-inline-block">
                        <div class="card border-0 shadow text-bg-warning">
                            <div class="card-body text-warning-emphasis">
                                <div class="row m-0 p-0">
                                    <div class="col-auto">
                                        <div class="bi bi-wallet2 my-auto" style="font-size:300%"> </div>
                                    </div>
                                    <div class="col-1 d-flex">
                                        <div class="border-start border-secondary h-100"></div>
                                    </div>
                                    <div class="col-auto">
                                        <p class="mb-1 text-opacity-75 fs-6 fw-light ">{{ 'Profit' }}</p>
                                        <span
                                            class="fs-3">{{ format_currency($total_income - $total_expense, 'IDR') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Income --}}
                    <div class="col-12 col-lg-6 col-xl-4 mt-3 me-3 d-inline-block">
                        <div class="card border-0 shadow text-bg-light">
                            <div class="card-body text-dark">
                                <div class="row m-0 p-0">
                                    <div class="col-auto">
                                        <div class="bi bi-box-arrow-in-down my-auto" style="font-size:300%"> </div>
                                    </div>
                                    <div class="col-1 d-flex">
                                        <div class="border-start border-secondary h-100"></div>
                                    </div>
                                    <div class="col-auto">
                                        <p class="text-secondary mb-1 text-opacity-100 fs-6 fw-light ">
                                            {{ 'Income' }}</p>
                                        <span class="fs-3">{{ format_currency($total_income, 'IDR') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Expense --}}
                    <div class="col-12 col-lg-6 col-xl-4 my-2 me-3 d-inline-block">
                        <div class="card border-0 shadow text-bg-light">
                            <div class="card-body">
                                <div class="row m-0 p-0">
                                    <div class="col-auto">
                                        <div class="bi bi-box-arrow-up my-auto" style="font-size:300%"> </div>
                                    </div>
                                    <div class="col-1 d-flex">
                                        <div class="border-start border-secondary h-100"></div>
                                    </div>
                                    <div class="col-auto">
                                        <p class="text-secondary mb-1 text-opacity-100 fs-6 fw-light ">
                                            {{ 'Expense' }}</p>
                                        <span class="fs-3">{{ format_currency($total_expense, 'IDR') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Content --}}
        <div class="row gx-2">
            {{-- Charts --}}
            <div class="col-12 col-lg-5 mt-3">
                <h5
                    class="text-secondary fw-bold d-flex bg-white rounded-top mx-2 py-1 mb-0 border-1 border-bottom border-primary">
                    <span class="mx-auto">{{ 'Top 5 Sales Product' }}
                        <i tabindex="0" data-bs-toggle="popover" data-bs-custom-class="custom-popover"
                            data-bs-placement="top" data-bs-trigger="hover" data-bs-title="{{ 'Sales Chart' }}"
                            data-bs-content="This chart is displaying top 5 product sales. Click the product label button on the top of chart to turn on/off specific data you want."
                            class="bi bi-question-circle"></i>
                    </span>
                    <button class="btn btn-sm btn-light rounded d-lg-none d-inline-block me-1" type="button"
                        data-bs-toggle="collapse" data-bs-target="#chartCollapse" aria-expanded="false">
                        <i class="bi bi-eye" id="salesIcon" onclick="collapse_chart()"></i>
                    </button>
                </h5>
                <div class="collapse show" id="chartCollapse">
                    <canvas id="salesChart" class="bg-white shadow-sm mx-2 px-3 rounded-bottom"></canvas>
                </div>
            </div>
            {{-- Content --}}
            <div class="col-12 col-lg-7">
                <div class="row">
                    <div class="col-12">
                        {{-- Content Tab Navigation for Medium and Smaller screen --}}
                        <ul class="nav nav-tabs mx-2 border-0 mt-3">
                            <li class="nav-item"><a id="tab_1" onclick="show_tab(1)"
                                    class="nav-link px-3 py-1  border {{ $default_tab == 1 ? 'active bg-secondary text-white' : 'bg-white' }}">{{ 'Cash In' }}
                                </a>
                            </li>
                            <li class="nav-item"><a id="tab_2" onclick="show_tab(2)"
                                    class="nav-link px-3 py-1  border {{ $default_tab == 2 ? 'active bg-secondary text-white' : 'bg-white' }}">{{ 'Cash Out' }}</a>
                            </li>
                        </ul>
                    </div>
                    {{-- Income list --}}
                    <div id="content_1" class="col-12 " {{ $default_tab == 1 ? '' : 'hidden' }}>
                        {{-- Income filter control --}}
                        <div class="row justify-content-center px-2">
                            <div class="col-12 ">
                                <nav class="navbar rounded bg-white shadow-sm p-2">
                                    <form method="post" id="formBalanceIncome"
                                        action="{{ route('good.balance.filter.cash_in') }}">
                                        @csrf
                                        @method('put')
                                        <?php
                                        $is_income_date = $filter['cash_in']['category'] == 'updated_at';
                                        $income_date_order = $filter['cash_in']['order'] == 'desc' ? 'desc' : 'asc';
                                        $income_date_icon = $is_income_date && $income_date_order == 'asc' ? 'up' : 'down';
                                        $income_date_value = $is_income_date && $income_date_order == 'asc' ? 'desc' : 'asc';
                                        ?>
                                        <input type="hidden" name="category" value="updated_at">
                                    </form>
                                    <form method="post" id="formBalanceIncome2"
                                        action="{{ route('good.balance.filter.cash_in') }}">
                                        @csrf
                                        @method('put')
                                        <?php
                                        $is_income_price = $filter['cash_in']['category'] == 'price';
                                        $income_price_order = $filter['cash_in']['order'] == 'desc' ? 'desc' : 'asc';
                                        $income_price_icon = $is_income_price && $income_price_order == 'asc' ? 'up' : 'down';
                                        $income_price_value = $is_income_price && $income_price_order == 'asc' ? 'desc' : 'asc';
                                        ?>
                                        <input type="hidden" name="category" value="price">
                                    </form>
                                    <form method="post" id="formBalanceIncome3"
                                        action="{{ route('good.balance.filter.cash_in') }}">
                                        @csrf
                                        @method('put')
                                        <?php
                                        $is_cash_in_income = $filter['cash_in']['income'] == 'income';
                                        $cash_in_income_class = $is_cash_in_income ? 'active' : '';
                                        $cash_in_income_value = $is_cash_in_income ? 'active' : 'income';
                                        ?>
                                        <input type="hidden" name="income" value="{{ $cash_in_income_value }}">
                                    </form>
                                    <div class="container d-block px-0 bg-body-tertiary rounded">
                                        <div class="row">
                                            <div class="input-group">
                                                <button type="button" form="formBalanceIncome"
                                                    class="btn btn-sm rounded-0 rounded-start text-light bg-secondary">
                                                    <i class="bi bi-funnel-fill"></i>
                                                    <span
                                                        class="ms-1 ps-2 border-start border-light d-none d-md-inline">{{ 'Filter' }}</span>
                                                </button>
                                                <button type="submit" form="formBalanceIncome"
                                                    class="btn btn-sm btn-outline-secondary border-0 rounded-0 my-0"
                                                    name="order" value="{{ $income_date_value }}">
                                                    <span class="">{{ 'Date' }}</span>
                                                    <i class="bi bi-sort-numeric-{{ $income_date_icon }}-alt"></i>
                                                </button>
                                                <button type="submit" form="formBalanceIncome2"
                                                    class="btn btn-sm btn-outline-secondary border-0 rounded-0 my-0"
                                                    name="order" value="{{ $income_price_value }}">
                                                    <span class="">{{ 'Price' }}</span>
                                                    <i class="bi bi-sort-numeric-{{ $income_price_icon }}-alt"></i>
                                                </button>
                                                <button type="submit" form="formBalanceIncome3"
                                                    class="btn btn-sm btn-outline-secondary {{ $cash_in_income_class }} border-0 rounded-0 my-0">
                                                    <span class="">{{ 'Income' }}</span>
                                                    <i class="bi bi-box-arrow-in-down"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                        </div>
                        {{-- Income list item --}}
                        <div class="bg-secondary rounded shadow-sm mx-2 bg-opacity-25">
                            <div class="scroll-container-2 scroll-container-lg mt-2 pb-2">
                                <?php
                                $i = 1;
                                ?>
                                @foreach ($income as $in)
                                    @if ($in->category_detail())
                                        <a target="_blank"
                                            href="{{ $in->category == 'goods income' ? route('good.insight') : route('program', ['id' => $in->category_id]) }}"
                                            class="text-decoration-none">
                                            <div class="card shadow-sm mt-2 mx-2 card-bg-hover ">
                                                <div class="row mt-1 mb-md-1">
                                                    <div class="col-12 d-flex">
                                                        <span
                                                            class="rounded bg-light text-secondary py-0 px-2 fw-light ms-2">{{ $i }}</span>
                                                        <span
                                                            class="ms-2 fw-light">{{ $in->category == 'program disbursement' ? 'PD : ' : 'GI : ' }}</span>
                                                        <span
                                                            class="ms-2 text-primary-emphasis scroll-x-hidden text-nowrap">
                                                            {{ $in->category == 'goods income' ? $in->category_detail()->customer . '`s transaction' : $in->category_detail()->name }}</span>
                                                        <span
                                                            class="fw-light ms-2 d-none d-md-inline">{{ ' - ' . format_currency($in->price) }}</span>
                                                        <span
                                                            class="fst-italic ms-auto me-2 d-none d-md-inline">{{ format_date_time($in->updated_at) }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-1 d-md-none">
                                                    <div class="col-12 d-flex">
                                                        <span
                                                            class=" fw-light ms-3">{{ format_currency($in->price) }}</span>
                                                        <span
                                                            class="fst-italic ms-auto me-2">{{ format_date_time($in->updated_at) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @endif
                                    <?php $i++; ?>
                                @endforeach
                                @if ($income->count() <= 0)
                                    <div class="card shadow-sm mt-2 mx-2 card-bg-hover ">
                                        <div class="row mt-1 mb-md-1">
                                            <div class="col-12 d-flex">
                                                <span
                                                    class="fw-light fst-italic px-2">{{ 'No cash in found.' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- Expense list --}}
                    <div id="content_2" class="col-12 " {{ $default_tab == 2 ? '' : 'hidden' }}>
                        {{-- Expense filter control --}}
                        <div class="row justify-content-center px-2">
                            <div class="col-12">
                                <nav class="navbar rounded bg-white shadow-sm p-2">
                                    <form method="post" id="formBalanceExpense"
                                        action="{{ route('good.balance.filter.cash_out') }}">
                                        @csrf
                                        @method('put')
                                        <?php
                                        $is_expense_date = $filter['cash_out']['category'] == 'updated_at';
                                        $expense_date_order = $filter['cash_out']['order'] == 'desc' ? 'desc' : 'asc';
                                        $expense_date_icon = $is_expense_date && $expense_date_order == 'asc' ? 'up' : 'down';
                                        $expense_date_value = $is_expense_date && $expense_date_order == 'asc' ? 'desc' : 'asc';
                                        $expense_date_button = $is_expense_date && $is_expense_date ? '' : '';
                                        ?>
                                        <input type="hidden" name="category" value="updated_at">
                                    </form>
                                    <form method="post" id="formBalanceExpense2"
                                        action="{{ route('good.balance.filter.cash_out') }}">
                                        @csrf
                                        @method('put')
                                        <?php
                                        $is_expense_price = $filter['cash_out']['category'] == 'price';
                                        $expense_price_order = $filter['cash_out']['order'] == 'desc' ? 'desc' : 'asc';
                                        $expense_price_icon = $is_expense_price && $expense_price_order == 'asc' ? 'up' : 'down';
                                        $expense_price_value = $is_expense_price && $expense_price_order == 'asc' ? 'desc' : 'asc';
                                        $expense_price_button = $is_expense_price && $is_expense_price ? '' : '';
                                        ?>
                                        <input type="hidden" name="category" value="price">
                                    </form>
                                    <form method="post" id="formBalanceExpense3"
                                        action="{{ route('good.balance.filter.cash_out') }}">
                                        @csrf
                                        @method('put')
                                        <?php
                                        $is_cash_in_expense = $filter['cash_out']['expense'] == 'expense';
                                        $cash_in_expense_class = $is_cash_in_expense ? 'active' : '';
                                        $cash_in_expense_value = $is_cash_in_expense ? 'active' : 'expense';
                                        ?>
                                        <input type="hidden" name="expense" value="{{ $cash_in_expense_value }}">
                                    </form>
                                    <div class="container d-block px-0">
                                        <div class="row">
                                            <div class="col-12 d-flex">
                                                <div class="input-group bg-body-tertiary rounded">
                                                    <button type="button" form="formBalanceExpense"
                                                        class="btn btn-sm rounded-0 rounded-start text-light bg-secondary">
                                                        <i class="bi bi-funnel-fill"></i>
                                                        <span
                                                            class="ms-1 ps-2 border-start border-light d-none d-md-inline">{{ 'Filter' }}</span>
                                                    </button>
                                                    <button type="submit" form="formBalanceExpense"
                                                        class="btn btn-sm btn-outline-secondary border-0 {{ $expense_date_button }} rounded-0 my-0"
                                                        name="order" value="{{ $expense_date_value }}">
                                                        <span class="">{{ 'Date' }}</span>
                                                        <i
                                                            class="bi bi-sort-numeric-{{ $expense_date_icon }}-alt"></i>
                                                    </button>
                                                    <button type="submit" form="formBalanceExpense2"
                                                        class="btn btn-sm btn-outline-secondary border-0 {{ $expense_price_button }} rounded-0 my-0"
                                                        name="order" value="{{ $expense_price_value }}">
                                                        <span class="">{{ 'Price' }}</span>
                                                        <i
                                                            class="bi bi-sort-numeric-{{ $expense_price_icon }}-alt"></i>
                                                    </button>
                                                    <button type="submit" form="formBalanceExpense3"
                                                        class="btn btn-sm btn-outline-secondary {{ $cash_in_expense_class }} border-0 rounded-0 my-0">
                                                        <span class="">{{ 'Expense' }}</span>
                                                        <i class="bi bi-box-arrow-in-down"></i>
                                                    </button>
                                                </div>
                                                @if ($auth_user->roles_id == 3)
                                                    <button class="btn btn-sm btn-primary ms-2" data-bs-toggle="modal"
                                                        data-bs-target="#addExpenseModal"><i
                                                            class="bi bi-upload"></i></button>
                                                    <div class="modal fade" id="addExpenseModal" tabindex="-1"
                                                        aria-labelledby="exampleModalLabel" aria-hidden="true">

                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content shadow mx-3">
                                                                <div class="modal-header py-1 ps-3 pe-2">
                                                                    <span
                                                                        class="modal-title fs-5 text-primary-emphasis"
                                                                        id="exampleModalLabel"><i
                                                                            class="bi bi-upload border-secondary border-end me-2 pe-2"></i>
                                                                        <div class="scroll-x-hidden d-inline">
                                                                            <span>
                                                                                {{ 'Withdraw' }}
                                                                            </span>
                                                                        </div>
                                                                    </span>
                                                                    <button type="button" class="btn btn-sm ms-auto"
                                                                        data-bs-dismiss="modal" aria-label="Close"><i
                                                                            class="bi bi-x-lg"></i></button>
                                                                </div>
                                                                <form method="post" enctype="multipart/form-data"
                                                                    action="{{ route('good.balance.withdraw') }}">
                                                                    @csrf
                                                                    @method('put')
                                                                    <div class="modal-body bg-light">
                                                                        <div class="row mb-2">
                                                                            <div class="col-12 d-flex">
                                                                                <span
                                                                                    class="mx-auto text-secondary fst-italic">{{ 'Withdraw money to SEEO Cash Flow' }}</span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_expense_name"
                                                                                    class="form-label d-inline-block">Name</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="text"
                                                                                    class="form-control form-control-sm d-inline-block"
                                                                                    name="name"
                                                                                    id="add_expense_name"
                                                                                    value="{{ old('name') }}"
                                                                                    placeholder="Blaterian Goods Withdraw"
                                                                                    required>
                                                                                <x-input-error :messages="$errors->get('name')"
                                                                                    class="mt-2" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_expense_price"
                                                                                    class="form-label d-inline-block">Price</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="number"
                                                                                    class="form-control form-control-sm d-inline-block"
                                                                                    name="price"
                                                                                    id="add_expense_price"
                                                                                    value="{{ old('price') }}"
                                                                                    placeholder="Type numbers only"
                                                                                    required>
                                                                                <x-input-error :messages="$errors->get('price')"
                                                                                    class="mt-2" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_disbursement_receipt"
                                                                                    class="form-label d-inline-block">{{ 'Receipt' }}</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="file"
                                                                                    class="form-control form-control-sm"
                                                                                    name="receipt"
                                                                                    id="add_disbursement_receipt"
                                                                                    value="{{ old('receipt') }}">
                                                                                <x-input-error :messages="$errors->get('receipt')"
                                                                                    class="mt-2" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer p-1">
                                                                        <button type="submit"
                                                                            class="btn btn-sm btn-primary ">{{ 'Send' }}</button>
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
                        {{-- Expense list item --}}
                        <div class="bg-secondary rounded shadow-sm mx-2 bg-opacity-25">
                            <div class="scroll-container-2 scroll-container-lg mt-2 pb-2">
                                <?php
                                $i = 1;
                                ?>
                                @foreach ($expense as $ex)
                                    @if ($ex->category_detail())
                                        <a href="{{ $ex->category == 'goods expense' ? route('good.insight') : route('cashflow') }}"
                                            class="text-decoration-none">
                                            <div class="card shadow-sm mt-2 mx-2 card-bg-hover ">
                                                <div class="row mt-1 mb-md-1">
                                                    <div class="col-12 d-flex">
                                                        <span
                                                            class="rounded bg-light text-secondary py-0 px-2 fw-light ms-2">{{ $i }}</span>
                                                        <span
                                                            class="fw-light ms-2">{{ $ex->category == 'goods expense' ? 'GE : ' : 'PD : ' }}</span>
                                                        <span class="ms-2 text-primary-emphasis">
                                                            {{ $ex->category_detail()->name }}</span>
                                                        <span
                                                            class="fw-light ms-2 d-none d-md-inline">{{ ' - ' . format_currency($ex->price) }}</span>
                                                        <span
                                                            class="fst-italic ms-auto me-2 d-none d-md-inline">{{ format_date_time($ex->updated_at) }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-1 d-md-none">
                                                    <div class="col-12 d-flex">
                                                        <span
                                                            class=" fw-light ms-3">{{ format_currency($ex->category_detail()->price) }}</span>
                                                        <span
                                                            class="fst-italic ms-auto me-2">{{ format_date_time($ex->updated_at) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                        <?php $i++; ?>
                                    @endif
                                @endforeach
                                @if ($expense->count() <= 0)
                                    <div class="card shadow-sm mt-2 mx-2 card-bg-hover ">
                                        <div class="row mt-1 mb-md-1">
                                            <div class="col-12 d-flex">
                                                <span
                                                    class="fw-light fst-italic px-2">{{ 'No cash out found.' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function show_tab(target) {
            const tabs = 2;
            // deactive all tabs
            for (let number = 1; number <= tabs; number++) {
                let tab = document.getElementById('tab_' + number);
                let content = document.getElementById('content_' + number);
                // set tab to deactive
                tab.setAttribute('class', 'nav-link bg-white px-3 py-1');
                // set content to hide
                content.setAttribute('hidden', '');
            }
            let tab = document.getElementById('tab_' + target);
            let content = document.getElementById('content_' + target);
            // set tab to active
            tab.setAttribute('class', 'nav-link active bg-secondary text-white px-3 py-1');
            // set content to show
            content.removeAttribute('hidden');
        }

        function collapse_chart() {
            const icon = document.getElementById('salesIcon');

            if (icon.classList.contains('bi-eye-slash-fill')) {
                icon.classList.remove('bi-eye-slash-fill');
                icon.classList.add('bi-eye');
            } else {
                icon.classList.remove('bi-eye')
                icon.classList.add('bi-eye-slash-fill')
            }
        }
        // Charts
        var salesChart = document.getElementById('salesChart').getContext('2d');
        new Chart(salesChart, {
            type: 'polarArea',
            data: {
                labels: @json($chartData['labels']), // Data labels
                datasets: [{
                    label: 'Dataset',
                    data: @json($chartData['sales']), // The data values for the chart
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)', // Red
                        'rgba(75, 192, 192, 0.6)', // Green
                        'rgba(255, 205, 86, 0.6)', // Yellow
                        'rgba(201, 203, 207, 0.6)', // Grey
                        'rgba(54, 162, 235, 0.6)', // Blue
                    ]
                }]
            },
            options: {
                responsive: true,
                scales: {
                    r: {
                        ticks: {
                            beginAtZero: true // Start the radial scale at zero
                        }
                    }
                }
            }
        });
    </script>

</x-app-layout>
