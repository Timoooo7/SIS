<?php
$auth_user = Auth::user();
?>
<x-app-layout>
    <x-slot name="header">
        {{ 'Goods Product' }}
    </x-slot>

    <div class="container pb-4">
        {{-- Product Filter --}}
        {{-- Filter --}}
        <div class="row justify-content-center">
            <div class="col-md-11 col-xl-10 col-12">
                <nav class="navbar bg-white p-2 mt-4 mx-2 rounded shadow">
                    <form method="post" id="searchProductForm" role="search" action="{{ route('good.product.filter') }}">
                        @csrf
                        @method('put')
                    </form>
                    <form method="post" id="formProductDate" action="{{ route('good.product.filter') }}">
                        @csrf
                        @method('put')
                        <?php
                        $is_product_date = $filter['product']['category'] == 'created_at';
                        $product_date_order = $filter['product']['order'] == 'desc' ? 'desc' : 'asc';
                        $product_date_icon = $is_product_date && $product_date_order == 'asc' ? 'up' : 'down';
                        $product_date_value = $is_product_date && $product_date_order == 'asc' ? 'desc' : 'asc';
                        $product_date_button = $is_product_date && $is_product_date ? '' : '';
                        ?>
                        <input type="hidden" name="category" value="created_at">
                    </form>
                    <div class="container d-block px-0 ">
                        <?php $keyword_focus = $filter['product']['keyword'] ? 'autofocus' : ''; ?>
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
                                    <button type="submit" form="formProductDate"
                                        class="btn btn-sm btn-outline-secondary border-0 {{ $product_date_button }} rounded-0 my-0"
                                        name="order" value="{{ $product_date_value }}">
                                        <span class="d-none d-lg-inline">{{ 'Date' }}</span>
                                        <i class="bi bi-calendar-range d-lg-none"></i>
                                        <i class="bi bi-arrow-{{ $product_date_icon }}"></i>
                                    </button>
                                    {{-- Search Filter --}}
                                    <input type="text" name="keyword" class="form-control" form="searchProductForm"
                                        {{ $keyword_focus }} placeholder="{{ 'Search name or category' }}"
                                        value="{{ $filter['product']['keyword'] }}">
                                    <button class="btn btn-outline-secondary border-0" type="submit"
                                        form="searchProductForm">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                                @if ($auth_user->roles_id == 3)
                                    <!-- Button trigger Add Product Modal -->
                                    <button
                                        class="btn btn-primary btn-sm bg-opacity-50 shadow-sm d-inline-block ms-2 fw-light"
                                        data-bs-toggle="modal" data-bs-target="#addProductModal">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                    <!-- Add Product Modal -->
                                    <div class="modal fade" id="addProductModal" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content shadow mx-3">
                                                <div class="modal-header py-1 ps-3 pe-2">
                                                    <span class="modal-title fs-5 text-primary-emphasis"
                                                        id="exampleModalLabel"><i
                                                            class="bi bi-bag-plus border-secondary border-end me-2 pe-2"></i>
                                                        {{ 'New Product' }}
                                                    </span>
                                                    <button type="button" class="btn btn-sm ms-auto"
                                                        data-bs-dismiss="modal" aria-label="Close"><i
                                                            class="bi bi-x-lg"></i></button>
                                                </div>
                                                <form method="post" id="formAddProduct"
                                                    action="{{ route('good.product.add') }}">
                                                    @csrf
                                                    @method('put')
                                                    <div class="modal-body bg-light">
                                                        <div class="row justify-content-center">
                                                            <div class="col-4 col-lg-3">
                                                                <label for="add_product_category"
                                                                    class="form-label d-inline-block">{{ 'Category' }}</label>
                                                            </div>
                                                            <div class="col-8 col-lg-7">
                                                                <input type="text"
                                                                    class="form-control form-control-sm d-inline-block"
                                                                    name="category" id="add_product_category" required>
                                                                <x-input-error :messages="$errors->get('category')" class="mt-2" />
                                                            </div>
                                                        </div>
                                                        <div class="row justify-content-center mt-3">
                                                            <div class="col-4 col-lg-3">
                                                                <label for="add_product_name"
                                                                    class="form-label d-inline-block">{{ 'Name' }}</label>
                                                            </div>
                                                            <div class="col-8 col-lg-7">
                                                                <input type="text"
                                                                    class="form-control form-control-sm d-inline-block"
                                                                    name="name" id="add_product_name" required>
                                                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                                            </div>
                                                        </div>
                                                        <div class="row justify-content-center mt-3">
                                                            <div class="col-4 col-lg-3">
                                                                <label for="add_product_pic"
                                                                    class="form-label d-inline-block">{{ 'PIC' }}</label>
                                                            </div>
                                                            <div class="col-8 col-lg-7">
                                                                <select name="pic" class="form-select d-inline"
                                                                    id="add_product_pic" required>
                                                                    <?php $i = 1; ?>
                                                                    @foreach ($user_list as $user)
                                                                        <?php $default = $i === 1 ? 'selected' : ''; ?>
                                                                        <option value="{{ $user->id }}"
                                                                            {{ $default }}>
                                                                            {{ $user->name }}
                                                                        </option>
                                                                        <?php $i++; ?>
                                                                    @endforeach
                                                                </select>
                                                                <x-input-error :messages="$errors->get('pic')" class="mt-2" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer p-1">
                                                        <button type="submit" form="formAddProduct"
                                                            class="btn btn-sm btn-primary ">{{ 'Add' }}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <a href="{{ route('good.insight') }}" class="text-decoration-none">
                                    <button class="btn btn-sm btn-secondary ms-2 d-flex">
                                        <i class="bi bi-bar-chart-line d-inline"></i>
                                        <span
                                            class="border-start ms-1 ps-1 d-none d-lg-inline">{{ 'Insight' }}</span>
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        {{-- Product List --}}
        <div class="row px-2">
            <div class="col-12">
                <div
                    class="scroll-container-lg-3 scroll-container-4 bg-primary bg-opacity-25 rounded-3 shadow-sm mt-3">
                    <div class="row gx-3 px-3 pb-3">
                        @foreach ($product_list as $product)
                            <div class="col-lg-3 col-xl-2 col-md-4 col-6 mt-3">
                                <a href="{{ route('good.product.detail', ['id' => $product->id]) }}"
                                    class="text-decoration-none">
                                    <div
                                        class="card shadow card-border-hover {{ $product->operational_id == 0 ? 'bg-secondary-subtle' : 'bg-pr' }}">
                                        <img src="/storage/images/product/{{ $product->image->count() > 0 ? $product->image->first()->image : 'example.png' }}"
                                            alt="image" class="rounded mx-2 mt-2"
                                            style="object-fit: contain; max-width: 100%;">
                                        <div class="row mt-3">
                                            <div class="col-12 ">
                                                <span
                                                    class="text-secondary text-opacity-50 fw-bold fs-6 mx-3">{{ $product->category }}</span>
                                            </div>
                                        </div>
                                        <div class="row mt-0 mb-2">
                                            <div class="col-12">
                                                <span
                                                    class="text-primary-emphasis text-opacity-100 fw-normal fs-6 mx-3 position-relative">
                                                    {{ substr($product->name, 0, 22) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- FAB --}}
    @if ($auth_user->roles_id == 3 || $auth_user->product)
        <button class="fab btn btn-primary p-2" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
            <i class="bi bi-cart4 fs-3"></i>
        </button>

        {{-- Off Canvas --}}
        <div class="offcanvas-height-sm-1 offcanvas-height-lg-0 offcanvas offcanvas-bottom " data-bs-scroll="true"
            tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel">
            {{-- Header --}}
            <div class="offcanvas-header px-3 py-1 border-top border-2 border-primary-subtle shadow-sm">
                <h5 class="offcanvas-title" id="cartOffcanvasLabel">
                    <i class="bi bi-cart4 border-end border-secondary-subtle pe-2 me-1"></i>
                    <span class="text-primary-emphasis my-auto">{{ 'Cart' }}</span>
                </h5>
                @if ($auth_user->product->count() > 0)
                    <button type="button" class="btn btn-sm btn-outline-primary border-0 ms-auto"
                        data-bs-target="#addCartModal" data-bs-toggle="modal"> <i class="bi bi-plus-lg"></i><span
                            class="ms-2 d-none d-lg-inline">{{ 'New Cart' }}</span></button>
                @endif
                <button type="button"
                    class="btn-close {{ $auth_user->product->count() > 0 ? 'ms-2' : 'ms-auto' }} border-start border-secondary rounded-0 ps-2"
                    data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            {{-- Body --}}
            <div class="offcanvas-body border-top border-light p-0">
                <div class="container px-4">
                    <div class="row">
                        {{-- Cart List --}}
                        <div class="col-12 col-lg-3 col-xl-2 mt-2">
                            <h6 class="border-bottom border-primary-subtle mb-1 pb-1 fw-light">
                                {{ 'Cart List' }}
                            </h6>
                            <div class="scroll-container-horizontal scroll-container-lg-0 bg-light pt-2 px-3 rounded">
                                <?php $cart_index = 1; ?>
                                @foreach ($cart_list as $cart)
                                    <div id="cart_{{ $cart_index }}"
                                        onclick="setCart({{ $cart }}, {{ $cart_index }})"
                                        class="card card-bg-hover mb-2 px-2 d-inline-block d-lg-block ms-lg-0 {{ $cart_index == 1 ? 'shadow border-primary border-1 ms-0' : 'shadow-sm text-secondary ms-2' }}">
                                        <div class="row">
                                            <div class="col-12 d-flex">
                                                <span
                                                    class="fw-light border-end border-secondary-subtle pe-2 me-2">{{ $cart_index }}</span>
                                                <span>{{ $cart->customer }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $cart_index++; ?>
                                @endforeach
                                @if ($cart_list->count() <= 0)
                                    <div class="card bg-light mb-2 px-2">
                                        <span class="fst-italic fw-light">{{ 'Please make a cart.' }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        {{-- Cart detail --}}
                        <div class="col-12 col-lg-8 col-xl-9 mt-2">
                            <div class="row">
                                <div class="col-12 d-flex border-bottom border-light">
                                    <span class="fw-light me-2">{{ 'Date :' }}</span>
                                    <span class="text-dark me-4">{{ format_date_time(now()) }}</span>
                                    <span class="fw-light me-2">{{ 'Customer :' }}</span>
                                    <span id="cart_customer"
                                        class="text-primary-emphasis">{{ $cart_list->count() > 0 ? $cart_list->first()->customer : '' }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <form method="post" id="addTransactionForm"
                                    action="{{ route('good.transaction.add', ['id' => $cart_list->count() > 0 ? $cart_list->first()->id : 0]) }}">
                                    @csrf
                                    @method('put')
                                </form>
                                {{-- Order List --}}
                                <div class="col-12 col-lg-8">
                                    <div class="scroll-container-lg-0 scroll-container pt-2"
                                        id="scroll_container_cart">
                                        <?php $transaction = 0; ?>
                                        @if ($cart_list->count() > 0)
                                            @if ($cart_list->first()->order->count() > 0)
                                                <?php $i = 0; ?>
                                                @foreach ($cart_list->first()->order as $order)
                                                    <div class="row mb-2">
                                                        <?php
                                                        $variant = $order->variant;
                                                        $product = $variant->product;
                                                        $transaction += $order->amount * $variant->price;
                                                        ?>
                                                        <div class="col-12 d-flex">
                                                            <button class="btn btn-sm btn-light"
                                                                onclick="update_amount({{ $order->id }},false , {{ $variant->price }})"><i
                                                                    class="bi bi-dash"></i></button>
                                                            <span class="mx-2 my-auto"
                                                                id="{{ 'order_amount_' . $order->id }}">{{ $order->amount }}</span>
                                                            <input form="addTransactionForm" type="hidden"
                                                                name="order[{{ $order->id }}]"
                                                                value="{{ $order->amount }}"
                                                                id="input_order_amount_{{ $order->id }}">
                                                            <button class="btn btn-sm btn-light"
                                                                onclick="update_amount({{ $order->id }},true , {{ $variant->price }})"><i
                                                                    class="bi bi-plus"></i></button>
                                                            <div
                                                                class="d-inline-block scroll-x-hidden my-auto text-nowrap ms-2 me-3">
                                                                <span
                                                                    class="text-primary-emphasis my-auto">{{ $product->name }}</span>
                                                                <span
                                                                    class="text-body-tertiary fw-bold ms-2 my-auto">{{ $variant->name }}</span>
                                                                <span
                                                                    class="ms-1 my-auto">{{ '- ' . format_currency($variant->price) }}</span>
                                                            </div>
                                                            <span id="order_total_{{ $order->id }}"
                                                                class="ms-auto me-2 my-auto">{{ format_currency($variant->price * $order->amount) }}</span>
                                                        </div>
                                                    </div>
                                                    <?php $i++; ?>
                                                @endforeach
                                            @else
                                                <div class="card bg-light mb-2 px-2">
                                                    <span
                                                        class="fst-italic fw-light">{{ 'Please make an order from product detail page.' }}</span>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                {{-- Transaction Detail --}}
                                <div class="col-12 col-lg-4">
                                    <div class="row mt-2 px-lg-0 px-2">
                                        <div class="col d-flex fs-5 px-0">
                                            <span class="fw-light ms-auto ">{{ 'Total : ' }} </span>
                                            <span class="text-white bg-primary ms-2 px-3 rounded "
                                                id="sales_transaction">{{ format_currency($transaction) }}</span>
                                            <input form="addTransactionForm" type="hidden" name="transaction"
                                                id="input_sales_transaction" value="{{ $transaction }}">
                                        </div>
                                    </div>
                                    <div class="row mt-2 px-lg-0 px-2">
                                        <div class="col ps-0 pe-1">
                                            <div id="add_transaction_button_container"
                                                onclick="{{ $cart_list->count() <= 0 ? "notif('No cart to make transaction.')" : ($transaction == 0 ? "notif('Total transaction is 0. Add some order to create a transaction.')" : '') }}">
                                                <button id="add_transaction_button"
                                                    class="btn btn-outline-primary d-flex w-100 text-center {{ $transaction == 0 ? 'disabled' : '' }}"
                                                    onclick="confirmSubmission('addTransactionForm','Are you sure want to add this transaction?')";>
                                                    <i class="bi bi-journal-plus me-2 pe-2 border-end ms-auto"></i>
                                                    <span class="fw-light me-auto">{{ 'Add Transaction' }}</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-auto ps-1 pe-0">
                                            <div id="add_transaction_button_container"
                                                onclick="{{ $cart_list->count() <= 0 ? "notif('No cart to be cancel.')" : '' }}">
                                                <button id="cancel_transaction_button"
                                                    onclick="{{ $cart_list->count() > 0 ? 'confirmation("' . route('good.transaction.cancel', ['id' => $cart_list->first()->id]) . '", "Cancel ' . $cart_list->first()->customer . ' transaction?")' : '' }}"
                                                    class="btn btn-outline-secondary d-flex w-auto text-center {{ $cart_list->count() <= 0 ? 'disabled' : '' }}">
                                                    <i class="bi bi-trash3 me-2 pe-2 border-end ms-auto"></i>
                                                    <span class="fw-light me-auto">{{ 'Cancel' }}</span>
                                                </button>
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

        @if ($auth_user->product->count() > 0)
            {{-- Add Cart Modal --}}
            <div class="modal fade" id="addCartModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content shadow mx-3 mt-5">
                        <div class="modal-header py-1 ps-3 pe-2">
                            <span class="modal-title fs-5 text-primary-emphasis">
                                <i class="bi bi-cart4 border-secondary border-end me-2 pe-2"></i>
                                {{ 'Add Cart Order' }}
                            </span>
                            <button type="button" class="btn btn-sm ms-auto" data-bs-dismiss="modal"
                                aria-label="Close"><i class="bi bi-x-lg"></i></button>
                        </div>
                        <form method="post" action="{{ route('good.cart.add') }}">
                            @csrf
                            @method('put')
                            <div class="modal-body bg-light">
                                <div class="row justify-content-center">
                                    <div class="col-4">
                                        <label for="add_cart_customer"
                                            class="form-label d-inline-block">Customer</label>
                                    </div>
                                    <div class="col-8">
                                        <input type="text" class="form-control form-control-sm d-inline-block"
                                            name="customer" id="add_cart_customer" value="{{ old('customer') }}"
                                            required>
                                        <x-input-error :messages="$errors->get('customer')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer p-1">
                                <button type="submit" class="btn btn-sm btn-primary ">{{ 'Add' }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

    @endif

    <script>
        const auth_user = <?php echo $auth_user; ?>;
        var order_list = <?php echo $cart_list->count() > 0 ? $cart_list->first()->order : 'null'; ?>;
        var transaction = null;
        var active_cart_index = <?php echo $cart_list->count() > 0 ? 1 : 0; ?>;
        const add_transaction_route = "<?php echo route('good.transaction.add'); ?>";
        const cancel_transaction_route = "<?php echo route('good.transaction.cancel'); ?>";
        const transaction_span = document.getElementById('sales_transaction');
        const transaction_input = document.getElementById('input_sales_transaction');
        const transaction_form = document.getElementById('addTransactionForm');


        function setCart(cart, index) {
            if (index !== active_cart_index) {
                order_list = cart.order;
                const cart_container = document.getElementById('scroll_container_cart');
                cart_container.innerHTML = '';
                transaction = 0;

                // Set order list
                for (let i = 0; i < order_list.length; i++) {
                    const order = order_list[i];
                    const variant = order.variant;

                    const row = document.createElement('div');
                    row.setAttribute('class', 'row mb-2');

                    const col = document.createElement('div');
                    col.setAttribute('class', 'col-12 d-flex');
                    row.appendChild(col);

                    const btn_min = document.createElement('button');
                    btn_min.setAttribute('class', 'btn btn-sm btn-light');
                    col.appendChild(btn_min);
                    btn_min.onclick = function() {
                        update_amount(order.id, false, variant.price);
                    };

                    const icon_dash = document.createElement('i');
                    icon_dash.setAttribute('class', 'bi bi-dash');
                    btn_min.appendChild(icon_dash);

                    const span_amount = document.createElement('span');
                    span_amount.setAttribute('class', 'mx-2 my-auto');
                    span_amount.id = 'order_amount_' + order.id;
                    span_amount.innerHTML = order.amount;
                    col.appendChild(span_amount);

                    const input_amount = document.createElement('input');
                    input_amount.type = 'hidden';
                    input_amount.name = 'order[' + order.id + ']';
                    input_amount.value = order.amount;
                    input_amount.id = 'input_order_amount_' + order.id;
                    input_amount.setAttribute('form', 'addTransactionForm');
                    col.appendChild(input_amount);

                    const btn_plus = document.createElement('button');
                    btn_plus.setAttribute('class', 'btn btn-sm btn-light');
                    col.appendChild(btn_plus);
                    btn_plus.onclick = function() {
                        update_amount(order.id, true, variant.price);
                    };

                    const icon_plus = document.createElement('i');
                    icon_plus.setAttribute('class', 'bi bi-plus');
                    btn_plus.appendChild(icon_plus);

                    const div = document.createElement('div');
                    div.setAttribute('class', 'd-inline-block scroll-x-hidden my-auto text-nowrap ms-2 me-3');
                    col.appendChild(div);

                    const span_product = document.createElement('span');
                    span_product.setAttribute('class', 'text-primary-emphasis my-auto');
                    span_product.innerHTML = variant.product.name;
                    div.appendChild(span_product);

                    const span_variant = document.createElement('span');
                    span_variant.setAttribute('class', 'text-body-tertiary fw-bold ms-2 my-auto');
                    span_variant.innerHTML = variant.name;
                    div.appendChild(span_variant);

                    const span_price = document.createElement('span');
                    span_price.setAttribute('class', 'ms-1 my-auto');
                    span_price.innerHTML = '- ' + formatRupiah(variant.price);
                    div.appendChild(span_price);

                    const span_total = document.createElement('span');
                    span_total.setAttribute('class', 'ms-auto me-2 my-auto');
                    span_total.id = 'order_total_' + order.id;
                    new_transaction = order.amount * variant.price;
                    transaction += new_transaction;
                    span_total.innerHTML = formatRupiah(new_transaction);
                    col.appendChild(span_total);

                    // Put everything to cart container
                    cart_container.appendChild(row);
                }

                if (order_list.length <= 0) {
                    const card = document.createElement('div');
                    card.setAttribute('class', 'card bg-light mb-2 px-2');

                    const span_zero = document.createElement('span');
                    span_zero.setAttribute('class', 'fst-italic fw-light');
                    span_zero.innerHTML = 'Please make an order from product detail page.';

                    card.appendChild(span_zero);
                    cart_container.appendChild(card);
                }

                // Set transaction detail
                const customer = document.getElementById('cart_customer');
                const button_cancel = document.getElementById('cancel_transaction_button');

                customer.innerHTML = cart.customer;
                transaction_span.innerHTML = formatRupiah(transaction);
                transaction_input.value = transaction;
                transaction_form.action = add_transaction_route + '/' + cart.id;
                update_transaction(0);
                button_cancel.onclick = function() {
                    confirmation(cancel_transaction_route + '/' + cart.id, 'Cancel ' + cart.customer + ' transaction?');
                }

                // Reformat cart list
                const active_cart = document.getElementById('cart_' + active_cart_index);
                const new_cart = document.getElementById('cart_' + index);

                active_cart.classList.remove('shadow', 'border-primary', 'border-1');
                active_cart.classList.add('shadow-sm', 'text-secondary');
                new_cart.classList.remove('shadow-sm', 'text-secondary');
                new_cart.classList.add('shadow', 'border-primary', 'border-1');
                active_cart_index = index;
            }
        }

        function update_amount(id, is_add, price) {
            const amount_span = document.getElementById('order_amount_' + id);
            const amount_input = document.getElementById('input_order_amount_' + id);
            const total_span = document.getElementById('order_total_' + id);
            const amount = parseInt(amount_input.value);
            const new_amount = amount + (is_add ? 1 : (amount <= 0 ? 0 : -1));

            amount_span.innerHTML = new_amount;
            amount_input.value = new_amount;
            total_span.innerHTML = formatRupiah(new_amount * price);

            var change_price = is_add ? price : -price;
            if ((amount == 0 && change_price > 0) || amount > 0) {
                update_transaction(change_price);
            }
        }

        function update_transaction(change_price = 0) {
            if (transaction == null) {
                var new_transaction = 0;
                for (let i = 0; i < order_list.length; i++) {
                    const order = order_list[i];
                    variant = order.variant;
                    new_transaction += order.amount * variant.price
                }
                transaction = new_transaction + change_price;
            } else {
                transaction += change_price;
            }

            transaction_span.innerHTML = formatRupiah(transaction);
            transaction_input.value = transaction;

            const submit_container = document.getElementById('add_transaction_button_container');
            const submit_button = document.getElementById('add_transaction_button');
            if (transaction == 0) {
                submit_container.onclick = function() {
                    notif('Total transaction is 0. Add some order to create a transaction.');
                }
                if (!submit_button.classList.contains('disabled')) {
                    submit_button.classList.add('disabled')
                }
            } else {
                submit_container.onclick = '';
                if (submit_button.classList.contains('disabled')) {
                    submit_button.classList.remove('disabled')
                }
            }
        }

        function formatRupiah(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                currencyDisplay: 'code',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(value);
        }
    </script>
</x-app-layout>
