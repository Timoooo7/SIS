<x-app-layout>
    <x-slot name="header">
        <nav aria-label="breadcrumb w-auto b-0" style="--bs-breadcrumb-divider: '>';">
            <ol class="breadcrumb">
                <li class="breadcrumb-item " aria-current="page"><span
                        class="text-decoration-none text-black">{{ 'Foods' }}</span></li>
                <li class="breadcrumb-item font-semibold text-xl text-gray-800 leading-tight" aria-current="page">
                    {{ __('Point of Sales') }}
                </li>
            </ol>
        </nav>
    </x-slot>
    <div class="container py-3 px-10">
        <div class="row mb-3">
            <div class="col-lg-5" id="pos_column">
                <div class="bg-white shadow p-3 pb-0 rounded-lg mb-4">
                    <?php $route = $stand !== null ? route('stand.sale.add', ['stand_id' => $stand->id]) : ''; ?>
                    <form method="post" action="{{ $route }}"
                        onsubmit="return confirm('Are you sure all data is correct?')">
                        @csrf
                        @method('put')
                        <?php
                        if ($stand !== null) {
                            $disabled = $stand->menu_lock != 0 && $stand->sale_validation == 0 ? '' : 'disabled';
                        } else {
                            $disabled = 'disabled';
                        }
                        ?>
                        <h2 class="text-dark text-center border-primary border-bottom shadow py-1 rounded-lg">
                            {{ 'Point of Sales' }}</h2>
                        <div class="text-center mt-sm-4 mt-3 mb-2">
                            @if ($stand !== null)
                                <span class="ms-3 d-block" style="font-size: 12px">
                                    <span class="text-danger ">*</span>
                                    Don't use comma (,) or dot (.). Write the numbers only.
                                </span>
                            @else
                                <h3 class="text-danger bg-secondary-subtle">No Active Stand Found</h3>
                            @endif
                        </div>
                        <div class="row g-3 mt-sm-1 mb-2 align-items-center">
                            <div class="col-sm-4 col-5">
                                <label for="stand_id"
                                    class="form-label mb-0 d-inline-block float-end text-secondary fw-light">Stand :
                                </label>
                            </div>
                            <div class="col-7 ms-sm-2">
                                <?php $stand_name = $stand !== null ? $stand->name : '-'; ?>
                                <span class="mt-0">{{ $stand_name }}</span>
                            </div>
                        </div>
                        <div class="row g-3 mt-sm-1 mb-2 align-items-center">
                            <div class="col-sm-4 col-5">
                                <label class="form-label text-secondary mb-0 d-inline-block float-end">Cashier :</label>
                            </div>
                            <div class="col-7 ms-sm-2">
                                <input type="hidden" hidden class="form-control d-inline-block" name="cashier_id"
                                    {{ $disabled }} id="cashier_id" required value="{{ Auth::user()->id }}">
                                <span class="mt-0">{{ Auth::user()->name }}</span>
                            </div>
                        </div>
                        <div class="row g-3 align-items-center mt-sm-1 mb-2">
                            <div class="col-sm-4 col-5">
                                <label for="token" class="form-label text-secondary d-inline-block float-end">Cashier
                                    Token<span class="text-danger">*</span> :</label>
                            </div>
                            <div class="col-7 ms-sm-2">
                                <input type="number" class="form-control d-inline-block" name="cashier_token"
                                    placeholder="Ask Stand PIC for cashier token" {{ $disabled }} id="cashier_token"
                                    required value="{{ old('cashier_token') }}">
                                <x-input-error :messages="$errors->get('cashier_token')" class="mt-2" />
                            </div>
                        </div>
                        <div class="row g-3 align-items-center mt-sm-1 mb-2">
                            <div class="col-sm-4 col-5">
                                <label for="sale_menu_id"
                                    class="form-label text-secondary d-inline-block float-end">Menu :</label>
                            </div>
                            <div class="col-7 ms-sm-2">
                                <select {{ $disabled }} name="sale_menu_id" id="sale_menu_id" class="form-select "
                                    aria-label="Default select example" required onchange="salePrice(this.value)">
                                    <option label="Choose Menu" selected disabled></option>
                                    @if ($stand !== null)
                                        @foreach ($menu_items as $menu_item)
                                            <?php $selected = $menu_item->id == old('sale_menu_id') ? 'selected' : ''; ?>
                                            <option value="{{ $menu_item->id }}" {{ $selected }}>
                                                {{ $menu_item->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <x-input-error :messages="$errors->get('sale_menu_id')" class="mt-2" />
                            </div>
                        </div>
                        <div class="row g-3 mt-sm-1 mb-2 align-items-center">
                            <div class="col-sm-4 col-5">
                                <label
                                    class="form-label text-secondary mb-0 d-inline-block float-end text-secondary fw-light">Price
                                    :
                                </label>
                            </div>
                            <div class="col-7 ms-sm-2">
                                <span class="fs-5 mt-0" id="sale_price">
                                    @if ($stand !== null)
                                        @foreach ($menu_items as $item)
                                            <span id="price_{{ $item->id }}" hidden>{{ $item->price }}</span>
                                        @endforeach
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="row g-3 align-items-center mt-sm-1 mb-2">
                            <div class="col-sm-4 col-5">
                                <label for="amount"
                                    class="form-label text-secondary d-inline-block float-end">Amount<span
                                        class="text-danger">*</span> :</label>
                            </div>
                            <div class="col-7 ms-sm-2">
                                <input type="number" class="form-control d-inline-block" name="sale_amount"
                                    {{ $disabled }} id="sale_amount" required value="{{ old('sale_amount') }}">
                                <x-input-error :messages="$errors->get('sale_amount')" class="mt-2" />
                            </div>
                        </div>
                        <div class="row g-3 align-items-center mt-sm-1 mb-2">
                            <div class="col-sm-4 col-5">
                                <label for="discount"
                                    class="form-label text-secondary d-inline-block float-end">Discount(IDR)<span
                                        class="text-danger">*</span> :</label>
                            </div>
                            <div class="col-7 ms-sm-2">
                                <input type="number" class="form-control d-inline-block" name="sale_discount"
                                    {{ $disabled }} id="sale_discount" value="{{ old('sale_discount') }}"
                                    placeholder="This is optional">
                                <x-input-error :messages="$errors->get('sale_discount')" class="mt-2" />
                            </div>
                        </div>
                        <div class="row g-3 mt-sm-1 mb-2 align-items-center">
                            <div class="col-sm-4 col-5">
                                <label
                                    class="form-label text-secondary mb-0 d-inline-block float-end text-secondary fw-light">Transaction
                                    :
                                </label>
                            </div>
                            <div class="col-6 ms-2">
                                <span class="fs-5 mt-0" id="sale_transaction"></span>
                            </div>
                        </div>
                        <div class="row g-3 align-items-center mt-sm-1 mb-2">
                            <div class="col-sm-4 col-5">
                                <label for="customer"
                                    class="form-label text-secondary d-inline-block float-end">Customer :</label>
                            </div>
                            <div class="col-7 ms-sm-2">
                                <input type="text" class="form-control d-inline-block" name="sale_customer"
                                    {{ $disabled }} id="sale_customer" value="{{ old('sale_customer') }}"
                                    placeholder="This is optional">
                                <x-input-error :messages="$errors->get('sale_customer')" class="mt-2" />
                            </div>
                        </div>
                        <div class="row py-1 mt-3">
                            <button type="submit" {{ $disabled }} class="btn btn-primary shadow-sm">Add
                                Transaction</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-7" id="menu_column">
                <div class="row">
                    <div class="col">
                        <div class="bg-white p-3 pb-3 mb-3 rounded-lg">
                            <h4 class="bg-warning-subtle text-dark fw-light p-2 mb-3 rounded-lg">
                                {{ $stand_name }} Menu
                            </h4>
                            @if ($stand !== null)
                                @foreach ($menu_items as $item)
                                    <p class="border-warning border-bottom text-dark pb-1 fs-6 mt-4 mx-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor"
                                            class="bi bi-caret-right-fill d-inline text-secondary mb-1"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z" />
                                        </svg>
                                        {{ $item->name }}
                                        @if ($item->volume !== null || $item->mass !== null)
                                            <span
                                                class="fw-light">({{ $item->volume . ' ' . $item->volume_unit . ' ' . $item->mass . ' ' . $item->mass_unit }})</span>
                                        @endif

                                        <span class="float-end text-primary-enhanced">
                                            {{ format_currency($item->price, 'IDR') }}
                                            <span
                                                class="border border-primary bg-white rounded-lg px-1 pt-1 pb-0 ms-2 shadow mt-0">
                                                {{ $item->sale . ' pcs' }}</span>
                                        </span>
                                    </p>
                                @endforeach
                            @else
                                <p class="border-warning border-bottom text-dark pb-1 fs-6 mt-4 mx-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor"
                                        class="bi bi-hand-thumbs-up-fill d-inline text-secondary mb-1 me-2"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M6.956 1.745C7.021.81 7.908.087 8.864.325l.261.066c.463.116.874.456 1.012.965.22.816.533 2.511.062 4.51a10 10 0 0 1 .443-.051c.713-.065 1.669-.072 2.516.21.518.173.994.681 1.2 1.273.184.532.16 1.162-.234 1.733q.086.18.138.363c.077.27.113.567.113.856s-.036.586-.113.856c-.039.135-.09.273-.16.404.169.387.107.819-.003 1.148a3.2 3.2 0 0 1-.488.901c.054.152.076.312.076.465 0 .305-.089.625-.253.912C13.1 15.522 12.437 16 11.5 16H8c-.605 0-1.07-.081-1.466-.218a4.8 4.8 0 0 1-.97-.484l-.048-.03c-.504-.307-.999-.609-2.068-.722C2.682 14.464 2 13.846 2 13V9c0-.85.685-1.432 1.357-1.615.849-.232 1.574-.787 2.132-1.41.56-.627.914-1.28 1.039-1.639.199-.575.356-1.539.428-2.59z" />
                                    </svg>
                                    {{ 'menu' }}
                                    <span class="fw-light">({{ 'details' }})</span>
                                    <span
                                        class="float-end text-primary-enhanced">{{ format_currency(0, 'IDR') }}</span>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var past_menu_id = 0;
        window.addEventListener("load", function() {
            salePrice({{ old('sale_menu_id') }});
        })

        function salePrice(menu_id) {
            if (past_menu_id != 0) {
                document.getElementById('price_' + past_menu_id).setAttribute('hidden', '');
            }
            document.getElementById('price_' + menu_id).removeAttribute('hidden');
            past_menu_id = menu_id;
        }

        let menu = document.getElementById('sale_menu_id');
        let amount = document.getElementById('sale_amount');
        let discount = document.getElementById('sale_discount');
        let transaction = document.getElementById('sale_transaction');

        menu.addEventListener("change", transactionPrice);
        amount.addEventListener("change", transactionPrice);
        discount.addEventListener("change", transactionPrice);

        function transactionPrice() {
            transaction.innerHTML = '';
            price = document.getElementById('price_' + past_menu_id).innerHTML;
            transaction.innerHTML = (Number(price) * amount.value) - Number(discount.value);
        }
    </script>
</x-app-layout>
