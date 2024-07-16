<?php
use Illuminate\Support\Carbon;
?>
<x-app-layout>
    <x-slot name="header">
        <nav aria-label="breadcrumb w-auto b-0" style="--bs-breadcrumb-divider: '>';">
            <ol class="breadcrumb">
                <li class="breadcrumb-item " aria-current="page"><span
                        class="text-decoration-none text-black">{{ 'Foods' }}</span></li>
                <li class="breadcrumb-item font-semibold text-xl text-gray-800 leading-tight" aria-current="page">
                    {{ __('Stand') }}</li>
            </ol>
        </nav>
    </x-slot>
    <?php $stand_exist = $stand !== '' ? true : false; ?>

    <div class="container py-3 px-10">
        <div class="row">
            {{-- Dashboard --}}
            <div class="col-lg-8 border-end border-primary">
                <div class="card border border-info bg-primary shadow mb-3" style="--bs-bg-opacity: .02;">
                    <div class="card-header border-primary border-bottom fs-4 text-primary">
                        @if ($stand_exist)
                            {{ $stand->name }}
                        @endif
                        Dashboard
                        @if (Auth::user()->roles_id == 3)
                            <?php
                            $disabled = $stand_exist ? '' : 'disabled';
                            ?>
                            {{-- Delete Stand --}}
                            <button class="btn btn-light text-dark float-end py-1 pe-2 {{ $disabled }}"
                                data-bs-toggle="modal" data-bs-target="#delProgram">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-trash3 text-danger d-inline" viewBox="0 0 16 16">
                                    <path
                                        d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                </svg> Delete
                            </button>
                            {{-- Edit Stand --}}
                            <button class="btn btn-light text-dark float-end py-1 pe-2 {{ $disabled }}"
                                data-bs-toggle="modal" data-bs-target="#editProgram">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-pen-fill text-warning d-inline"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001" />
                                </svg> Edit
                            </button>
                            <!-- Add Stand Button Trigger Modal -->
                            <button class="btn btn-light text-dark float-end py-1 pe-2" data-bs-toggle="modal"
                                data-bs-target="#addStandModal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-plus d-inline text-primary" viewBox="0 0 16 16">
                                    <path
                                        d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                </svg> Add Stand
                            </button>
                            <!-- Add Stand Modal -->
                            <div class="modal fade" id="addStandModal" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-top border-warning text-dark fs-6">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Stand</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form method="post" action="{{ route('food.stand.add') }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('put')
                                            <div class="modal-body">
                                                <div class="row g-3 align-items-center">
                                                    <div class="col-4">
                                                        <label for="stand_name"
                                                            class="form-label d-inline-block float-end">Name</label>
                                                    </div>
                                                    <div class="col-7 ms-2">
                                                        <input type="text"
                                                            class="form-control form-control-sm d-inline-block rounded-lg border hover:ring-1 hover:ring-blue-500"
                                                            name="name" id="name"
                                                            placeholder="ex: Stand Blaterian 1" required
                                                            value="{{ old('name') }}">
                                                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                                    </div>
                                                </div>
                                                <div class="row g-3 align-items-center mt-1 ">
                                                    <div class="col-4">
                                                        <label for="pic_id"
                                                            class="form-label d-inline-block float-end">Person In
                                                            Charge</label>
                                                    </div>
                                                    <div class="col-7 ms-2">
                                                        <select name="pic_id" id="pic_id"
                                                            class="form-select rounded-lg border hover:ring-1 hover:ring-blue-500"
                                                            aria-label="Default select example" required>
                                                            <option selected value="" disabled>Choose the PIC
                                                                here
                                                            </option>
                                                            @foreach ($users as $user)
                                                                <?php $selected = $user->id == old('pic_id') ? 'selected' : ''; ?>
                                                                <option value="{{ $user->id }}" {{ $selected }}>
                                                                    {{ $user->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <x-input-error :messages="$errors->get('pic_id')" class="mt-2" />
                                                    </div>
                                                </div>
                                                <div class="row g-3 align-items-center mt-1">
                                                    <div class="col-4">
                                                        <label for="date"
                                                            class="form-label d-inline-block float-end">Date</label>
                                                    </div>
                                                    <div class="col-7 ms-2">
                                                        <input type="date"
                                                            class="form-control d-inline-block rounded-lg border hover:ring-1 hover:ring-blue-500"
                                                            name="date" id="date" value="{{ old('date') }}">
                                                        <x-input-error :messages="$errors->get('date')" class="mt-2" />
                                                    </div>
                                                </div>
                                                <div class="row g-3 align-items-center mt-1">
                                                    <div class="col-4">
                                                        <label for="time"
                                                            class="form-label d-inline-block float-end">Time</label>
                                                    </div>
                                                    <div class="col-7 ms-2">
                                                        <input type="time"
                                                            class="form-control d-inline-block rounded-lg border hover:ring-1 hover:ring-blue-500"
                                                            name="time" id="time"
                                                            value="{{ old('time') }}">
                                                        <x-input-error :messages="$errors->get('time')" class="mt-2" />
                                                    </div>
                                                </div>
                                                <div class="row g-3 align-items-center mt-1">
                                                    <div class="col-4">
                                                        <label for="place"
                                                            class="form-label d-inline-block float-end">Place</label>
                                                    </div>
                                                    <div class="col-7 ms-2">
                                                        <input type="text"
                                                            class="form-control form-control-sm d-inline-block rounded-lg border hover:ring-1 hover:ring-blue-500"
                                                            name="place" id="place"
                                                            value="{{ old('place') }}">
                                                        <x-input-error :messages="$errors->get('place')" class="mt-2" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn shadow border-primary">Add</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @if ($stand_exist)
                                <!-- Delete Stand Modal -->
                                <div class="modal fade text-dark fs-6 fw-light" id="delProgram" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-top border-warning">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">
                                                    {{ $stand->name }}</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form method="post" action="{{ route('stand.delete', $stand->id) }}">
                                                @csrf
                                                @method('put')
                                                <div class="modal-body">
                                                    <p class="mx-5 fw-light">All expense, income, and menu
                                                        in
                                                        {{ $stand->name }}
                                                        will be lost. Please make sure you have back up all
                                                        the data needed.</p>
                                                    <div class="row g-3 align-items-center">
                                                        <div class="col-auto mx-auto border-bottom">
                                                            <label for="password"
                                                                class="form-label d-inline-block">Password
                                                                Needed to Authorize</label>
                                                        </div>
                                                    </div>
                                                    <div class="row g-3 align-items-center mt-1">
                                                        <div class="col-auto ms-auto">
                                                            <label for="password"
                                                                class="form-label d-inline-block">Password</label>
                                                        </div>
                                                        <div class="col-auto ms-2 me-auto">
                                                            <input id="password"
                                                                class="form-control rounded-lg border hover:ring-1 hover:ring-blue-500"
                                                                type="password" name="password" required
                                                                value="{{ old('password') }}"
                                                                autocomplete="current-password" />
                                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                                            <div class="block mt-2">
                                                                <label for="show_password_3"
                                                                    class="inline-flex items-center"
                                                                    onclick="show_password('password')">
                                                                    <input id="show_password_3" type="checkbox"
                                                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                                        name="remember">
                                                                    <span
                                                                        class="ms-2 text-sm text-gray-600">{{ __('Show Password') }}</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit"
                                                        class="btn btn-outline-danger shadow">Delete</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- Edit Stand Modal -->
                                <div class="modal fade fs-6 text-dark" id="editProgram" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-top border-warning">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">
                                                    {{ $stand->name }}</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form method="post" action="{{ route('stand.update', $stand->id) }}"
                                                onsubmit="return confirm('Are you sure ALL DATA is filled correctly?')">
                                                @csrf
                                                @method('put')
                                                <div class="modal-body">
                                                    <div class="row g-3 align-items-center">
                                                        <div class="col-auto mx-auto border-bottom">
                                                            <label for="name"
                                                                class="form-label d-inline-block">Setting
                                                                new name and person in charge.</label>
                                                        </div>
                                                    </div>
                                                    <div class="row g-3 mt-1">
                                                        <div class="col-3 ms-auto text-end">
                                                            <label for="name" class="form-label mb-0">New
                                                                name</label>
                                                        </div>
                                                        <div class="col-7 ms-2 me-auto">
                                                            <input id="name"
                                                                class="form-control rounded-lg border hover:ring-1 hover:ring-blue-500"
                                                                type="text" name="name"
                                                                placeholder="current: {{ $stand->name }}"
                                                                value="{{ old('name') }}" />
                                                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                                        </div>
                                                    </div>
                                                    <div class="row g-3 mt-1">
                                                        <div class="col-3 ms-auto text-end">
                                                            <label for="pic_id"
                                                                class="form-label d-inline-block">New
                                                                PIC</label>
                                                        </div>
                                                        <div class="col-7 ms-2 me-auto">
                                                            <select name="pic_id" id="pic_id"
                                                                class="form-select rounded-lg border hover:ring-1 hover:ring-blue-500">
                                                                <option selected class="text-secondary"
                                                                    value="">
                                                                    Choose new PIC here
                                                                </option>
                                                                @foreach ($users as $user)
                                                                    <?php $selected = old('pic_id') == $user->id ? 'selected' : ''; ?>
                                                                    <option value="{{ $user->id }}"
                                                                        {{ $selected }}>
                                                                        {{ $user->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <x-input-error :messages="$errors->get('pic_id')" class="mt-2" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit"
                                                        class="btn border-warning shadow">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="card-body py-2">
                        <div class="row ">
                            <div class="col-lg-6">
                                <p class="my-2">{{ __('Date :') }}
                                    <span class="float-end">
                                        @if ($stand_exist)
                                            {{ Carbon::parse($stand->date)->format('d, M Y') }}
                                        @endif
                                    </span>
                                </p>
                                <p class="my-2">{{ __('Place :') }}
                                    <span class="float-end">
                                        @if ($stand_exist)
                                            {{ $stand->place }}
                                        @endif
                                    </span>
                                </p>
                                <p class="my-2">{{ __('In Charge :') }}
                                    <span class="float-end">
                                        @if ($stand_exist)
                                            {{ $stand->pic->name }}
                                        @endif
                                    </span>
                                </p>
                            </div>
                            <div class="col-lg-6">
                                <p class="my-2">{{ __('Expense :') }}
                                    <span class="float-end">
                                        @if ($stand_exist)
                                            {{ Number::currency($stand->expense !== null ? $stand->expense : 0, in: 'IDR') }}
                                        @endif
                                    </span>
                                </p>
                                <p class="my-2">{{ __('Income :') }}
                                    <span class="float-end">
                                        @if ($stand_exist)
                                            {{ Number::currency($stand->income !== null ? $stand->income : 0, in: 'IDR') }}
                                        @endif
                                    </span>
                                </p>
                                <?php
                                if ($stand_exist) {
                                    $percent = $stand->income > 0 ? ($stand->profit / $stand->expense) * 100 : 0;
                                    $percent = round($percent, 1);
                                } else {
                                    $percent = 0;
                                }
                                ?>
                                <p class="my-2">{{ __('Profit (' . $percent . '%) :') }}
                                    <span class="float-end">
                                        @if ($stand_exist)
                                            {{ Number::currency($stand->profit !== null ? $stand->profit : 0, in: 'IDR') }}
                                        @endif
                                    </span>
                                </p>
                                <p class="my-2 rounded-lg bg-primary text-white fw-light p-2" data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    data-bs-title="This is showing the stand balance, based on validated expense and income.">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-wallet2 d-inline mb-1 me-1 text-white"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9a1.5 1.5 0 0 1 1.432-1.499zM5.562 3H13V1.78a.5.5 0 0 0-.621-.484zM1.5 4a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z" />
                                    </svg>
                                    @if ($stand_exist)
                                        {{ __('Balance : ' . Number::currency($stand->balance !== null && $stand_exist ? $stand->balance : 0, in: 'IDR')) }}
                                        <span class="text-sm float-end">
                                            @if ($stand->sale_validation == 0)
                                                Active
                                            @else
                                                <span class="text-warning px-1">Not Active.</span>
                                            @endif
                                        </span>
                                    @endif
                                    {{ __('Balance : -') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($stand_exist)
                {{-- Stand Control --}}
                <div class="col-lg-4">
                    <div class="card border-0 mb-3">
                        <div class="card-header bg-white fs-5 fw-bold text-dark">{{ 'Stand Control' }}</div>
                        <div class="card-body">
                            <div class="row m-0">
                                <div class="col-lg-12">
                                    <form method="post" id="formFindStand" action="{{ route('food.stand.find') }}">
                                        @csrf
                                        @method('put')
                                        <div class="row">
                                            <div class="col-4">
                                                <label for="category"
                                                    class="form-label d-inline-block float-end">Category</label>
                                            </div>
                                            <div class="col-7 ms-2">
                                                <select name="category" id="category"
                                                    class="h-full rounded-md border-0 bg-transparent py-0 pl-2 pr-7 text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                                                    aria-label="Default select example" required>
                                                    <?php $categories = ['date', 'expense', 'income', 'profit']; ?>
                                                    @foreach ($categories as $category)
                                                        <?php $selected = $category == Session::get('category') ? 'selected' : ''; ?>
                                                        <option value="{{ $category }}" {{ $selected }}>
                                                            {{ Str::of($category)->ucfirst() }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <label for="order"
                                                    class="form-label d-inline-block float-end">Order</label>
                                            </div>
                                            <div class="col-7 ms-2">
                                                <select name="order" id="order"
                                                    class="h-full rounded-md border-0 bg-transparent py-0 pl-2 pr-7 text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                                                    required>
                                                    <?php $orders = [['Ascending', '', 'asc'], ['Descending', 'selected', 'desc']]; ?>
                                                    @foreach ($orders as $order)
                                                        <?php
                                                        $selected = $order[2] == Session::get('order') ? 'selected' : '';
                                                        ?>
                                                        <option value="{{ $order[2] }}" {{ $selected }}>
                                                            {{ $order[0] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row justify-content-between px-3">
                                            <?php
                                            $prev_class = $stand_control['on_first_page'] ? 'disabled text-secondary' : 'btn-light';
                                            $prev_link = !$stand_control['on_first_page'] ? $stand_control['prev_url'] : '';
                                            $next_class = $stand_control['has_more_page'] ? 'btn-light' : 'disabled text-secondary';
                                            $next_link = $stand_control['has_more_page'] ? $stand_control['next_url'] : '';
                                            ?>
                                            <div class="col-sm-4 mt-2 col-6">
                                                <a class="btn {{ $prev_class }} py-0" href="{{ $prev_link }}"
                                                    style="width: 100%"><svg xmlns="http://www.w3.org/2000/svg"
                                                        width="16" height="16" fill="currentColor"
                                                        class="bi bi-caret-left-fill float-start mt-1 text-primary"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z" />
                                                    </svg>prev
                                                </a>
                                            </div>
                                            <div class="col-sm-4 mt-2 col-6">
                                                <a class="btn {{ $next_class }} py-0" href="{{ $next_link }}"
                                                    style="width: 100%">next
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor"
                                                        class="bi bi-caret-right-fill float-end mt-1 text-primary"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z" />
                                                    </svg>
                                                </a>
                                            </div>
                                            <div class="col-sm-4 mt-2">
                                                <button class="btn bg-primary-subtle py-0 col-sm-4" type="submit"
                                                    form="formFindStand" style="width: 100%">Go
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor"
                                                        class="bi bi-search float-end mt-1 text-primary"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        {{-- Tab --}}
        <ul class="nav nav-tabs">
            <li class="nav-item"><a id="tab_1" onclick="show_tab(1)" class="nav-link">Expense</a></li>
            <li class="nav-item"><a id="tab_2" onclick="show_tab(2)" class="nav-link me-2">Income
                    @if ($stand_exist)
                        @if ($stand->menu_lock != null && $stand->sale_validation == 0)
                            <span class="text-success">{{ ' (Active)' }}</span>
                        @endif
                    @endif
                </a></li>
            <li class="nav-item"><a id="tab_3" onclick="show_tab(3)" class="nav-link">Menu</a></li>
        </ul>

        {{-- Expense Tab --}}
        <div id="content_1" class="row pt-0">

            {{-- Expense Items --}}
            <div class="col-lg-12 mb-4 px-2">
                <div class="bg-white shadow mx-0 p-3 sm:p-6 rounded-lg">
                    <div class="h5 fw-bold d-inline-block">{{ __('Stand Expense Item |') }}
                        <span class="fs-6">Total Expense :
                            <span class="fw-light">
                                @if ($stand_exist)
                                    {{ Number::currency($stand->expense()->sum('total_price'), 'IDR') }}
                                @else
                                    {{ ' - ' }}
                                @endif
                            </span>
                        </span>
                    </div>
                    @if ($stand_exist)

                        @if ($stand->sale_validation == 0)
                            @if (Auth::user()->id == $stand->pic_id)
                                <!-- Add Expense Button Trigger Modal -->
                                <button class="btn text-dark float-end pt-0 pe-2" data-bs-toggle="modal"
                                    data-bs-target="#addExpenseItemModal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-plus d-inline text-primary"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                    </svg>
                                    Add Expense Item
                                </button>

                                <!-- Add Expense Item Modal -->
                                <div class="modal fade" id="addExpenseItemModal" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-top border-warning">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Add
                                                    {{ $stand->name }}
                                                    Expense Item</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form method="post" enctype="multipart/form-data"
                                                action="{{ route('stand.expense.add', ['stand_id' => $stand->id]) }}">
                                                @csrf
                                                @method('put')
                                                <div class="modal-body">
                                                    <div class="text-center mb-2">
                                                        <span class="ms-3 d-block" style="font-size: 12px">
                                                            <span class="text-danger ">*</span>
                                                            Don't use comma (,) or dot (.). Write the numbers only.
                                                        </span>
                                                    </div>
                                                    <div class="text-center mb-2">
                                                        <span class="ms-3 border-bottom pb-1 d-block"
                                                            style="font-size: 12px">
                                                            <span class="text-danger ">**</span>
                                                            Max size: 5Mb, Type: .jpg, .jpeg, .png, .heic.
                                                        </span>
                                                    </div>
                                                    <div class="row g-3 align-items-center">
                                                        <div class="col-3">
                                                            <label for="name"
                                                                class="form-label d-inline-block float-end">Item
                                                                Name</label>
                                                        </div>
                                                        <div class="col-8 ms-2">
                                                            <input type="text" class="form-control d-inline-block"
                                                                name="expense_name" id="name" required
                                                                value="{{ old('expense_name') }}">
                                                            <x-input-error :messages="$errors->get('expense_name')" class="mt-2" />
                                                        </div>
                                                    </div>
                                                    <div class="row g-3 align-items-center mt-1">
                                                        <div class="col-3">
                                                            <label for="price"
                                                                class="form-label d-inline-block float-end">Price<span
                                                                    class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="col-8 ms-2">
                                                            <input type="number" class="form-control d-inline-block"
                                                                name="expense_price" id="price" required
                                                                value="{{ old('expense_price') }}">
                                                            <x-input-error :messages="$errors->get('expense_price')" class="mt-2" />
                                                        </div>
                                                    </div>
                                                    <div class="row g-3 align-items-center mt-1">
                                                        <div class="col-3">
                                                            <label for="unit"
                                                                class="form-label d-inline-block float-end">Unit</label>
                                                        </div>
                                                        <div class="col-8 ms-2">
                                                            <input type="text" class="form-control d-inline-block"
                                                                name="expense_unit" id="unit" required
                                                                value="{{ old('expense_unit') }}"
                                                                placeholder="pcs, person, box, etc..">
                                                            <x-input-error :messages="$errors->get('expense_unit')" class="mt-2" />
                                                        </div>
                                                    </div>
                                                    <div class="row g-3 align-items-center mt-1">
                                                        <div class="col-3">
                                                            <label for="qty"
                                                                class="form-label d-inline-block float-end">Quantity<span
                                                                    class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="col-8 ms-2">
                                                            <input type="number" class="form-control d-inline-block"
                                                                name="expense_qty" id="qty" required
                                                                value="{{ old('expense_qty') }}">
                                                            <x-input-error :messages="$errors->get('expense_qty')" class="mt-2" />
                                                        </div>
                                                    </div>
                                                    <div class="row g-3 align-items-center mt-1">
                                                        <div class="col-3">
                                                            <label for="expense_reciept"
                                                                class="form-label d-inline-block float-end">Reciept<span
                                                                    class="text-danger ">**</span></label>
                                                        </div>
                                                        <div class="col-8 ms-2">
                                                            <div class="row" id="receipt_upload">
                                                                <input type="file"
                                                                    class="form-control form-control-sm ms-3"
                                                                    style="width: 92%" name="expense_reciept"
                                                                    id="expense_reciept"
                                                                    value="{{ old('expense_reciept') }}">
                                                                <x-input-error :messages="$errors->get('expense_reciept')" class="mt-2" />
                                                            </div>
                                                            <div class="row" id="receipt_same" hidden>
                                                                <div class="input-group input-group-sm">
                                                                    <label for="expense_receipt_same"
                                                                        class="input-group-text">Same as item</label>
                                                                    <select name="expense_receipt_same"
                                                                        class="form-select py-0 d-inline"
                                                                        id="expense_receipt_same">
                                                                        <?php $i = 1; ?>
                                                                        @foreach ($inverted_expense_items as $expense_item)
                                                                            <?php $default = $i === 1 ? 'selected' : ''; ?>
                                                                            <option value="{{ $expense_item->id }}"
                                                                                {{ $default }}>
                                                                                {{ $expense_item->name }}</option>
                                                                            <?php $i++; ?>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            @if ($stand->expense > 0)
                                                                <label for="same_receipt_check"
                                                                    class="inline-flex items-center mt-1"
                                                                    onclick="sameReceipt()">
                                                                    <input id="same_receipt_check" type="checkbox"
                                                                        name="same_receipt_check"
                                                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                                                    <span
                                                                        class="ms-2 text-sm text-gray-600">{{ __('same as exist item') }}</span>
                                                                </label>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit"
                                                        class="btn shadow border-primary">Add</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <span
                                class="text-secondary fst-italic float-end">{{ 'Sale is validated, no changes permitted.' }}</span>
                        @endif
                        {{-- Expense Item Table --}}
                        <table class="table">
                            <thead class="table-primary">
                                <th scope="col">#</th>
                                <th scope="col">Item</th>
                                <th scope="col">Price</th>
                                <th scope="col">Unit</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                                <th scope="col">Reciept</th>
                                <th scope="col">Last Update</th>
                            </thead>
                            <tbody class="table-group-divider">
                                @if (Auth::user()->id == $stand->pic_id && $stand->sale_validation == 0)
                                    {{-- Action form --}}
                                    <form method="post" id="formUpdateExpenseItem" enctype="multipart/form-data"
                                        action="{{ route('stand.expense.update', ['pic_id' => $stand->pic_id]) }}">
                                        @csrf
                                        @method('put')
                                        <tr>
                                            <td colspan="5"><span class="fst-italic" style="font-size: 12px">This
                                                    function only accessible
                                                    by PIC only.</span></td>
                                            <td colspan="3" class="text-end"> Action:
                                                {{-- action button --}}
                                                @if (count($expense_items) > 0)
                                                    <button type="submit" form="formUpdateExpenseItem"
                                                        class="px-1 d-inline" value="update" name="action">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor"
                                                            class="bi bi-pen-fill text-primary" viewBox="0 0 16 16">
                                                            <path
                                                                d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001" />
                                                        </svg>
                                                    </button>

                                                    <button type="submit" form="formUpdateExpenseItem"
                                                        class="px-1 d-inline" value="delete" name="action"
                                                        onclick="return confirm('Confirm to delete this item?')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor"
                                                            class="bi bi-trash3 text-danger" viewBox="0 0 16 16">
                                                            <path
                                                                d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                                        </svg>
                                                    </button>
                                                @else
                                                    <span class="bg-light text-secondary fst-italic">disabled</span>
                                                @endif
                                            </td>
                                        </tr>

                                        <tr class="border-warning">
                                            <?php $disabled = count($expense_items) > 0 ? '' : 'disabled'; ?>
                                            <td colspan="2">
                                                <select {{ $disabled }} name="id" id="pic"
                                                    class="form-select py-0" aria-label="Default select example"
                                                    required>
                                                    <option selected value="null">select item</option>
                                                    @foreach ($expense_items as $expense_item)
                                                        @if ($expense_item->operational_id == null)
                                                            <option value="{{ $expense_item->id }}">
                                                                {{ $expense_item->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <x-input-error :messages="$errors->get('id_expense')" class="mt-2" />
                                            </td>
                                            <td>
                                                <input {{ $disabled }} type="number"
                                                    class="form-control py-0 d-inline-block" name="price_expense"
                                                    id="price" placeholder="price">
                                                <x-input-error :messages="$errors->get('price_expense')" class="mt-2" />
                                            </td>
                                            <td>
                                                <input {{ $disabled }} type="text"
                                                    class="form-control py-0 d-inline-block" style="max-width: 110px;"
                                                    name="unit_expense" id="unit" placeholder="unit">
                                                <x-input-error :messages="$errors->get('unit_expense')" class="mt-2" />
                                            </td>
                                            <td>
                                                <input {{ $disabled }} type="number"
                                                    class="form-control py-0 d-inline-block" name="quantity_expense"
                                                    id="qty" style="max-width: 70px;" placeholder="qty">
                                                <x-input-error :messages="$errors->get('quantity_expense')" class="mt-2" />
                                            </td>
                                            <td colspan="3">
                                                <input {{ $disabled }} type="file"
                                                    class="form-control py-0 d-inline-block" name="reciept_expense"
                                                    style="max-width: 100%;" placeholder="reciept.png">
                                                <x-input-error :messages="$errors->get('reciept_expense')" class="mt-2" />
                                            </td>
                                        </tr>
                                    </form>
                                @endif
                                <?php $i = $expense_items->perPage() * ($expense_items->currentPage() - 1) + 1; ?>
                                @foreach ($expense_items as $expense_item)
                                    <tr>
                                        <th scope="row">{{ $i }}</th>
                                        <td>{{ $expense_item->name }}</td>
                                        <td>{{ Number::currency($expense_item->price, 'IDR') }}</td>
                                        <td>{{ $expense_item->unit }}</td>
                                        <td class="ps-4">{{ $expense_item->qty }}</td>
                                        <td>{{ Number::currency($expense_item->total_price, in: 'IDR') }}</td>
                                        <td>
                                            <?php
                                            $operational_name = $expense_item->operational_id != null ? $expense_item->operational->name : 'none';
                                            ?>
                                            <button data-bs-toggle="modal" data-bs-target="#recieptModal"
                                                onclick="setReceipt('{{ $expense_item->id }}' ,'{{ $expense_item->reciept }}', '{{ Auth::user()->roles_id }}' , '{{ $operational_name }}', '{{ Auth::user()->id }}', 'expense')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-receipt text-info d-inline"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27m.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0z" />
                                                    <path
                                                        d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5m8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5" />
                                                </svg>
                                                @if ($expense_item->operational_id != null)
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor"
                                                        class="bi bi-check-circle-fill text-success d-inline"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor"
                                                        class="bi bi-ban text-secondary d-inline" viewBox="0 0 16 16">
                                                        <path
                                                            d="M15 8a6.97 6.97 0 0 0-1.71-4.584l-9.874 9.875A7 7 0 0 0 15 8M2.71 12.584l9.874-9.875a7 7 0 0 0-9.874 9.874ZM16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0" />
                                                    </svg>
                                                @endif
                                            </button>
                                        </td>
                                        <td>{{ $expense_item->updated_at->format('d/M/Y H:i:s') }}</td>
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach
                                {{ $expense_items->links() }}
                            </tbody>


                            <!-- Receipt Modal -->
                            <div class="modal fade pt-5 mb-5" id="recieptModal" tabindex="-1"
                                aria-labelledby="recieptModal" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mt-5 mb-5">
                                    <div class="modal-content">
                                        <div class="modal-header py-1 bg-info-subtle">
                                            <h1 class="modal-title fs-5">Receipt</h1>
                                            <span id="validationReceipt"
                                                class="fst-italic text-secondary mx-auto"></span>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body pt-0">
                                            <p class="mb-0 text-center">
                                                <span class="ms-auto mb-0 d-inline-block" id="receiptTitle"></span>
                                                <a class="d-inline-block my-2 align-middle" id="downloadReceipt"
                                                    download>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor"
                                                        class="bi bi-download text-primary" viewBox="0 0 16 16">
                                                        <path
                                                            d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                                                        <path
                                                            d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z" />
                                                    </svg>
                                                </a>
                                            </p>
                                            <img id="receiptImage" alt="reciept image" class="rounded"
                                                style="height: auto; width: 100%;">
                                        </div>
                                        {{-- Feature for Operational --}}
                                        @if (Auth::user()->roles_id == 3)
                                            <div class="modal-footer">
                                                <p class="me-auto">Action:
                                                    <?php $message = $stand->sale_validation == 0 ? 'this feature only appear to Operational' : 'Sale is validated, no changes permitted'; ?>
                                                    <span
                                                        class="fst-italic text-secondary text-sm">{{ $message }}</span>
                                                </p>
                                                <form method="post" id="formValidateExpenseItem"
                                                    enctype="multipart/form-data"
                                                    action="{{ route('stand.expense.validate') }}">
                                                    @csrf
                                                    @method('put')
                                                    <input type="hidden" id="receiptId" name="id"
                                                        value="">
                                                    @if ($stand->sale_validation == 0)
                                                        <button type="submit" id="receiptButton" value=""
                                                            form="formValidateExpenseItem" name="operational_id"
                                                            data-bs-dismiss="modal"> </button>
                                                    @endif
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        {{-- Income Tab --}}
        <div id="content_2" class="row pt-0">
            <div class="col-lg-12 mb-4 px-2">
                <div class="bg-white shadow mx-0 p-3 sm:p-6 rounded-lg">
                    {{-- Sales Items --}}
                    <div class="h5 fw-bold d-inline-block">{{ __('Sales |') }}
                        <span class="fs-6 ">{{ 'Total Transaction : ' }}
                            <span class="fw-light">
                                @if ($stand_exist)
                                    {{ Number::currency($sales->sum('transaction'), 'IDR') }}
                                @else
                                    {{ ' - ' }}
                                @endif
                            </span>
                        </span>
                    </div>
                    @if ($stand_exist)
                        <div class="float-end">
                            @if ($stand->menu_lock != null)
                                @if (count($sales) > 0)
                                    {{-- Operational Feature --}}
                                    <form method="post" class="d-inline ms-0"
                                        action="{{ route('stand.sales.validate', ['stand_id' => $stand->id]) }}">
                                        @csrf
                                        @method('put')
                                        <input type="hidden" name="stand_id" value="{{ $stand->id }}">
                                        @if ($stand->sale_validation !== 0)
                                            <span
                                                class="text-secondary fst-italic">{{ 'Sale is validated, no changes permitted.' }}</span>
                                            @if (Auth::user()->roles_id == 3)
                                                <button type="submit"
                                                    class="border-start border-danger bg-danger-subtle text-danger ps-2 pe-2 "
                                                    value="{{ 0 }}" name="operational_id"
                                                    data-bs-dismiss="modal">
                                                    UNVALIDATE
                                                </button>
                                            @endif
                                        @else
                                            @if (Auth::user()->roles_id == 3)
                                                <button type="submit"
                                                    class="border-start border-success bg-success-subtle text-success ps-2 pe-2 "
                                                    value="{{ Auth::user()->id }}" name="operational_id"
                                                    data-bs-dismiss="modal">
                                                    VALIDATE
                                                </button>
                                            @endif
                                        @endif
                                    </form>
                                @endif
                            @else
                                <span
                                    class="text-secondary fst-italic d-inline-block">{{ 'Menu must be locked to activate Stand Sales ' }}</span>
                            @endif

                        </div>
                        {{-- Income Item Table --}}
                        <table class="table">
                            <thead class="table-primary">
                                <th scope="col">#</th>
                                <th scope="col" class="text-center">Time</th>
                                <th scope="col" class="text-center">Cashier</th>
                                <th scope="col" class="text-center">Menu</th>
                                <th scope="col" class="text-center">Price (item)</th>
                                <th scope="col" class="text-center">Amount</th>
                                <th scope="col" class="text-center">Discount</th>
                                <th scope="col" class="text-center">Transaction</th>
                                <th scope="col" class="text-center">Customer</th>
                            </thead>
                            <tbody class="table-group-divider">
                                @if (Auth::user()->id == $stand->pic_id && $stand->sale_validation == 0)
                                    {{-- Action form --}}
                                    <form method="post" id="formUpdateSaleItem"
                                        action="{{ route('stand.sale.update', $stand->id) }}">
                                        @csrf
                                        @method('put')
                                        <tr>
                                            <td colspan="5"><span class="fst-italic" style="font-size: 12px">This
                                                    function only accessible
                                                    by PIC only.</span></td>
                                            <td colspan="4" class="text-end"> Action:
                                                {{-- action button --}}
                                                @if (count($sales) > 0 && $stand->sale_validation == null)
                                                    <button type="submit" form="formUpdateSaleItem"
                                                        class="px-1 d-inline" value="update" name="action">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor"
                                                            class="bi bi-pen-fill text-primary" viewBox="0 0 16 16">
                                                            <path
                                                                d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001" />
                                                        </svg>
                                                    </button>

                                                    <button type="submit" form="formUpdateSaleItem"
                                                        class="px-1 d-inline" value="delete" name="action"
                                                        onclick="return confirm('Confirm to delete this item?')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor"
                                                            class="bi bi-trash3 text-danger" viewBox="0 0 16 16">
                                                            <path
                                                                d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                                        </svg>
                                                    </button>
                                                @else
                                                    <span class="text-secondary fst-italic">disabled</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <?php $disabled = count($sales) > 0 && $stand->sale_validation == null ? '' : 'disabled'; ?>
                                            <td colspan="4">
                                                <select {{ $disabled }} name="sale_update_id"
                                                    id="sale_update_id" class="form-select py-0"
                                                    aria-label="Default select example" required>
                                                    <option selected value="null">select sale item</option>
                                                    @foreach ($sales as $sale)
                                                        <?php $selected = $sale->id == old('sale_update_id') ? 'selected' : ''; ?>
                                                        <option value="{{ $sale->id }}" {{ $selected }}>
                                                            {{ $sale->created_at->format('H:i:s') . ' ' . $sale->menu->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <x-input-error :messages="$errors->get('sale_update_id')" class="mt-2" />
                                            </td>
                                            <td colspan="2">
                                                <input {{ $disabled }} type="number"
                                                    class="form-control py-0 d-inline-block" name="sale_update_amount"
                                                    value="{{ old('sale_update_amount') }}" placeholder="amount">
                                                <x-input-error :messages="$errors->get('sale_update_amount')" class="mt-2" />
                                            </td>
                                            <td colspan="2">
                                                <input {{ $disabled }} type="number"
                                                    class="form-control py-0 d-inline-block"
                                                    name="sale_update_discount"
                                                    value="{{ old('sale_update_discount') }}"
                                                    placeholder="discount ">
                                                <x-input-error :messages="$errors->get('sale_update_discount')" class="mt-2" />
                                            </td>
                                            <td>
                                                <input {{ $disabled }} type="text"
                                                    class="form-control py-0 d-inline-block"
                                                    value="{{ old('sale_update_customer') }}"
                                                    name="sale_update_customer" placeholder="customer">
                                                <x-input-error :messages="$errors->get('sale_update_customer')" class="mt-2" />
                                            </td>
                                        </tr>
                                    </form>
                                @endif
                                <?php $i = $sales->perPage() * ($sales->currentPage() - 1) + 1; ?>
                                @foreach ($sales as $sale)
                                    <tr>
                                        <th scope="row">{{ $i }}</th>
                                        <td>{{ $sale->created_at->format('H:i:s') }}</td>
                                        <td>{{ $sale->cashier->name }}</td>
                                        <td>{{ $sale->menu->name }}</td>
                                        <td>{{ Number::currency($sale->menu->price, in: 'IDR') }}</td>
                                        <td class="text-center">{{ $sale->amount . ' pcs' }}</td>
                                        <td>{{ Number::currency($sale->discount, in: 'IDR') }}</td>
                                        <td>{{ Number::currency($sale->transaction, in: 'IDR') }}</td>
                                        <td>{{ $sale->customer }}</td>
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach
                                {{ $sales->links() }}
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        {{-- Menu Tab --}}
        <div id="content_3" class="row pt-0 ">
            <div class="col-lg-12 mb-4 px-2">
                <div class="bg-white shadow mx-0 p-3 sm:p-6 rounded-lg">
                    {{-- Menu Items --}}
                    <div class="h5 fw-bold d-inline-block">{{ __('Stand Menu Item') }}</div>
                    @if ($stand_exist)
                        <div class="float-end">
                            @if ($stand->sale_validation == 0)
                                @if ($stand->menu_lock == 0)
                                    {{-- Person In Charge Feature --}}
                                    @if (Auth::user()->id == $stand->pic_id)
                                        <!-- Add Menu Button Trigger Modal -->
                                        <button class="btn text-dark pt-0 pe-2" data-bs-toggle="modal"
                                            data-bs-target="#addMenuItemModal">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-plus d-inline text-primary"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                            </svg>
                                            Add Menu Item
                                        </button>

                                        <!-- Add Menu Item Modal -->
                                        <div class="modal fade" id="addMenuItemModal" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-top border-warning">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Add
                                                            {{ $stand->name }}
                                                            Menu</h1>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form method="post"
                                                        action="{{ route('stand.menu.add', $stand->id) }}">
                                                        @csrf
                                                        @method('put')
                                                        <div class="modal-body">
                                                            <div class="text-center mb-2"><span
                                                                    class="ms-3 border-bottom pb-1 d-block"
                                                                    style="font-size: 12px">
                                                                    <span class="text-danger ">*</span>
                                                                    Don't use
                                                                    comma
                                                                    (,) or dot (.). Write the numbers only.</span></div>
                                                            <div class="row g-3 align-items-center">
                                                                <div class="col-5">
                                                                    <label for="menu_name"
                                                                        class="form-label d-inline-block float-end">
                                                                        Name</label>
                                                                </div>
                                                                <div class="col-6 ms-2">
                                                                    <input type="text"
                                                                        class="form-control d-inline-block"
                                                                        name="menu_name" id="menu_name"
                                                                        value="{{ old('menu_name') }}">
                                                                    <x-input-error :messages="$errors->get('menu_name')" class="mt-2" />
                                                                </div>
                                                            </div>
                                                            <div class="row g-3 align-items-center mt-1">
                                                                <div class="col-5">
                                                                    <label for="menu_price"
                                                                        class="form-label d-inline-block float-end">Selling
                                                                        Price<span class="text-danger">*</span></label>
                                                                </div>
                                                                <div class="col-6 ms-2">
                                                                    <input type="number"
                                                                        class="form-control d-inline-block"
                                                                        name="menu_price" id="menu_price"
                                                                        value="{{ old('menu_price') }}">
                                                                    <x-input-error :messages="$errors->get('menu_price')" class="mt-2" />
                                                                </div>
                                                            </div>
                                                            <div class="row g-3 align-items-center mt-1">
                                                                <div class="col-5">
                                                                    <label for="menu_volume"
                                                                        class="form-label d-inline-block float-end">Volume
                                                                        <span
                                                                            class="text-light text-sm">(opt)</span></label>
                                                                </div>
                                                                <div class="col-6 ms-2">
                                                                    <input type="number"
                                                                        class="form-control d-inline-block"
                                                                        name="menu_volume" id="menu_volume"
                                                                        value="{{ old('menu_volume') }}">
                                                                    <x-input-error :messages="$errors->get('menu_volume')" class="mt-2" />
                                                                </div>
                                                            </div>
                                                            <div class="row g-3 align-items-center mt-1">
                                                                <div class="col-5">
                                                                    <label for="menu_volume_unit"
                                                                        class="form-label d-inline-block float-end">Volume
                                                                        Unit
                                                                        <span
                                                                            class="text-light text-sm">(opt)</span></label>
                                                                </div>
                                                                <div class="col-6 ms-2">
                                                                    <input type="text"
                                                                        class="form-control d-inline-block"
                                                                        name="menu_volume_unit" id="menu_volume_unit"
                                                                        value="{{ old('menu_volume_unit') }}"
                                                                        placeholder="pcs, person, box, etc..">
                                                                    <x-input-error :messages="$errors->get('menu_volume_unit')" class="mt-2" />
                                                                </div>
                                                            </div>
                                                            <div class="row g-3 align-items-center mt-1">
                                                                <div class="col-5">
                                                                    <label for="menu_mass"
                                                                        class="form-label d-inline-block float-end">Mass
                                                                        <span
                                                                            class="text-light text-sm">(opt)</span></label>
                                                                </div>
                                                                <div class="col-6 ms-2">
                                                                    <input type="number"
                                                                        class="form-control d-inline-block"
                                                                        name="menu_mass" id="menu_mass"
                                                                        value="{{ old('menu_mass') }}">
                                                                    <x-input-error :messages="$errors->get('menu_mass')" class="mt-2" />
                                                                </div>
                                                            </div>
                                                            <div class="row g-3 align-items-center mt-1">
                                                                <div class="col-5">
                                                                    <label for="menu_mass_unit"
                                                                        class="form-label d-inline-block float-end">Mass
                                                                        Unit
                                                                        <span
                                                                            class="text-light text-sm">(opt)</span></label>
                                                                </div>
                                                                <div class="col-6 ms-2">
                                                                    <input type="text"
                                                                        class="form-control d-inline-block"
                                                                        name="menu_mass_unit" id="menu_mass_unit"
                                                                        value="{{ old('menu_mass_unit') }}"
                                                                        placeholder="pcs, person, box, etc..">
                                                                    <x-input-error :messages="$errors->get('menu_mass_unit')" class="mt-2" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit"
                                                                class="btn shadow border-primary">Add</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    {{-- Operational Feature --}}
                                    @if (Auth::user()->roles_id == 3 && count($menu_items) > 0)
                                        <form method="post" class="d-inline ms-0"
                                            action="{{ route('stand.menu.lock') }}">
                                            @csrf
                                            @method('put')
                                            <input type="hidden" name="stand_id" value="{{ $stand->id }}">
                                            <button type="submit"
                                                class="border-start border-success bg-success-subtle text-success ps-2 pe-2 "
                                                value="{{ Auth::user()->id }}" name="operational_id"
                                                data-bs-dismiss="modal">
                                                LOCK
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <span
                                        class="text-secondary fst-italic d-inline-block">{{ 'Menu has been locked by ' . $stand->pic->name }}</span>
                                    {{-- Operational Feature --}}
                                    @if (Auth::user()->roles_id == 3)
                                        <button type="submit"
                                            class="d-inline-block bg-danger-subtle  border-start border-danger text-danger ps-2 pe-2 ms-2"
                                            value="0" name="operational_id" form="formValidationBudget"
                                            data-bs-dismiss="modal">
                                            UNLOCK
                                        </button>
                                        <form method="post" id="formValidationBudget"
                                            action="{{ route('stand.menu.lock') }}">
                                            @csrf
                                            @method('put')
                                            <input type="hidden" name="stand_id" value="{{ $stand->id }}">
                                        </form>
                                    @endif
                                @endif
                            @else
                                <span
                                    class="text-secondary fst-italic">{{ 'Sale is validated, no changes permitted.' }}</span>
                            @endif
                        </div>
                        {{-- Menu Item Table --}}
                        <table class="table">
                            <thead class="table-primary">
                                <th scope="col">#</th>
                                <th scope="col" class="text-center">Name</th>
                                <th scope="col" class="text-center">Price</th>
                                <th scope="col" colspan="2" class="text-center">Volume</th>
                                <th scope="col" colspan="2" class="text-center">Mass</th>
                                <th scope="col" class="text-center">Sale</th>
                            </thead>
                            <tbody class="table-group-divider">
                                @if (Auth::user()->id == $stand->pic_id && $stand->sale_validation == 0)
                                    {{-- Action form --}}
                                    <form method="post" id="formUpdateMenuItem"
                                        action="{{ route('stand.menu.update') }}">
                                        @csrf
                                        @method('put')
                                        <tr>
                                            <td colspan="5"><span class="fst-italic" style="font-size: 12px">This
                                                    function only accessible
                                                    by PIC only.</span></td>
                                            <td colspan="3" class="text-end"> Action:
                                                {{-- action button --}}
                                                @if (count($menu_items) > 0 && $stand->menu_lock == null)
                                                    <button type="submit" form="formUpdateMenuItem"
                                                        class="px-1 d-inline" value="update" name="action">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor"
                                                            class="bi bi-pen-fill text-primary" viewBox="0 0 16 16">
                                                            <path
                                                                d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001" />
                                                        </svg>
                                                    </button>

                                                    <button type="submit" form="formUpdateMenuItem"
                                                        class="px-1 d-inline" value="delete" name="action"
                                                        onclick="return confirm('Confirm to delete this item?')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor"
                                                            class="bi bi-trash3 text-danger" viewBox="0 0 16 16">
                                                            <path
                                                                d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                                        </svg>
                                                    </button>
                                                @else
                                                    <a href="{{ route('stand.token', $stand->id) }}"
                                                        class="btn btn-light text-dark py-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            height="20" fill="currentColor"
                                                            class="bi bi-key-fill text-primary float-start mb-0 mt-1"
                                                            viewBox="0 0 20 20">
                                                            <path
                                                                d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2M2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
                                                        </svg>
                                                        Generate Token
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <?php $disabled = count($menu_items) > 0 && $stand->menu_lock == null ? '' : 'disabled'; ?>
                                            <td colspan="2">
                                                <select {{ $disabled }} name="menu_update_id" id="menu_update_"
                                                    class="form-select py-0" aria-label="Default select example"
                                                    required>
                                                    <option selected value="null">select menu</option>
                                                    @foreach ($menu_items as $menu_item)
                                                        <?php $selected = $menu_item->id == old('menu_update_id') ? 'selected' : ''; ?>
                                                        <option value="{{ $menu_item->id }}" {{ $selected }}>
                                                            {{ $menu_item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <x-input-error :messages="$errors->get('menu_update_id')" class="mt-2" />
                                            </td>
                                            <td>
                                                <input {{ $disabled }} type="number"
                                                    class="form-control py-0 d-inline-block" style="max-width: 110px;"
                                                    value="{{ old('menu_update_price') }}" name="menu_update_price"
                                                    placeholder="price">
                                                <x-input-error :messages="$errors->get('menu_update_price')" class="mt-2" />
                                            </td>
                                            <td>
                                                <input {{ $disabled }} type="number"
                                                    class="form-control py-0 d-inline-block" name="menu_update_volume"
                                                    value="{{ old('menu_update_volume') }}" placeholder="volume">
                                                <x-input-error :messages="$errors->get('menu_update_volume')" class="mt-2" />
                                            </td>
                                            <td>
                                                <input {{ $disabled }} type="text"
                                                    class="form-control py-0 d-inline-block"
                                                    name="menu_update_volume_unit"
                                                    value="{{ old('menu_update_volume_unit') }}"
                                                    placeholder="vol unit">
                                                <x-input-error :messages="$errors->get('menu_update_volume_unit')" class="mt-2" />
                                            </td>
                                            <td>
                                                <input {{ $disabled }} type="number"
                                                    class="form-control py-0 d-inline-block" name="menu_update_mass"
                                                    value="{{ old('menu_update_mass') }}" placeholder="mass">
                                                <x-input-error :messages="$errors->get('menu_update_mass')" class="mt-2" />
                                            </td>
                                            <td>
                                                <input {{ $disabled }} type="text"
                                                    class="form-control py-0 d-inline-block"
                                                    name="menu_update_mass_unit"
                                                    value="{{ old('menu_update_mass_unit') }}"
                                                    placeholder="mass unit">
                                                <x-input-error :messages="$errors->get('menu_update_mass_unit')" class="mt-2" />
                                            </td>
                                            <td></td>
                                        </tr>
                                    </form>
                                @endif
                                <?php
                                $i = $menu_items->perPage() * ($menu_items->currentPage() - 1) + 1;
                                $highest_sale = $menu_items->sortBy([['sale', 'desc']])->first();
                                $highest_id = $highest_sale !== null ? $highest_sale->id : 0;
                                // dd($highest_id);
                                ?>
                                @foreach ($menu_items as $menu_item)
                                    <tr>
                                        <th scope="row">{{ $i }}</th>
                                        <td>{{ $menu_item->name }}</td>
                                        <td>{{ Number::currency($menu_item->price, in: 'IDR') }}</td>
                                        <?php $volume = $menu_item->volume !== null ? $menu_item->volume . ' (' . $menu_item->volume_unit . ')' : '-'; ?>
                                        <td colspan="2" class="text-center">{{ $volume }}</td>
                                        <?php $mass = $menu_item->mass !== null ? $menu_item->mass . ' (' . $menu_item->mass_unit . ')' : '-'; ?>
                                        <td colspan="2" class="text-center">{{ $mass }}</td>
                                        <?php $sale = $menu_item->sale > 0 ? $menu_item->sale . ' (pcs)' : '-'; ?>
                                        <td class="text-center" style="min-width: 100px">{{ $sale }}
                                            @if ($highest_id == $menu_item->id)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor"
                                                    class="bi bi-hand-thumbs-up d-inline text-success"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M8.864.046C7.908-.193 7.02.53 6.956 1.466c-.072 1.051-.23 2.016-.428 2.59-.125.36-.479 1.013-1.04 1.639-.557.623-1.282 1.178-2.131 1.41C2.685 7.288 2 7.87 2 8.72v4.001c0 .845.682 1.464 1.448 1.545 1.07.114 1.564.415 2.068.723l.048.03c.272.165.578.348.97.484.397.136.861.217 1.466.217h3.5c.937 0 1.599-.477 1.934-1.064a1.86 1.86 0 0 0 .254-.912c0-.152-.023-.312-.077-.464.201-.263.38-.578.488-.901.11-.33.172-.762.004-1.149.069-.13.12-.269.159-.403.077-.27.113-.568.113-.857 0-.288-.036-.585-.113-.856a2 2 0 0 0-.138-.362 1.9 1.9 0 0 0 .234-1.734c-.206-.592-.682-1.1-1.2-1.272-.847-.282-1.803-.276-2.516-.211a10 10 0 0 0-.443.05 9.4 9.4 0 0 0-.062-4.509A1.38 1.38 0 0 0 9.125.111zM11.5 14.721H8c-.51 0-.863-.069-1.14-.164-.281-.097-.506-.228-.776-.393l-.04-.024c-.555-.339-1.198-.731-2.49-.868-.333-.036-.554-.29-.554-.55V8.72c0-.254.226-.543.62-.65 1.095-.3 1.977-.996 2.614-1.708.635-.71 1.064-1.475 1.238-1.978.243-.7.407-1.768.482-2.85.025-.362.36-.594.667-.518l.262.066c.16.04.258.143.288.255a8.34 8.34 0 0 1-.145 4.725.5.5 0 0 0 .595.644l.003-.001.014-.003.058-.014a9 9 0 0 1 1.036-.157c.663-.06 1.457-.054 2.11.164.175.058.45.3.57.65.107.308.087.67-.266 1.022l-.353.353.353.354c.043.043.105.141.154.315.048.167.075.37.075.581 0 .212-.027.414-.075.582-.05.174-.111.272-.154.315l-.353.353.353.354c.047.047.109.177.005.488a2.2 2.2 0 0 1-.505.805l-.353.353.353.354c.006.005.041.05.041.17a.9.9 0 0 1-.121.416c-.165.288-.503.56-1.066.56z" />
                                                </svg>
                                            @endif
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach
                                {{ $menu_items->links() }}
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        var default_tab = sessionStorage.getItem('default_tab');
        window.onload = function() {
            if (default_tab !== null) {
                show_tab(default_tab);
            } else {
                show_tab(1);
            }
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

            sessionStorage.setItem('default_tab', target);
        }

        let receiptImage = document.getElementById('receiptImage');
        let downloadReceipt = document.getElementById('downloadReceipt');
        let receiptName = document.getElementById('receiptTitle');
        let validationReceipt = document.getElementById('validationReceipt');
        let receiptFooter = document.getElementById('receiptFooter');
        let receiptId = document.getElementById('receiptId');
        let receiptButton = document.getElementById('receiptButton');

        function setReceipt(id, receipt, role, operational_name, operational_id) {
            receiptImage.setAttribute('src', '/storage/images/receipt/stand/expense/' + receipt);
            downloadReceipt.setAttribute('href', '/storage/images/receipt/stand/expense/' + receipt);
            if (role == 3) {
                receiptId.setAttribute('value', id)
            };
            receiptName.innerText = receipt;
            // check for validated item
            if (operational_name != 'none') {
                validationReceipt.innerText = 'Validated by ' + operational_name;
                if (role == 3) {
                    receiptButton.innerText = 'UNVALIDATE';
                    receiptButton.setAttribute('value', '');
                    receiptButton.setAttribute('class', 'btn text-danger shadow');
                    receiptButton.setAttribute('onclick', 'return confirm(\'Confirm to UNVALIDATE this receipt?\')');
                }
            } else {
                validationReceipt.innerText = 'Unvalidated';
                if (role == 3) {
                    receiptButton.innerText = 'VALIDATE';
                    receiptButton.setAttribute('value', operational_id);
                    receiptButton.setAttribute('class', 'btn text-success shadow');
                    receiptButton.setAttribute('onclick', 'return confirm(\'Confirm to VALIDATE this receipt?\')');
                }
            }

        }

        let receiptUpload = document.getElementById('receipt_upload');
        let receiptSame = document.getElementById('receipt_same');
        let sameReceiptCheck = document.getElementById('same_receipt_check');

        function sameReceipt() {
            if (sameReceiptCheck.checked == true) {
                receiptUpload.setAttribute('hidden', '');
                receiptSame.removeAttribute('hidden');
            } else {
                receiptUpload.removeAttribute('hidden');
                receiptSame.setAttribute('hidden', '');
            }
        }
    </script>
</x-app-layout>
