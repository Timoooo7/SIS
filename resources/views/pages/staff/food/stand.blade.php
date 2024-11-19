<?php
use Illuminate\Support\Carbon;

$auth_user = Auth::user();
$active_stand_id = $active_stand ? $active_stand->id : 0;
$active_stand_name = $active_stand ? $active_stand->name : '';
?>
<x-app-layout>
    <x-slot name="header">
        {{ 'Foods Stand' }}
    </x-slot>

    <div class="container">
        {{-- Stand List --}}
        <div class="row">
            <div class="col-12">
                <div class="card border shadow p-3 mt-4 rounded mx-2">
                    {{-- Header --}}
                    <div class="d-flex border-primary border-bottom pb-2">
                        <span class="text-primary-emphasis ms-2 me-auto my-auto h4">
                            <i class="bi bi-shop me-2 "></i>
                            {{ __('Stand List') }}
                        </span>
                    </div>
                    {{-- Filter --}}
                    <div class="row justify-content-center">
                        <div class="col-md-11 col-xl-10">
                            <nav class="navbar py-0 mt-3">
                                <form method="post" id="searchStandForm" role="search"
                                    action="{{ route('food.stand.filter', ['id' => $active_stand_id]) }}">
                                    @csrf
                                    @method('put')
                                </form>
                                <form method="post" id="formStandProfit"
                                    action="{{ route('food.stand.filter', ['id' => $active_stand_id]) }}">
                                    @csrf
                                    @method('put')
                                    <?php
                                    $is_stand_profit = $filter['stand']['category'] == 'profit';
                                    $stand_profit_order = $filter['stand']['order'] == 'desc' ? 'desc' : 'asc';
                                    $stand_profit_icon = $is_stand_profit && $stand_profit_order == 'asc' ? 'up' : 'down';
                                    $stand_profit_value = $is_stand_profit && $stand_profit_order == 'asc' ? 'desc' : 'asc';
                                    $stand_profit_button = $is_stand_profit && $is_stand_profit ? '' : '';
                                    ?>
                                    <input type="hidden" name="category" value="profit">
                                </form>
                                <form method="post" id="formStandIncome"
                                    action="{{ route('food.stand.filter', ['id' => $active_stand_id]) }}">
                                    @csrf
                                    @method('put')
                                    <?php
                                    $is_stand_income = $filter['stand']['category'] == 'income';
                                    $stand_income_order = $filter['stand']['order'] == 'desc' ? 'desc' : 'asc';
                                    $stand_income_icon = $is_stand_income && $stand_income_order == 'asc' ? 'up' : 'down';
                                    $stand_income_value = $is_stand_income && $stand_income_order == 'asc' ? 'desc' : 'asc';
                                    $stand_income_button = $is_stand_income && $is_stand_income ? '' : '';
                                    ?>
                                    <input type="hidden" name="category" value="income">
                                </form>
                                <form method="post" id="formStandDate"
                                    action="{{ route('food.stand.filter', ['id' => $active_stand_id]) }}">
                                    @csrf
                                    @method('put')
                                    <?php
                                    $is_stand_date = $filter['stand']['category'] == 'date';
                                    $stand_date_order = $filter['stand']['order'] == 'desc' ? 'desc' : 'asc';
                                    $stand_date_icon = $is_stand_date && $stand_date_order == 'asc' ? 'up' : 'down';
                                    $stand_date_value = $is_stand_date && $stand_date_order == 'asc' ? 'desc' : 'asc';
                                    $stand_date_button = $is_stand_date && $is_stand_date ? '' : '';
                                    ?>
                                    <input type="hidden" name="category" value="date">
                                </form>
                                <div class="container d-block px-0 ">
                                    <?php $keyword_focus = $filter['stand']['keyword'] ? 'autofocus' : ''; ?>
                                    <div class="row">
                                        <div class="col-12 d-flex">
                                            <div class="input-group input-group-sm bg-body-tertiary rounded">
                                                {{-- Name Filter --}}
                                                <button type="button"
                                                    class="btn btn-sm rounded-0 rounded-start text-light bg-secondary">
                                                    <i class="bi bi-funnel-fill"></i>
                                                    <span
                                                        class="ms-1 ps-2 border-start border-light d-none d-md-inline">{{ 'Filter' }}</span>
                                                </button>
                                                <button type="submit" form="formStandProfit"
                                                    class="btn btn-sm btn-outline-secondary border-0 {{ $stand_profit_button }} rounded-0 my-0"
                                                    name="order" value="{{ $stand_profit_value }}">
                                                    <span class="d-none d-lg-inline">{{ 'Profit' }}</span>
                                                    <i class="bi bi-graph-up d-lg-none"></i>
                                                    <i class="bi bi-arrow-{{ $stand_profit_icon }}"></i>
                                                </button>
                                                <button type="submit" form="formStandIncome"
                                                    class="btn btn-sm btn-outline-secondary border-0 {{ $stand_income_button }} rounded-0 my-0"
                                                    name="order" value="{{ $stand_income_value }}">
                                                    <span class="d-none d-lg-inline">{{ 'Income' }}</span>
                                                    <i class="bi bi-cash-stack d-lg-none"></i>
                                                    <i class="bi bi-arrow-{{ $stand_income_icon }}"></i>
                                                </button>
                                                <button type="submit" form="formStandDate"
                                                    class="btn btn-sm btn-outline-secondary border-0 {{ $stand_date_button }} rounded-0 my-0"
                                                    name="order" value="{{ $stand_date_value }}">
                                                    <span class="d-none d-lg-inline">{{ 'Date' }}</span>
                                                    <i class="bi bi-calendar-range d-lg-none"></i>
                                                    <i class="bi bi-arrow-{{ $stand_date_icon }}"></i>
                                                </button>
                                                {{-- Search Filter --}}
                                                <input type="text" name="keyword" class="form-control"
                                                    form="searchStandForm" {{ $keyword_focus }}
                                                    value="{{ $filter['stand']['keyword'] }}"
                                                    placeholder="{{ ' Search by name' }}">
                                                <button class="btn btn-outline-secondary border-0" type="submit"
                                                    form="searchStandForm">
                                                    <i class="bi bi-search"></i>
                                                </button>
                                            </div>
                                            @if ($auth_user->roles->id == 3)
                                                <!-- Button trigger Add Stand Modal -->
                                                <button
                                                    class="btn btn-primary btn-sm bg-opacity-50 shadow-sm d-inline-block ms-2 fw-light"
                                                    data-bs-toggle="modal" data-bs-target="#addStandModal">
                                                    <i class="bi bi-plus-lg"></i>
                                                </button>
                                                <!-- Add Stand Modal -->
                                                <div class="modal fade" id="addStandModal" tabindex="-1"
                                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content shadow mx-3">
                                                            <div class="modal-header py-1 ps-3 pe-2">
                                                                <span class="modal-title fs-5 text-primary-emphasis"
                                                                    id="exampleModalLabel"><i
                                                                        class="bi bi-shop border-secondary border-end me-2 pe-2"></i>
                                                                    {{ 'New Stand' }}
                                                                </span>
                                                                <button type="button" class="btn btn-sm ms-auto"
                                                                    data-bs-dismiss="modal" aria-label="Close"><i
                                                                        class="bi bi-x-lg"></i></button>
                                                            </div>
                                                            <form method="post" id="formAddStand"
                                                                action="{{ route('food.stand.insert') }}">
                                                                @csrf
                                                                @method('put')
                                                                <div class="modal-body bg-light">
                                                                    <div class="row justify-content-center">
                                                                        <div class="col-4 col-lg-3">
                                                                            <label for="add_stand_name"
                                                                                class="form-label d-inline-block">Name</label>
                                                                        </div>
                                                                        <div class="col-8 col-lg-7">
                                                                            <input type="text"
                                                                                class="form-control form-control-sm d-inline-block"
                                                                                name="name" id="add_stand_name"
                                                                                required>
                                                                            <x-input-error :messages="$errors->get('name')"
                                                                                class="mt-2" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mt-2 justify-content-center">
                                                                        <div class="col-4 col-lg-3">
                                                                            <label for="add_stand_pic"
                                                                                class="form-label d-inline-block">PIC</label>
                                                                        </div>
                                                                        <div class="col-8 col-lg-7">
                                                                            <select name="pic_id" id="add_stand_pic"
                                                                                class="form-select form-select-sm"
                                                                                aria-label="Default select example"
                                                                                required>
                                                                                @foreach ($users as $user)
                                                                                    {{-- User can not be manager more than one departments --}}
                                                                                    <option
                                                                                        value="{{ $user->id }}">
                                                                                        {{ $user->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                            <x-input-error :messages="$errors->get('pic_id')"
                                                                                class="mt-2" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mt-2 justify-content-center">
                                                                        <div class="col-4 col-lg-3">
                                                                            <label for="add_stand_place"
                                                                                class="form-label d-inline-block">Place</label>
                                                                        </div>
                                                                        <div class="col-8 col-lg-7">
                                                                            <input type="text"
                                                                                class="form-control form-control-sm d-inline-block"
                                                                                name="place" id="add_stand_place"
                                                                                required>
                                                                            <x-input-error :messages="$errors->get('place')"
                                                                                class="mt-2" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mt-2 justify-content-center">
                                                                        <div class="col-4 col-lg-3">
                                                                            <label for="add_stand_date"
                                                                                class="form-label d-inline-block">Date</label>
                                                                        </div>
                                                                        <div class="col-8 col-lg-7">
                                                                            <input type="date"
                                                                                class="form-control form-control-sm d-inline-block"
                                                                                name="date" id="add_stand_date">
                                                                            <x-input-error :messages="$errors->get('date')"
                                                                                class="mt-2" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer p-1">
                                                                    <button type="submit" form="formAddStand"
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
                    {{-- Stand List --}}
                    <div class="row mx-1 mt-2 bg-light">
                        <div class="scroll-container-horizontal-lg scroll-container-horizontal mx-auto">
                            <?php $stand_number = 1; ?>
                            @foreach ($stand_list as $stand)
                                <?php
                                $is_stand_active = $stand->name == $active_stand_name;
                                $stand_card_class = $is_stand_active ? 'shadow ' : 'shadow-sm ';
                                $stand_divider_class = $is_stand_active ? 'border-2' : 'border-1';
                                $stand_text_class = $is_stand_active ? 'text-dark' : 'text-secondary fw-light';
                                $stand_number_class = $is_stand_active ? 'fw-normal' : 'fw-light';
                                $stand_text_secondary_class = $is_stand_active ? 'text-dark' : 'text-secondary';
                                ?>
                                {{-- stand List Item --}}
                                <div class="d-inline-block me-2 my-2 position-relative">
                                    <a href="{{ route('food.stand', ['id' => $stand->id]) }}"
                                        class="text-decoration-none text-nowrap">
                                        <div class="card rounded d-block card-bg-hover {{ $stand_card_class }}"
                                            style="width: auto;">
                                            <div class="row py-1">
                                                <div class="col-auto">
                                                    <span
                                                        class="ms-3 {{ $stand_number_class }} py-auto fw-light fs-6 me-2 pe-1 border-end border-secondary {{ $stand_divider_class }}">
                                                        {{ $stand_number }}
                                                    </span>
                                                    <span class="m-0 {{ $stand_text_class }} fs-5 me-3">
                                                        {{ $stand->name }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    @if ($stand->sale_validation == 0 && $stand->menu_lock > 0)
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle"></span>
                                    @endif
                                </div>
                                <?php $stand_number++; ?>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gx-2 pb-3">
            {{-- Stand Details --}}
            <div class="col-lg-5 col-12">
                <div class="card border-0 shadow-sm p-3 mt-3 mx-2">
                    <div class="d-flex border-secondary border-bottom pb-2">
                        <span class="text-primary-emphasis ms-2 me-auto my-auto h4 d-flex">
                            <span class="d-none d-lg-inline me-1">{{ 'Stand ' }}</span>
                            <span class="d-lg-none position-relative pe-3">
                                {{ strlen($active_stand_name) > 12 ? substr($active_stand_name, 0, 12) . '..' : substr($active_stand_name, 0, 15) . '' }}
                                @if ($active_stand)
                                    @if ($active_stand->sale_validation == 0 && $active_stand->menu_lock > 0)
                                        <span
                                            class="bg-success border border-light rounded d-md-none position-absolute start-100 top-0 translate-middle text-white fw-normal my-auto py-1 px-2 ms-2"
                                            style="font-size: .6rem">{{ 'Active' }}</span>
                                    @endif
                                    @if ($active_stand->sale_validation > 0)
                                        <span
                                            class="bg-light border border-light rounded d-md-none position-absolute start-100 top-0 translate-middle text-primary fw-bold my-auto py-1 px-2 ms-2"
                                            style="font-size: .6rem">{{ 'Done' }}</span>
                                    @endif
                                @endif
                            </span>
                            <span class="d-none d-lg-inline "> {{ $active_stand_name }} </span>
                            @if ($active_stand)
                                @if ($active_stand->sale_validation == 0 && $active_stand->menu_lock > 0)
                                    <span
                                        class="bg-success border border-light rounded d-none d-md-inline text-white fw-normal my-auto py-1 px-2 ms-2"
                                        style="font-size: .8rem">{{ 'Active' }}</span>
                                @endif
                                @if ($active_stand->sale_validation > 0)
                                    <span
                                        class="bg-light border border-light rounded d-none d-md-inline text-primary fw-bold my-auto py-1 px-2 ms-2"
                                        style="font-size: .8rem">{{ 'Done' }}</span>
                                @endif
                            @endif
                        </span>
                        @if ($auth_user->roles->id == 3)
                            <!-- Button trigger Edit Stand Modal -->
                            <button class="btn btn-outline-secondary btn-sm d-inline-block ms-2 fw-light"
                                data-bs-toggle="modal" data-bs-target="#editStandModal">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            {{-- Delete Button --}}
                            <button class="ms-2 btn btn-sm btn-secondary" data-bs-toggle="modal"
                                data-bs-target="#deleteStandModal">
                                <i class="bi bi-trash"></i>
                            </button>
                            @if ($active_stand)
                                <!-- Edit Stand Modal -->
                                <div class="modal fade" id="editStandModal" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content shadow mx-3">
                                            <div class="modal-header py-1 ps-3 pe-2">
                                                <span class="modal-title fs-5 text-primary-emphasis"
                                                    id="exampleModalLabel"><i
                                                        class="bi bi-shop border-secondary border-end me-2 pe-2"></i>
                                                    {{ 'Edit Stand' }}
                                                </span>
                                                <button type="button" class="btn btn-sm ms-auto"
                                                    data-bs-dismiss="modal" aria-label="Close"><i
                                                        class="bi bi-x-lg"></i></button>
                                            </div>
                                            <form method="post"
                                                action="{{ route('food.stand.update', ['id' => $active_stand_id]) }}">
                                                @csrf
                                                @method('put')
                                                <div class="modal-body bg-light">
                                                    <div class="row justify-content-center">
                                                        <div class="col-4 col-lg-3">
                                                            <label class="form-label d-inline-block">Name</label>
                                                        </div>
                                                        <div class="col-8 col-lg-7">
                                                            {{ $active_stand_name }}
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2 justify-content-center">
                                                        <div class="col-4 col-lg-3">
                                                            <label for="edit_stand_pic"
                                                                class="form-label d-inline-block">PIC</label>
                                                        </div>
                                                        <div class="col-8 col-lg-7">
                                                            <select name="pic_id" id="edit_stand_pic"
                                                                class="form-select form-select-sm"
                                                                aria-label="Default select example">
                                                                @foreach ($users as $user)
                                                                    {{-- User can not be manager more than one departments --}}
                                                                    <option value="{{ $user->id }}"
                                                                        {{ $user->id == $active_stand->pic_id ? 'selected' : '' }}>
                                                                        {{ $user->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <x-input-error :messages="$errors->get('pic_id')" class="mt-2" />
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2 justify-content-center">
                                                        <div class="col-4 col-lg-3">
                                                            <label for="edit_stand_place"
                                                                class="form-label d-inline-block">Place</label>
                                                        </div>
                                                        <div class="col-8 col-lg-7">
                                                            <input type="text"
                                                                class="form-control form-control-sm d-inline-block"
                                                                value="{{ $active_stand->place }}" name="place"
                                                                id="edit_stand_place">
                                                            <x-input-error :messages="$errors->get('place')" class="mt-2" />
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2 justify-content-center">
                                                        <div class="col-4 col-lg-3">
                                                            <label for="edit_stand_date"
                                                                class="form-label d-inline-block">Date</label>
                                                        </div>
                                                        <div class="col-8 col-lg-7">
                                                            <input type="date"
                                                                class="form-control form-control-sm d-inline-block"
                                                                value="{{ $active_stand->date }}" name="date"
                                                                id="edit_stand_date">
                                                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer p-1">
                                                    <button type="submit"
                                                        class="btn btn-sm btn-primary ">{{ 'Update' }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                {{-- Delete Stand Modal --}}
                                <div class="modal fade" id="deleteStandModal" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content shadow mx-3">
                                            <div class="modal-header py-1 ps-3 pe-2">
                                                <span class="modal-title text-primary-emphasis fs-5"
                                                    id="exampleModalLabel">
                                                    <i class="bi bi-house border-secondary border-end me-2 pe-2"></i>
                                                    <span class="d-none d-lg-inline">{{ 'Stand ' }}</span>
                                                    {{ $active_stand_name }}
                                                </span>
                                                <button type="button" class="btn btn-sm ms-auto"
                                                    data-bs-dismiss="modal" aria-label="Close"><i
                                                        class="bi bi-x-lg"></i></button>
                                            </div>
                                            <form method="post"
                                                action="{{ route('food.stand.delete', $active_stand_id) }}">
                                                @csrf
                                                @method('put')
                                                <div class="modal-body bg-light">
                                                    <div class="row justify-content-center">
                                                        <div class="col-12 col-lg-10">
                                                            <p class="m-0" style="text-align: justify;">
                                                                {{ 'All expense, income, menu, and sales in Stand ' .
                                                                    $active_stand_name .
                                                                    ' will be lost. Please make sure you have back up all the data needed.' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="row justify-content-center mt-2">
                                                        <div class="col-lg-10 col-11 border-bottom">
                                                            <span class="d-block text-center">
                                                                <label for="password">Password
                                                                    Needed to Authorize</label>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="row justify-content-center mt-2">
                                                        <div class="col-3 col-lg-3">
                                                            <label for="pic"
                                                                class="form-label d-inline-block">Password</label>
                                                        </div>
                                                        <div class="col-9 col-lg-7 ">
                                                            <div class="input-group">
                                                                <input id="password" class="form-control"
                                                                    type="password" name="password" required
                                                                    autocomplete="current-password" />
                                                                <button type="button" class="btn bg-light"
                                                                    onclick="show_password('password','password_icon_del')">
                                                                    <i class="bi bi-eye-slash-fill"
                                                                        id="password_icon_del"></i>
                                                                </button>
                                                            </div>
                                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="modal-footer p-1">
                                                    <button type="submit"
                                                        class="btn btn-sm btn-danger shadow">Delete</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="row mt-2">
                        <div class="col-5 col-lg-4 text-end">{{ 'In Charge :' }}</div>
                        <div class="col-7 col-lg-6">
                            {{ $active_stand ? $active_stand->pic->name : '' }}
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-5 col-lg-4 text-end">{{ 'Place :' }}</div>
                        <div class="col-7 col-lg-6">
                            {{ $active_stand ? $active_stand->place : '' }}
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-5 col-lg-4 text-end">{{ 'Date :' }}</div>
                        <div class="col-7 col-lg-6">
                            {{ $active_stand ? $active_stand->date : '' }}
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-5 col-lg-4 text-end">{{ 'Profit :' }}</div>
                        <div class="col-7 col-lg-6">
                            {{ $active_stand ? ($active_stand->profit ? format_currency($active_stand->profit) : format_currency(0)) : '' }}
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-5 col-lg-4 text-end">{{ 'Income :' }}</div>
                        <div class="col-7 col-lg-6">
                            {{ $active_stand ? ($active_stand->income ? format_currency($active_stand->income) : format_currency(0)) : '' }}
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-5 col-lg-4 text-end">{{ 'Expense :' }}</div>
                        <div class="col-7 col-lg-6">
                            {{ $active_stand ? ($active_stand->expense ? format_currency($active_stand->expense) : format_currency(0)) : '' }}
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-5 col-lg-4 text-end">{{ 'Status Menu :' }}</div>
                        <div class="col-7 col-lg-6">
                            {{ $active_stand ? ($active_stand->menu_lock > 0 ? 'Locked by ' . $active_stand->menu_validator->name : 'Unlocked') : '' }}
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-5 col-lg-4 text-end">{{ 'Status Income :' }}</div>
                        <div class="col-7 col-lg-6">
                            {{ $active_stand ? ($active_stand->sale_validation > 0 ? 'Validated by ' . $active_stand->sales_validator->name : 'Unvalidated') : '' }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-12">
                {{-- Content Tab Navigation --}}
                <div class="row mt-3">
                    <div class="col-12">
                        <ul class="nav nav-tabs mx-2 border-0">
                            <li class="nav-item"><a id="tab_1" onclick="show_tab(1)"
                                    class="nav-link px-3 py-1 border {{ $default_tab == 1 ? 'active bg-secondary text-white' : 'bg-white' }}">Expense
                                </a>
                            </li>
                            <li class="nav-item"><a id="tab_2" onclick="show_tab(2)"
                                    class="nav-link px-3 py-1 border {{ $default_tab == 2 ? 'active bg-secondary text-white' : 'bg-white' }}">Menu</a>
                            </li>
                            <li class="nav-item"><a id="tab_3" onclick="show_tab(3)"
                                    class="nav-link px-3 py-1 border {{ $default_tab == 3 ? 'active bg-secondary text-white' : 'bg-white' }}">Income</a>
                            </li>
                        </ul>
                    </div>
                </div>
                {{-- Expense list --}}
                <div id="content_1" {{ $default_tab == 1 ? '' : 'hidden' }}>
                    {{-- Expense Filter --}}
                    <div class="row justify-content-center">
                        <div class="col-12 ">
                            <nav class="navbar rounded bg-white shadow-sm p-2 mx-2">
                                <form method="post" id="formExpenseFilter"
                                    action="{{ route('stand.expense.filter', ['id' => $active_stand_id]) }}">
                                    @csrf
                                    @method('put')
                                    <?php
                                    $is_expense_name = $filter['expense']['category'] == 'name';
                                    $expense_name_order = $filter['expense']['order'] == 'desc' ? 'desc' : 'asc';
                                    $expense_name_icon = $is_expense_name && $expense_name_order == 'asc' ? 'up' : 'down';
                                    $expense_name_value = $is_expense_name && $expense_name_order == 'asc' ? 'desc' : 'asc';
                                    ?>
                                    <input type="hidden" name="category" value="name">
                                </form>
                                <form method="post" id="formExpenseFilter2"
                                    action="{{ route('stand.expense.filter', ['id' => $active_stand_id]) }}">
                                    @csrf
                                    @method('put')
                                    <?php
                                    $is_expense_date = $filter['expense']['category'] == 'created_at';
                                    $expense_date_order = $filter['expense']['order'] == 'desc' ? 'desc' : 'asc';
                                    $expense_date_icon = $is_expense_date && $expense_date_order == 'asc' ? 'up' : 'down';
                                    $expense_date_value = $is_expense_date && $expense_date_order == 'asc' ? 'desc' : 'asc';
                                    ?>
                                    <input type="hidden" name="category" value="created_at">
                                </form>
                                <div class="container d-block px-0 ">
                                    <div class="row">
                                        <div class="col-12 d-flex">
                                            <div class="input-group bg-body-tertiary rounded">
                                                <button type="button" form="formExpenseFilter"
                                                    class="btn btn-sm rounded-0 rounded-start text-light bg-secondary">
                                                    <i class="bi bi-funnel-fill"></i>
                                                    <span
                                                        class="ms-1 ps-2 border-start border-light d-none d-md-inline">{{ 'Filter' }}</span>
                                                </button>
                                                <button type="submit" form="formExpenseFilter"
                                                    class="btn btn-sm btn-outline-secondary border-0 rounded-0 my-0"
                                                    name="order" value="{{ $expense_name_value }}">
                                                    <span class="">{{ 'Name' }}</span>
                                                    <i class="bi bi-sort-alpha-{{ $expense_name_icon }}-alt"></i>
                                                </button>
                                                <button type="submit" form="formExpenseFilter2"
                                                    class="btn btn-sm btn-outline-secondary border-0 rounded-0 my-0"
                                                    name="order" value="{{ $expense_date_value }}">
                                                    <span class="">{{ 'Date' }}</span>
                                                    <i class="bi bi-sort-numeric-{{ $expense_date_icon }}-alt"></i>
                                                </button>
                                            </div>
                                            @if ($active_stand ? $auth_user->id == $active_stand->pic_id : false)
                                                <!-- Button trigger Add Expense Modal -->
                                                <div
                                                    onclick="{{ $active_stand->sale_validation == 0 ? '' : 'notif("You can not add Stand Expense Item after Stand Income validated by Operational Officer.")' }}">
                                                    <button
                                                        class="ms-2 btn btn-sm btn-{{ $active_stand->sale_validation == 0 ? 'primary' : 'secondary disabled' }}"
                                                        data-bs-toggle="modal" data-bs-target="#addStandExpense">
                                                        <i class="bi bi-plus-lg"></i>
                                                    </button>
                                                </div>
                                                <!-- Add Expense Modal -->
                                                @if ($active_stand->sale_validation == 0)
                                                    <div class="modal fade" id="addStandExpense" tabindex="-1"
                                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content shadow mx-3">
                                                                <div class="modal-header py-1 ps-3 pe-2">
                                                                    <span
                                                                        class="modal-title fs-5 text-primary-emphasis"
                                                                        id="exampleModalLabel"><i
                                                                            class="bi bi-cart-plus border-secondary border-end me-2 pe-2"></i>
                                                                        New Stand Expense Item
                                                                    </span>
                                                                    <button type="button" class="btn btn-sm ms-auto"
                                                                        data-bs-dismiss="modal" aria-label="Close"><i
                                                                            class="bi bi-x-lg"></i></button>
                                                                </div>
                                                                <form method="post" enctype="multipart/form-data"
                                                                    action="{{ route('stand.expense.add', ['id' => $active_stand_id]) }}">
                                                                    @csrf
                                                                    @method('put')
                                                                    <div class="modal-body bg-light">
                                                                        <div class="row justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_stand_expense_name"
                                                                                    class="form-label d-inline-block">Name</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="text"
                                                                                    class="form-control form-control-sm d-inline-block"
                                                                                    name="name"
                                                                                    id="add_stand_expense_name"
                                                                                    value="{{ old('name') }}"
                                                                                    required>
                                                                                <x-input-error :messages="$errors->get('name')"
                                                                                    class="mt-2" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_stand_expense_price"
                                                                                    class="form-label d-inline-block">Price</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="number"
                                                                                    class="form-control form-control-sm d-inline-block"
                                                                                    name="price"
                                                                                    id="add_stand_expense_price"
                                                                                    value="{{ old('price') }}"
                                                                                    oninput="return setTotalPrice('stand_expense')"
                                                                                    placeholder="Type numbers only"
                                                                                    required>
                                                                                <x-input-error :messages="$errors->get('price')"
                                                                                    class="mt-2" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_stand_expense_unit"
                                                                                    class="form-label d-inline-block">Unit</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="text"
                                                                                    class="form-control form-control-sm d-inline-block"
                                                                                    name="unit"
                                                                                    placeholder="pcs, gram, etc.."
                                                                                    id="add_stand_expense_unit"
                                                                                    value="{{ old('unit') }}"
                                                                                    required>
                                                                                <x-input-error :messages="$errors->get('unit')"
                                                                                    class="mt-2" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_stand_expense_qty"
                                                                                    class="form-label d-inline-block">Quantity</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="number"
                                                                                    class="form-control form-control-sm d-inline-block"
                                                                                    name="qty"
                                                                                    id="add_stand_expense_qty"
                                                                                    value="{{ old('qty') }}"
                                                                                    oninput="return setTotalPrice('stand_expense')"
                                                                                    placeholder="Type numbers only"
                                                                                    required>
                                                                                <x-input-error :messages="$errors->get('qty')"
                                                                                    class="mt-2" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <span>{{ 'Total Price' }}</span>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <span
                                                                                    id="add_stand_expense_total"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_stand_expense_receipt"
                                                                                    class="form-label d-inline-block">{{ 'Receipt' }}</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="file"
                                                                                    class="form-control form-control-sm"
                                                                                    name="reciept"
                                                                                    id="add_stand_expense_receipt"
                                                                                    value="{{ old('reciept') }}">
                                                                                <x-input-error :messages="$errors->get('reciept')"
                                                                                    class="mt-2" />
                                                                                <div class="input-group input-group-sm"
                                                                                    id="add_stand_expense_receipt_same_container"
                                                                                    hidden>
                                                                                    <label
                                                                                        for="add_stand_expense_receipt_same"
                                                                                        class="input-group-text">Same
                                                                                        as
                                                                                        item</label>
                                                                                    <select name="receipt_same"
                                                                                        class="form-select py-0 d-inline"
                                                                                        id="add_stand_expense_receipt_same">
                                                                                        <?php $i = 1; ?>
                                                                                        @foreach ($expense_list as $expense)
                                                                                            <?php $default = $i === 1 ? 'selected' : ''; ?>
                                                                                            <option
                                                                                                value="{{ $expense->id }}"
                                                                                                {{ $default }}>
                                                                                                {{ $expense->name }}
                                                                                            </option>
                                                                                            <?php $i++; ?>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                @if ($expense_list->count() > 0)
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
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </nav>
                        </div>
                    </div>
                    {{-- Expense list item --}}
                    <div class="row">
                        <div class="col-12">
                            <div class="bg-secondary bg-opacity-25 rounded shadow-sm mx-2">
                                <div class="scroll-container-2 scroll-container-lg mt-2 pb-2">
                                    <?php
                                    $i = 1;
                                    ?>
                                    @foreach ($expense_list as $expense)
                                        <div class="card shadow-sm mt-2 mx-2 py-1 border-0 bg-white ">
                                            <div class="row">
                                                <div class="col-12 d-flex">
                                                    <span
                                                        class="text-primary-emphasis ms-2 my-auto">{{ $expense->name }}</span>
                                                    <span
                                                        class="fw-light ms-2 my-auto d-none d-lg-flex">{{ '- ' . format_currency($expense->price) . ' /' . $expense->unit }}</span>
                                                    <span
                                                        class="fw-light ms-auto my-auto d-none d-lg-flex">{{ 'total (' . $expense->qty . ') : ' }}</span>
                                                    <span
                                                        class="fw-normal mx-2 my-auto d-none d-lg-flex">{{ format_currency($expense->total_price) }}</span>
                                                    {{-- Receipt Trigger Button --}}
                                                    <button
                                                        class="ms-auto ms-lg-1 {{ $auth_user->id == $active_stand->pic_id ? 'me-1' : 'me-2' }} btn btn-sm btn-light position-relative"
                                                        data-bs-toggle="modal" data-bs-target="#receiptExpenseModal"
                                                        onclick="setExpenseReceipt({{ $expense }})">

                                                        @if ($expense->operational_id == null)
                                                            <span
                                                                class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                                                        @endif
                                                        <i class="bi bi-receipt"> </i>
                                                    </button>
                                                    @if ($auth_user->id == $active_stand->pic_id)
                                                        {{-- Delete Button --}}
                                                        <div
                                                            onclick="{{ $expense->operational_id == 0 ? '' : 'notif("You can not delete Stand Expense Item after this receipt validated by Operational Officer.")' }}">
                                                            <button
                                                                class="me-2 btn btn-sm btn-danger {{ $expense->operational_id > 0 ? 'disabled' : ' ' }}"
                                                                onclick="confirmation('{{ route('stand.expense.delete', ['id' => $expense->id]) }}', '{{ 'Are you sure want to delete ' . $expense->name . ' from ' . $stand->name . ' Stand Expense?' }}')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row d-lg-none">
                                                <div class="col-12 d-flex">
                                                    <span
                                                        class="fw-light ms-2 ">{{ format_currency($expense->price) . ' /' . $expense->unit }}</span>
                                                    <span
                                                        class="fw-light ms-auto">{{ 'total (' . $expense->qty . ') : ' }}</span>
                                                    <span
                                                        class="fw-normal mx-2">{{ format_currency($expense->total_price) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $i++; ?>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Expense Receipt Modal -->
                    <div class="modal fade" id="receiptExpenseModal" tabindex="-1"
                        aria-labelledby="receiptExpenseModal" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content shadow mx-3 mt-5">
                                <div class="modal-header py-1 ps-3 pe-2">
                                    <span class="modal-title fs-5 text-primary-emphasis">
                                        <i class="bi bi-receipt border-secondary border-end me-2 pe-2"></i>
                                        {{ 'Stand Expense Receipt' }}
                                    </span>
                                    <button type="button" class="btn btn-sm ms-auto" data-bs-dismiss="modal"
                                        aria-label="Close"><i class="bi bi-x-lg"></i></button>
                                </div>
                                <div class="modal-body bg-light p-1 px-3">
                                    <div class="row justify-content-center mt-2">
                                        <div class="col-12 d-flex">
                                            <img src="" alt="image" class="rounded mx-auto"
                                                style="width: 100%; height 100%;  object-fit: contain; max-height:320px;"
                                                id="expense_receipt_image">
                                        </div>
                                    </div>
                                    <div class="row mt-2 justify-content-center">
                                        <div class="col-12 d-flex">
                                            <span class="fw-light ms-auto">{{ 'Status : ' }}</span>
                                            <span class="fw-normal text-primary-emphasis me-auto ms-1"
                                                id="expense_receipt_status"></span>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-12 d-flex">
                                            <a href="" target="blank" style="text-decoration-none"
                                                class="mx-auto" id="expense_receipt_download" download>
                                                <button class="btn btn-sm btn-light">
                                                    <span class="fw-light" id="expense_receipt_name"></span>
                                                    <i class="bi bi-download text-primary"></i>
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer p-1 px-2">
                                    @if ($active_stand ? $auth_user->roles_id == 3 && $active_stand->sale_validation == 0 : false)
                                        <form method="post" action="{{ route('stand.expense.validate') }}"
                                            enctype="multipart/form-data" id="formExpenseReceiptValidation">
                                            @csrf
                                            @method('put')
                                            <input type="hidden" name="receipt_id" id="expense_receipt_id">
                                            <input type="hidden" name="validate" id="expense_receipt_validate">
                                        </form>
                                        <div class="btn-group btn-group-sm">
                                            <button type="submit" form="formExpenseReceiptValidation"
                                                class="btn btn-sm btn-primary" id="expense_receipt_button"></button>
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
                {{-- Menu list --}}
                <div id="content_2" {{ $default_tab == 2 ? '' : 'hidden' }}>
                    {{-- Menu Filter --}}
                    <div class="row justify-content-center">
                        <div class="col-12 ">
                            <nav class="navbar rounded bg-white shadow-sm p-2 mx-2">
                                <form method="post" id="formStandMenuFilter"
                                    action="{{ route('stand.menu.filter', ['id' => $active_stand_id]) }}">
                                    @csrf
                                    @method('put')
                                    <?php
                                    $is_menu_name = $filter['menu']['category'] == 'name';
                                    $menu_name_order = $filter['menu']['order'] == 'desc' ? 'desc' : 'asc';
                                    $menu_name_icon = $is_menu_name && $menu_name_order == 'asc' ? 'up' : 'down';
                                    $menu_name_value = $is_menu_name && $menu_name_order == 'asc' ? 'desc' : 'asc';
                                    ?>
                                    <input type="hidden" name="category" value="name">
                                </form>
                                <form method="post" id="formStandMenuFilter2"
                                    action="{{ route('stand.menu.filter', ['id' => $active_stand_id]) }}">
                                    @csrf
                                    @method('put')
                                    <?php
                                    $is_menu_sale = $filter['menu']['category'] == 'sale';
                                    $menu_sale_order = $filter['menu']['order'] == 'desc' ? 'desc' : 'asc';
                                    $menu_sale_icon = $is_menu_sale && $menu_sale_order == 'asc' ? 'up' : 'down';
                                    $menu_sale_value = $is_menu_sale && $menu_sale_order == 'asc' ? 'desc' : 'asc';
                                    ?>
                                    <input type="hidden" name="category" value="sale">
                                </form>
                                <form method="post" id="formStandMenuFilter3"
                                    action="{{ route('stand.menu.filter', ['id' => $active_stand_id]) }}">
                                    @csrf
                                    @method('put')
                                    <?php
                                    $is_menu_stock = $filter['menu']['category'] == 'stock';
                                    $menu_stock_order = $filter['menu']['order'] == 'desc' ? 'desc' : 'asc';
                                    $menu_stock_icon = $is_menu_stock && $menu_stock_order == 'asc' ? 'up' : 'down';
                                    $menu_stock_value = $is_menu_stock && $menu_stock_order == 'asc' ? 'desc' : 'asc';
                                    ?>
                                    <input type="hidden" name="category" value="stock">
                                </form>
                                <div class="container d-block px-0 ">
                                    <div class="row">
                                        <div class="col-12 d-flex ">
                                            <div class="input-group bg-body-tertiary rounded">
                                                <button type="button"
                                                    class="btn btn-sm rounded-0 rounded-start text-light bg-secondary">
                                                    <i class="bi bi-funnel-fill"></i>
                                                    <span
                                                        class="ms-1 ps-2 border-start border-light d-none d-md-inline">{{ 'Filter' }}</span>
                                                </button>
                                                <button type="submit" form="formStandMenuFilter"
                                                    class="btn btn-sm btn-outline-secondary border-0 rounded-0 my-0"
                                                    name="order" value="{{ $menu_name_value }}">
                                                    <span class="">{{ 'Name' }}</span>
                                                    <i class="bi bi-sort-alpha-{{ $menu_name_icon }}-alt"></i>
                                                </button>
                                                <button type="submit" form="formStandMenuFilter2"
                                                    class="btn btn-sm btn-outline-secondary border-0 rounded-0 my-0"
                                                    name="order" value="{{ $menu_sale_value }}">
                                                    <span class="">{{ 'Sale' }}</span>
                                                    <i class="bi bi-sort-numeric-{{ $menu_sale_icon }}-alt"></i>
                                                </button>
                                                <button type="submit" form="formStandMenuFilter3"
                                                    class="btn btn-sm btn-outline-secondary border-0 rounded-0 my-0"
                                                    name="order" value="{{ $menu_stock_value }}">
                                                    <span class="">{{ 'Stock' }}</span>
                                                    <i class="bi bi-sort-numeric-{{ $menu_stock_icon }}-alt"></i>
                                                </button>
                                            </div>
                                            @if ($active_stand ? $auth_user->id == $active_stand->pic_id : false)
                                                <!-- Button trigger Add Menu Modal -->
                                                <div
                                                    onclick="{{ $active_stand->menu_lock == 0 ? '' : 'notif("You can not add Menu Item after Stand Menu locked by Operational Officer.")' }}">
                                                    <button
                                                        class="ms-2 btn btn-sm btn-{{ $active_stand->menu_lock == 0 ? 'primary' : 'secondary disabled' }}"
                                                        data-bs-toggle="modal" data-bs-target="#addStandMenu">
                                                        <i class="bi bi-plus-lg"></i>
                                                    </button>
                                                </div>
                                                @if ($active_stand->menu_lock == 0)
                                                    <!-- Add Menu Modal -->
                                                    <div class="modal fade" id="addStandMenu" tabindex="-1"
                                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content shadow mx-3">
                                                                <div class="modal-header py-1 ps-3 pe-2">
                                                                    <span
                                                                        class="modal-title fs-5 text-primary-emphasis"
                                                                        id="exampleModalLabel"><i
                                                                            class="bi bi-menu-button-wide-fill border-secondary border-end me-2 pe-2"></i>
                                                                        New Menu Item
                                                                    </span>
                                                                    <button type="button" class="btn btn-sm ms-auto"
                                                                        data-bs-dismiss="modal" aria-label="Close"><i
                                                                            class="bi bi-x-lg"></i></button>
                                                                </div>
                                                                <form method="post"
                                                                    action="{{ route('stand.menu.add', ['id' => $active_stand_id]) }}">
                                                                    @csrf
                                                                    @method('put')
                                                                    <div class="modal-body bg-light">
                                                                        <div class="row justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_menu_name"
                                                                                    class="form-label d-inline-block">Name</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="text"
                                                                                    class="form-control form-control-sm d-inline-block"
                                                                                    name="name" id="add_menu_name"
                                                                                    value="{{ old('name') }}"
                                                                                    required>
                                                                                <x-input-error :messages="$errors->get('name')"
                                                                                    class="mt-2" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_menu_volume"
                                                                                    class="form-label d-inline-block">Volume</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="number"
                                                                                    class="form-control form-control-sm d-inline-block"
                                                                                    name="volume"
                                                                                    id="add_menu_volume"
                                                                                    value="{{ old('volume') }}"
                                                                                    placeholder="Type numbers only">
                                                                                <x-input-error :messages="$errors->get('volume')"
                                                                                    class="mt-2" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_menu_unit_vol"
                                                                                    class="form-label d-inline-block">Volume
                                                                                    Unit</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="text"
                                                                                    class="form-control form-control-sm d-inline-block"
                                                                                    name="volume_unit"
                                                                                    placeholder="ml, cc, etc.."
                                                                                    id="add_menu_unit_vol"
                                                                                    value="{{ old('volume_unit') }}">
                                                                                <x-input-error :messages="$errors->get('volume_unit')"
                                                                                    class="mt-2" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_menu_mass"
                                                                                    class="form-label d-inline-block">Mass</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="number"
                                                                                    class="form-control form-control-sm d-inline-block"
                                                                                    name="mass" id="add_menu_mass"
                                                                                    value="{{ old('mass') }}"
                                                                                    placeholder="Type numbers only">
                                                                                <x-input-error :messages="$errors->get('mass')"
                                                                                    class="mt-2" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_menu_unit_mass"
                                                                                    class="form-label d-inline-block">Mass
                                                                                    Unit</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="text"
                                                                                    class="form-control form-control-sm d-inline-block"
                                                                                    name="mass_unit"
                                                                                    placeholder="gram, kg, etc.."
                                                                                    id="add_menu_unit_mass"
                                                                                    value="{{ old('mass_unit') }}">
                                                                                <x-input-error :messages="$errors->get('mass_unit')"
                                                                                    class="mt-2" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_menu_price"
                                                                                    class="form-label d-inline-block">Price</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="number"
                                                                                    class="form-control form-control-sm d-inline-block"
                                                                                    name="price" id="add_menu_price"
                                                                                    value="{{ old('price') }}"
                                                                                    placeholder="Type numbers only"
                                                                                    required>
                                                                                <x-input-error :messages="$errors->get('price')"
                                                                                    class="mt-2" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_menu_stock"
                                                                                    class="form-label d-inline-block">Stock</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="number"
                                                                                    class="form-control form-control-sm d-inline-block"
                                                                                    name="stock" id="add_menu_stock"
                                                                                    value="{{ old('stock') }}"
                                                                                    placeholder="Type numbers only"
                                                                                    required>
                                                                                <x-input-error :messages="$errors->get('stock')"
                                                                                    class="mt-2" />
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
                                            @endif
                                            @if ($active_stand ? $auth_user->roles_id == 3 : false)
                                                {{-- Menu Lock Button --}}
                                                <div
                                                    onclick="{{ $active_stand->sale_validation == 0 ? '' : 'notif("You can not change Menu Item after Stand Income validated by Operational Officer.")' }}">
                                                    <button
                                                        class="ms-1 btn btn-sm btn-{{ $active_stand->menu_lock > 0 ? 'success' : 'secondary' }} {{ $active_stand->sale_validation > 0 ? 'disabled' : '' }}"
                                                        onclick="confirmation('{{ route('stand.menu.validate', ['id' => $active_stand_id, 'valid' => $active_stand->menu_lock > 0 ? 0 : 1]) }}', '{{ $active_stand->menu_lock > 0 ? 'Are you sure want to unlock Stand ' . $active_stand_name . ' Menu?' : 'Are you sure want to lock Stand ' . $active_stand_name . ' Menu?' }}')">
                                                        <i
                                                            class="bi bi-{{ $active_stand->menu_lock > 0 ? 'lock' : 'unlock' }}"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </nav>
                        </div>
                    </div>
                    {{-- Menu list item --}}
                    <div class="row">
                        <div class="col-12">
                            <div class="bg-secondary bg-opacity-25 rounded shadow-sm mx-2">
                                <div class="scroll-container-2 scroll-container-lg mt-2 pb-2">
                                    <?php
                                    $i = 1;
                                    ?>
                                    @foreach ($menu_list as $menu)
                                        <div class="card shadow-sm mt-2 mx-2 py-1 border-0 bg-white ">
                                            <div class="row">
                                                <div class="col-12 d-flex">
                                                    <span
                                                        class="text-primary-emphasis ms-2 my-auto">{{ $menu->name }}</span>
                                                    <?php
                                                    $dash = $menu->volume && $menu->mass ? ' - ' : '';
                                                    $opbracket = $menu->volume || $menu->mass ? ' (' : '';
                                                    $clbracket = $menu->volume || $menu->mass ? ')' : '';
                                                    ?>
                                                    <span
                                                        class="fw-light ms-2 my-auto d-none d-lg-flex">{{ '- ' . format_currency($menu->price) . $opbracket . $menu->volume . $menu->volume_unit . $dash . $menu->mass . $menu->mass_unit . $clbracket }}</span>
                                                    <span
                                                        class="fw-light ms-auto my-auto d-none d-lg-flex">{{ 'sales (' . $menu->sale . '/' . $menu->stock . ') : ' }}</span>
                                                    <span
                                                        class="fw-normal mx-2 my-auto d-none d-lg-flex">{{ format_currency($menu->sale * $menu->price) }}</span>
                                                    @if ($auth_user->id == $active_stand->pic_id)
                                                        {{-- Update Stock Button --}}
                                                        <div class="ms-auto ms-lg-1"
                                                            onclick="{{ $active_stand->sale_validation == 0 ? '' : 'notif("You can not update stock Menu Item after Stand Income validated by Operational Officer.")' }}">
                                                            <button
                                                                class="btn btn-sm btn-light {{ $active_stand->sale_validation > 0 ? 'disabled' : '' }}"
                                                                onclick="setUpdateStock({{ $menu }})"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#updateStockModal">
                                                                <i class="bi bi-box-arrow-in-down"></i>
                                                            </button>
                                                        </div>
                                                        {{-- Delete Button --}}
                                                        <div
                                                            onclick="{{ $active_stand->menu_lock == 0 ? '' : 'notif("You can not delete Menu Item after Stand Menu locked by Operational Officer.")' }}">
                                                            <button
                                                                class="ms-1 ms-lg-1 me-2 btn btn-sm btn-danger {{ $active_stand->menu_lock > 0 ? 'disabled' : '' }}"
                                                                onclick="confirmation('{{ route('stand.menu.delete', ['id' => $menu->id]) }}', '{{ 'Are you sure want to delete ' . $menu->name . ' from Stand ' . $active_stand_name . ' Menu List?' }}')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row d-lg-none">
                                                <div class="col-12 d-flex">
                                                    <span
                                                        class="fw-light ms-2 ">{{ format_currency($menu->price) . ' /' . $menu->volume . $menu->volume_unit . $dash . $menu->mass . $menu->mass_unit }}</span>
                                                    <span
                                                        class="fw-light ms-auto">{{ 'sales (' . $menu->sale . '/' . $menu->stock . ') : ' }}</span>
                                                    <span
                                                        class="fw-normal mx-2">{{ format_currency($menu->sale * $menu->price) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $i++; ?>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Update Stock Modal -->
                    @if ($active_stand ? $auth_user->id == $active_stand->pic_id && $active_stand->sale_validation == 0 : false)
                        <div class="modal fade" id="updateStockModal" tabindex="-1"
                            aria-labelledby="updateStockModal" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content shadow mx-3 mt-5">
                                    <div class="modal-header py-1 ps-3 pe-2">
                                        <span class="modal-title fs-5 text-primary-emphasis">
                                            <i
                                                class="bi bi-box-arrow-in-down border-secondary border-end me-2 pe-2"></i>
                                            {{ 'Update Stock' }}
                                        </span>
                                        <button type="button" class="btn btn-sm ms-auto" data-bs-dismiss="modal"
                                            aria-label="Close"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                    <div class="modal-body bg-light p-1 px-3">
                                        <div class="row mt-2 justify-content-center">
                                            <div class="col-4 col-lg-4">
                                                <label class="form-label d-flex"><span
                                                        class="ms-auto">{{ 'Menu :' }}</span></label>
                                            </div>
                                            <div class="col-6 col-lg-6">
                                                <span id="update_stock_menu"></span>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-4 col-lg-4">
                                                <label class="form-label d-flex"><span
                                                        class="ms-auto">{{ 'Stock :' }}</span></label>
                                            </div>
                                            <div class="col-6 col-lg-6">
                                                <span id="update_stock_stock"></span>
                                            </div>
                                        </div>
                                        <form method="post" id="formUpdateStock"
                                            action="{{ route('stand.menu.stock.update') }}">
                                            @csrf
                                            @method('put')
                                            <input type="hidden" name="id" id="update_stock_id">
                                            <div class="row justify-content-center">
                                                <div class="col-4 col-lg-4">
                                                    <label class="form-label d-flex"><span
                                                            class="ms-auto">{{ 'Add :' }}</span></label>
                                                </div>
                                                <div class="col-6 col-lg-6">
                                                    <input type="number"
                                                        class="form-control form-control-sm d-inline-block"
                                                        name="stock" id="update_stock_stock"
                                                        value="{{ old('stock') }}" required>
                                                    <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer p-1 px-2">
                                        <button form="formUpdateStock" type="submit"
                                            class="btn btn-sm btn-primary">{{ 'Update' }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                {{-- Income list --}}
                <div id="content_3" {{ $default_tab == 3 ? '' : 'hidden' }}>
                    {{-- Income Filter --}}
                    <div class="row justify-content-center">
                        <div class="col-12 ">
                            <nav class="navbar rounded bg-white shadow-sm p-2 mx-2">
                                <form method="post" id="formStandIncomeFilter"
                                    action="{{ route('stand.income.filter', ['id' => $active_stand_id]) }}">
                                    @csrf
                                    @method('put')
                                    <?php
                                    $is_income_price = $filter['income']['category'] == 'transaction';
                                    $income_price_order = $filter['income']['order'] == 'desc' ? 'desc' : 'asc';
                                    $income_price_icon = $is_income_price && $income_price_order == 'asc' ? 'up' : 'down';
                                    $income_price_value = $is_income_price && $income_price_order == 'asc' ? 'desc' : 'asc';
                                    ?>
                                    <input type="hidden" name="category" value="transaction">
                                </form>
                                <form method="post" id="formStandIncomeFilter2"
                                    action="{{ route('stand.income.filter', ['id' => $active_stand_id]) }}">
                                    @csrf
                                    @method('put')
                                    <?php
                                    $is_income_date = $filter['income']['category'] == 'created_at';
                                    $income_date_order = $filter['income']['order'] == 'desc' ? 'desc' : 'asc';
                                    $income_date_icon = $is_income_date && $income_date_order == 'asc' ? 'up' : 'down';
                                    $income_date_value = $is_income_date && $income_date_order == 'asc' ? 'desc' : 'asc';
                                    ?>
                                    <input type="hidden" name="category" value="created_at">
                                </form>
                                <div class="container d-block px-0">
                                    <div class="row">
                                        <div class="col-12 d-flex">
                                            <div class="input-group  bg-body-tertiary rounded">
                                                <button type="button"
                                                    class="btn btn-sm rounded-0 rounded-start text-light bg-secondary">
                                                    <i class="bi bi-funnel-fill"></i>
                                                    <span
                                                        class="ms-1 ps-2 border-start border-light d-none d-md-inline">{{ 'Filter' }}</span>
                                                </button>
                                                <button type="submit" form="formStandIncomeFilter"
                                                    class="btn btn-sm btn-outline-secondary border-0 rounded-0 my-0"
                                                    name="order" value="{{ $income_price_value }}">
                                                    <span class="">{{ 'Transaction' }}</span>
                                                    <i class="bi bi-sort-numeric-{{ $income_price_icon }}-alt"></i>
                                                </button>
                                                <button type="submit" form="formStandIncomeFilter2"
                                                    class="btn btn-sm btn-outline-secondary border-0 rounded-0 my-0"
                                                    name="order" value="{{ $income_date_value }}">
                                                    <span class="">{{ 'Date' }}</span>
                                                    <i class="bi bi-sort-numeric-{{ $income_date_icon }}-alt"></i>
                                                </button>
                                            </div>
                                            @if ($active_stand)
                                                @if ($auth_user->id == $active_stand->pic_id)
                                                    {{-- Generate Token Button --}}
                                                    <div class="ms-2"
                                                        onclick="{{ $active_stand->sale_validation == 0 && $active_stand->menu_lock > 0 ? '' : 'notif("Cashier token can be generated only when stand active.")' }}">
                                                        <a href="{{ route('stand.token', ['id' => $active_stand_id]) }}"
                                                            class="btn btn-sm btn-{{ $active_stand->sale_validation == 0 && $active_stand->menu_lock > 0 ? 'light' : 'secondary disabled' }}">
                                                            <i class="bi bi-key"></i>
                                                        </a>
                                                    </div>
                                                @endif
                                                @if ($auth_user->roles_id == 3)
                                                    {{-- Income Validation Button --}}
                                                    <div
                                                        onclick="{{ $active_stand->menu_lock > 0 ? '' : 'notif("Stand menu are unlock, please lock the menu list first.")' }}">
                                                        <button
                                                            class="ms-2 btn btn-sm btn-{{ $active_stand->sale_validation > 0 ? 'success' : 'secondary' }} {{ $active_stand->menu_lock > 0 ? '' : 'disabled' }}"
                                                            onclick="confirmation('{{ route('stand.income.validate', ['id' => $active_stand_id, 'valid' => $active_stand->sale_validation > 0 ? 0 : 1]) }}', '{{ $active_stand->sale_validation > 0 ? 'Are you sure want to UNPROVE Stand ' . $active_stand_name . ' Income?' : 'Are you sure want to APPROVE Stand ' . $active_stand_name . ' Income?' }}')">
                                                            <i
                                                                class="bi bi-{{ $active_stand->sale_validation > 0 ? 'lock' : 'unlock' }}"></i>
                                                        </button>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </nav>
                        </div>
                    </div>
                    {{-- Income list item --}}
                    <div class="row">
                        <div class="col-12">
                            <div class="bg-secondary bg-opacity-25 rounded shadow-sm mx-2">
                                <div class="scroll-container-2 scroll-container-lg mt-2 pb-2">
                                    <?php
                                    $i = 1;
                                    ?>
                                    @foreach ($income_list as $income)
                                        <div class="card shadow-sm mt-2 mx-2 py-1 border-0 bg-white ">
                                            <div class="row">
                                                <div class="col-12 d-flex">
                                                    <span
                                                        class="my-auto ms-2 text-dark fw-light">{{ format_date_time($income->created_at) }}</span>
                                                    <span
                                                        class="my-auto ms-2 text-primary-emphasis">{{ ($income->customer ? ' - ' : '') . substr($income->customer, 0, 15) . (strlen($income->customer) > 15 ? '...' : '') }}</span>
                                                    <span
                                                        class="ms-auto my-auto fw-light d-none d-md-inline">{{ 'trsansaction ' . ($income->discount > 0 ? '( ' : '') }}
                                                        <span
                                                            class="text-secondary">{{ $income->discount > 0 ? '-' . format_currency($income->discount) : '' }}</span>
                                                        {{ ($income->discount > 0 ? ' )' : '') . ' : ' }}</span>
                                                    <span
                                                        class="ms-2 me-2 my-auto text-primary-emphasis  d-none d-md-inline">{{ format_currency($income->transaction) }}</span>
                                                    {{-- Order Trigger Button --}}
                                                    <button
                                                        class="ms-auto ms-md-0 {{ $auth_user->id == $active_stand->pic_id ? 'me-1' : 'me-2' }} btn btn-sm btn-light position-relative "
                                                        data-bs-toggle="modal" data-bs-target="#orderIncomeModal"
                                                        onclick="setOrderIncome({{ $income }})">
                                                        <i class="bi bi-bag-check"> </i>
                                                    </button>
                                                    @if ($auth_user->id == $active_stand->pic_id)
                                                        {{-- Delete Button --}}
                                                        <div
                                                            onclick="{{ $active_stand->sale_validation == 0 ? '' : 'notif("You can not delete Income Item after Stand Income validated by Operational Officer.")' }}">
                                                            <button
                                                                class="me-2 btn btn-sm btn-danger {{ $active_stand->sale_validation > 0 ? 'disabled' : '' }}"
                                                                onclick="confirmation('{{ route('stand.sale.delete', ['id' => $income->id]) }}', '{{ 'Are you sure want to delete ' . $income->customer . ' transaction from Stand ' . $active_stand_name . ' Income?' }}')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row d-md-none">
                                                <div class="col-12 d-flex">
                                                    <span
                                                        class="ms-2 fw-light">{{ 'trsansaction ' . ($income->discount > 0 ? '( ' : '') }}
                                                        <span
                                                            class="text-secondary {{ $income->discount == 0 ? 'd-none' : '' }}">{{ '-' . format_currency($income->discount) }}</span>
                                                        {{ ($income->discount > 0 ? ' )' : '') . ' : ' }}</span>
                                                    <span
                                                        class="ms-2 me-2 text-primary-emphasis">{{ format_currency($income->transaction) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $i++; ?>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Income Order Modal -->
                    <div class="modal fade" id="orderIncomeModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content shadow mx-3 mt-5">
                                <div class="modal-header py-1 ps-3 pe-2">
                                    <span class="modal-title fs-5 text-primary-emphasis">
                                        <i class="bi bi-bag-check border-secondary border-end me-2 pe-2"></i>
                                        {{ 'Stand Income Order' }}
                                    </span>
                                    <button type="button" class="btn btn-sm ms-auto" data-bs-dismiss="modal"
                                        aria-label="Close"><i class="bi bi-x-lg"></i></button>
                                </div>
                                <div class="modal-body bg-light p-1 px-3">
                                    <div class="row mt-md-2">
                                        <div class="col-4 col-md-2 text-end pe-0">
                                            <span class="fw-light">{{ 'Stand :' }}</span>
                                        </div>
                                        <div class="col-8 col-md-4 d-flex ps-1">
                                            <span class="fw-normal text-dark">
                                                {{ $active_stand_name }}</span>
                                        </div>
                                        <div class="col-4 col-md-2 text-end pe-0">
                                            <span class="fw-light">{{ 'Cashier :' }}</span>
                                        </div>
                                        <div class="col-8 col-md-4 d-flex ps-1">
                                            <span class="fw-normal text-dark" id="income_order_cashier"></span>
                                        </div>
                                    </div>
                                    <div class="row mt-md-2">
                                        <div class="col-4 col-md-2 text-end pe-0">
                                            <span class="fw-light">{{ 'Customer :' }}</span>
                                        </div>
                                        <div class="col-8 col-md-4 d-flex ps-1">
                                            <span class="fw-normal text-dark" id="income_order_customer"></span>
                                        </div>
                                        <div class="col-4 col-md-2 text-end pe-0">
                                            <span class="fw-light">{{ 'Time :' }}</span>
                                        </div>
                                        <div class="col-8 col-md-4 d-flex ps-1">
                                            <span class="fw-normal text-dark" id="income_order_time"></span>
                                        </div>
                                    </div>
                                    <div class="row mt-md-2">
                                        <div class="col-4 col-md-2 text-end pe-0">
                                            <span class="fw-light">{{ 'Orders Item :' }}</span>
                                        </div>
                                        <div class="col-8 col-md-10 d-flex">
                                            <span class="border-secondary-subtle border-1 border-bottom my-auto"
                                                style="width: 100%; height:1px;"></span>
                                        </div>
                                    </div>
                                    <div class="row justify-content-end">
                                        <div class="col-12 col-md-10">
                                            <div class="row pt-2 pt-md-0" id="order_container"></div>
                                            <div class="row justify-content-end">
                                                <div class="col-md-12 col-8 d-flex">
                                                    <span
                                                        class="border-secondary-subtle border-1 border-bottom my-auto"
                                                        style="width: 100%; height:1px;"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-md-2">
                                        <div class="col-4 col-md-auto  ms-md-auto text-end pe-0">
                                            <span class="fw-light">{{ 'Subtotal : ' }}</span>
                                        </div>
                                        <div class="col-8 col-md-auto me-md-auto ps-1">
                                            <span class="text-dark" id="income_order_subtotal"></span>
                                        </div>
                                        <div class="col-4 col-md-auto ms-md-auto  text-end pe-0">
                                            <span class="fw-light"> {{ 'Discount : ' }}</span>
                                        </div>
                                        <div class="col-8 col-md-auto me-md-auto ps-1">
                                            <span class="text-dark" id="income_order_discount"></span>
                                        </div>
                                        <div class="col-4 col-md-auto ms-md-auto text-end pe-0">
                                            <span class="fw-light"> {{ 'Total : ' }}</span>
                                        </div>
                                        <div class="col-8 col-md-auto me-md-auto ps-1">
                                            <span class="text-white bg-primary rounded px-2"
                                                id="income_order_total"></span>
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

    <script>
        const auth_user = <?php echo $auth_user; ?>;

        function show_tab(target) {
            const tabs = 3;
            // deactive all tabs
            for (let number = 1; number <= tabs; number++) {
                let tab = document.getElementById('tab_' + number);
                let content = document.getElementById('content_' + number);
                // set tab to deactive
                tab.setAttribute('class', 'nav-link bg-white px-3 py-1 border');
                // set content to hide
                content.setAttribute('hidden', '');
            }
            let tab = document.getElementById('tab_' + target);
            let content = document.getElementById('content_' + target);
            // set tab to active
            tab.setAttribute('class', 'nav-link active bg-secondary text-white px-3 py-1 borde');
            // set content to show
            content.removeAttribute('hidden');
        }

        function setOrderIncome(sale) {
            var cashier = document.getElementById('income_order_cashier');
            var customer = document.getElementById('income_order_customer');
            var time = document.getElementById('income_order_time');
            var subtotal = document.getElementById('income_order_subtotal');
            var total = document.getElementById('income_order_total');
            var discount = document.getElementById('income_order_discount');

            cashier.innerHTML = sale.cashier.name;
            customer.innerHTML = sale.customer;
            time.innerHTML = sale.created_at.slice(11, 19);
            subtotal.innerHTML = formatRupiah(sale.transaction + sale.discount);
            discount.innerHTML = formatRupiah(sale.discount);
            total.innerHTML = formatRupiah(sale.transaction);

            var container = document.getElementById('order_container');
            if (container) {
                container.innerHTML = '';
            }

            var order_list = sale.order;

            order_list.forEach(order_item => {
                // create order
                var order = document.createElement('div');
                order.className = 'col-12 mb-2 d-flex';

                // create amount span 
                const span_amn = document.createElement('span');
                span_amn.className = 'text-dark px-1 ms-2 rounded bg-white';
                span_amn.innerHTML = order_item.amount;
                order.appendChild(span_amn);

                // create menu span 
                var menu_name = order_item.menu.name;
                const span_menu = document.createElement('span');
                span_menu.className = 'text-dark ms-2 d-md-inline d-none';
                span_menu.innerHTML = menu_name;
                order.appendChild(span_menu);
                const span_menu_sm = document.createElement('span');
                span_menu_sm.className = 'text-dark ms-2 d-md-none';
                span_menu_sm.innerHTML = menu_name.slice(0, 13) + (menu_name.length > 10 ? '...' : '');
                order.appendChild(span_menu_sm);

                // create price span 
                const span_price = document.createElement('span');
                span_price.className = 'text-dark fw-light ms-2';
                span_price.innerHTML = ' - ' + formatRupiah(order_item.menu.price);
                order.appendChild(span_price);

                // create total span 
                const span_total = document.createElement('span');
                span_total.className = 'text-dark ms-auto me-2';
                span_total.innerHTML = formatRupiah(order_item.amount * order_item.menu.price);
                order.appendChild(span_total);


                order_container.appendChild(order);
            });
        }

        function setExpenseReceipt(expense) {
            const image = document.getElementById('expense_receipt_image');
            const download = document.getElementById('expense_receipt_download');
            const name = document.getElementById('expense_receipt_name');
            const status = document.getElementById('expense_receipt_status');

            status.innerHTML = expense.operational_id == null ? 'Unvalidated' : 'Validated by ' + expense.operational.name;
            image.setAttribute('src', '/storage/images/receipt/stand/expense/' + expense.reciept);
            download.setAttribute('href', '/storage/images/receipt/stand/expense/' + expense.reciept);
            name.innerHTML = expense.reciept;

            if (auth_user.roles_id == 3) {
                const id = document.getElementById('expense_receipt_id');
                const validate = document.getElementById('expense_receipt_validate');
                const button = document.getElementById('expense_receipt_button');

                id.value = expense.id;
                validate.value = expense.operational_id == null ? 1 : 0;
                button.innerHTML = expense.operational_id == null ? 'Validate' : 'Unvalidate';
            }
        }

        function setUpdateStock(menu) {
            const id = document.getElementById('update_stock_id');
            const name = document.getElementById('update_stock_menu');
            const stock = document.getElementById('update_stock_stock');

            id.value = menu.id;
            name.innerHTML = menu.name;
            stock.innerHTML = menu.stock;
        }

        function sameReceipt() {
            let receiptUpload = document.getElementById('add_stand_expense_receipt');
            let receiptSame = document.getElementById('add_stand_expense_receipt_same_container');
            let sameReceiptCheck = document.getElementById('same_receipt_check');
            if (sameReceiptCheck.checked == true) {
                receiptUpload.setAttribute('hidden', '');
                receiptSame.removeAttribute('hidden');
            } else {
                receiptUpload.removeAttribute('hidden');
                receiptSame.setAttribute('hidden', '');
            }
        }

        function setTotalPrice(type) {
            const price = document.getElementById('add_' + type + '_price');
            const qty = document.getElementById('add_' + type + '_qty');
            const total = document.getElementById('add_' + type + '_total');

            total.innerHTML = formatRupiah(price.value * qty.value);
        }

        function collapse_toggle(trigger_id) {
            if (trigger_id == 'collapseDetailTrigger') {
                var container1 = document.getElementById('collapseTab1');
                var container2 = document.getElementById('collapseTab2');
                var container3 = document.getElementById('collapseTab3');
                var trigger = document.getElementById('collapseTabTrigger');
                if (container1.classList.contains('show') || container2.classList.contains('show') || container3.classList
                    .contains('show')) {
                    container1.classList.remove('show');
                    container2.classList.remove('show');
                    container3.classList.remove('show');
                    trigger.classList.remove('bi-chevron-up');
                    trigger.classList.add('bi-chevron-down');
                }
            } else {
                var container = document.getElementById('collapseDetail');
                var trigger = document.getElementById('collapseDetailTrigger');
                if (container.classList.contains('show')) {
                    container.classList.remove('show');
                    trigger.classList.remove('bi-chevron-up');
                    trigger.classList.add('bi-chevron-down');
                }
            }

            var trigger = document.getElementById(trigger_id);
            if (trigger.classList.contains('bi-chevron-up')) {
                trigger.classList.remove('bi-chevron-up');
                trigger.classList.add('bi-chevron-down');
                if (trigger_id !== 'collapseDetailTrigger') {
                    var container1 = document.getElementById('collapseTab1');
                    var container2 = document.getElementById('collapseTab2');
                    var container3 = document.getElementById('collapseTab3');
                    container1.classList.remove('show');
                    container2.classList.remove('show');
                    container3.classList.remove('show');
                }
            } else {
                trigger.classList.remove('bi-chevron-down');
                trigger.classList.add('bi-chevron-up');
                if (trigger_id !== 'collapseDetailTrigger') {
                    var container1 = document.getElementById('collapseTab1');
                    var container2 = document.getElementById('collapseTab2');
                    var container3 = document.getElementById('collapseTab3');
                    container1.classList.add('show');
                    container2.classList.add('show');
                    container3.classList.add('show');
                }
            }
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
