<x-app-layout>
    <?php
    $auth_user = Auth::user(); ?>
    <x-slot name="header">
        {{ __('Point of Sales') }}
    </x-slot>
    <div class="container">
        <div class="row pb-4 gx-2">
            <div class="col-12 col-lg-6">
                {{-- Stand List --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card border shadow p-3 mt-4 rounded mx-2">
                            {{-- Header --}}
                            <div class="d-flex border-primary border-bottom pb-2">
                                <span class="text-primary-emphasis ms-2 me-auto my-auto h4">
                                    <i class="bi bi-shop me-2 "></i>
                                    {{ __('Active Stand List') }}
                                </span>
                            </div>
                            {{-- Stand List Item --}}
                            <div class="row mx-1 mt-2 bg-light">
                                <div class="scroll-container-horizontal-lg scroll-container-horizontal mx-auto">
                                    @if ($stand_list->count() == 0)
                                        <span
                                            class="text-secondary rounded-md d-inline-block my-2">{{ 'No active Stand' }}</span>
                                    @else
                                        <?php $stand_number = 1; ?>
                                        @foreach ($stand_list as $stand)
                                            <?php
                                            $is_stand_active = $stand->id == $active_stand->id;
                                            $stand_card_class = $is_stand_active ? 'bg-white shadow' : 'bg-white shadow-sm';
                                            $stand_divider_class = $is_stand_active ? 'border-2' : 'border-1';
                                            $stand_text_class = $is_stand_active ? 'text-dark' : 'text-secondary fw-light';
                                            $stand_number_class = $is_stand_active ? 'fw-normal' : 'fw-light';
                                            $stand_text_secondary_class = $is_stand_active ? 'text-dark' : 'text-secondary';
                                            ?>
                                            {{-- stand List Item --}}
                                            <div class="d-inline-block me-2 my-2">
                                                <a href="{{ route('food.sales', ['id' => $stand->id]) }}"
                                                    class="text-decoration-none">
                                                    <div class="card rounded d-block border-0 {{ $stand_card_class }}">
                                                        <div class="row py-1">
                                                            <div
                                                                class="col-auto ms-3 fw-light {{ $stand_number_class }} pt-1">
                                                                <span class="fs-6">
                                                                    {{ $stand_number }}
                                                                </span>
                                                            </div>
                                                            <div
                                                                class="col-auto me-3 border-start border-secondary {{ $stand_divider_class }}">
                                                                <p class="m-0 {{ $stand_text_class }} fs-5">
                                                                    {{ $stand->name }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <?php $stand_number++; ?>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Menu List --}}
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border shadow-sm p-3 rounded mx-2">
                            {{-- Menu Header --}}
                            <div class="d-flex border-secondary border-bottom pb-2">
                                <span class="text-primary-emphasis ms-2 me-auto my-auto h4">
                                    <i class="bi bi-menu-button-wide-fill me-2 "></i>
                                    <?php $stand_name = $active_stand == null ? '' : $active_stand->name; ?>
                                    {{ 'Stand ' }}<span
                                        class="d-none d-lg-inline">{{ $stand_name . ' ' }}</span>{{ 'Menu' }}
                                </span>
                            </div>
                            {{-- Menu Item --}}
                            <div class="row">
                                <div class="col-12">
                                    <div class="bg-light rounded-md mx-0" style="--bs-bg-opacity:.8">
                                        <div class="scroll-container scroll-container-lg mt-3 pb-2">
                                            <div class="row">
                                                @if ($menu_list == null)
                                                    <span
                                                        class="text-secondary ms-3 mt-2">{{ 'No active Stand' }}</span>
                                                @else
                                                    <?php $i = 1; ?>
                                                    @foreach ($menu_list as $menu)
                                                        <div class="col-12 col-md-6 px-0">
                                                            <?php
                                                            $is_mass = $menu->mass > 0;
                                                            $is_volume = $menu->volume > 0;
                                                            $and = $is_mass && $is_volume;
                                                            $or = $is_mass || $is_volume;
                                                            ?>
                                                            <div tabindex="0" data-bs-toggle="popover"
                                                                data-bs-custom-class="custom-popover"
                                                                data-bs-placement="top" data-bs-trigger="hover"
                                                                data-bs-title="{{ $menu->name }}"
                                                                data-bs-content="{{ ($or ? '(' : '') . ($is_volume ? $menu->volume . '/' . $menu->volume_unit : '') . ($and ? ' - ' : '') . ($is_mass ? $menu->mass . '/' . $menu->mass_unit : '') . ($or ? ')' : '') . ' ' . format_currency($menu->price) }}"
                                                                class="card shadow-sm mt-2 mx-3 py-1 border-0 bg-white {{ $i++ % 2 == 1 ? 'me-md-1' : 'ms-md-1' }}">
                                                                <div class="d-flex">
                                                                    <span
                                                                        class="text-primary-emphasis ms-2">{{ substr($menu->name, 0, 15) . (strlen($menu->name) > 15 ? '...' : '') }}</span>
                                                                    <span
                                                                        class="fw-light ms-auto me-1 position-relative">
                                                                        <span
                                                                            class="d-none d-md-inline ">{{ 'sale (' }}</span><span
                                                                            class="d-md-none">{{ '(' }}</span><span
                                                                            class="{{ $menu->stock > 0 ? ($menu->sale / $menu->stock > 0.8 ? 'text-danger-emphasis fw-bold' : 'text-dark fw-normal ') : 'text-dark fw-normal ' }}">{{ $menu->sale }}</span>/<span
                                                                            class="fw-normal {{ $menu->stock > 0 ? ($menu->sale / $menu->stock > 0.8 ? 'text-danger-emphasis' : 'text-secondary') : 'text-secondary' }}">{{ $menu->stock }}</span>{{ ')' }}
                                                                        @if ($menu->stock == 0)
                                                                            <span
                                                                                class="rounded-circle p-1 bg-danger position-absolute top-0 end-0"></span>
                                                                        @endif
                                                                    </span>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-light py-0 px-1 me-2 text-{{ $menu->stock == 0 ? 'secondary disabled' : 'primary' }}"
                                                                        onclick="setOrder({{ $menu }})">{{ '+' }}</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
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
            <div class="col-12 col-lg-6">
                {{-- Add Transaction --}}
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border shadow-sm p-3 rounded mx-2">
                            {{-- Header --}}
                            <div class="d-flex border-secondary border-bottom pb-2">
                                <span class="text-primary-emphasis mx-auto my-auto h4">
                                    <i class="bi bi-receipt-cutoff me-2 "></i>
                                    {{ __('New Transaction') }}
                                </span>
                            </div>
                            <form method="post"
                                action="{{ route('stand.sale.add', ['id' => $active_stand ? $active_stand->id : 0]) }}"
                                id="formAddSale">
                                @csrf
                                @method('put')
                                <div class="d-flex d-md-none mt-2">
                                    <span
                                        class="text-secondary ms-auto">{{ format_date(now(), 'day') . ', ' . format_date(now()) }}</span>
                                    <span class="text-secondary ms-3 me-auto" id="sale_clock"></span>
                                </div>
                                <div class="row mt-md-2">
                                    <div class="col-4 col-md-2 text-end pe-0 mt-md-0 mt-1">
                                        <span class="fw-light">{{ 'Stand :' }}</span>
                                    </div>
                                    <div class="col-8 col-md-4 d-flex ps-1 mt-md-0 mt-1">
                                        <span
                                            class="fw-normal text-primary-emphasis ">{{ $active_stand ? $active_stand->name : '' }}</span>
                                    </div>
                                    <div class="col-4 col-md-2 text-end pe-0 mt-md-0 mt-1">
                                        <span class="fw-light">{{ 'Cashier :' }}</span>
                                    </div>
                                    <div class="col-8 col-md-4 d-flex ps-1 mt-md-0 mt-1">
                                        <span class="fw-normal text-primary-emphasis ">{{ $auth_user->name }}</span>
                                    </div>
                                </div>
                                <div class="row mt-md-2">
                                    <div class="col-4 col-md-2 mt-md-0 mt-2 text-end pe-0">
                                        <label for="add_sale_customer"
                                            class="form-label d-inline-block fw-light">{{ 'Customer :' }}</label>
                                    </div>
                                    <div class="col-8 col-md-4 ps-1 mt-md-0 mt-2">
                                        <input type="text" class="form-control form-control-sm d-inline-block"
                                            name="customer" id="add_sale_customer" value="{{ old('customer') }}"
                                            required>
                                        <x-input-error :messages="$errors->get('customer')" class="mt-2" />
                                    </div>
                                    <div class="col-4 col-md-2 mt-md-0 mt-2  text-end pe-0">
                                        <label for="add_sale_token"
                                            class="form-label d-inline-block fw-light">{{ 'Token :' }}</label>
                                    </div>
                                    <div class="col-8 col-md-4  ps-1 mt-md-0 mt-2">
                                        <input type="text" class="form-control form-control-sm d-inline-block"
                                            name="token" id="add_sale_token" value="{{ old('token') }}" required>
                                        <x-input-error :messages="$errors->get('token')" class="mt-2" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4 col-md-auto text-end pe-0">
                                        <span class="fw-light">{{ 'Orders Item :' }}</span>
                                    </div>
                                    <div class="col-8 col-md d-flex">
                                        <span class="border-secondary-subtle border-1 border-bottom my-auto"
                                            style="width: 100%; height:1px;"></span>
                                    </div>
                                </div>
                                <div class="row justify-content-end">
                                    <div class="col-12 col-md-10">
                                        <div class="scroll-container-lg scroll-container bg-white rounded-md  pt-2 pt-md-0"
                                            id="sale_order_container" style="min-height: 10px;">
                                            <div class="px-md-3 px-5 mb-2" id="order_default">
                                                <div class="card border-0 bg-light" id="order_card_default">
                                                    <span
                                                        class="fst-italic text-secondary text-center">{{ 'Click menu to add order item.' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-end">
                                            <div class="col-md-12 col-8 d-flex">
                                                <span class="border-secondary-subtle border-1 border-bottom my-auto"
                                                    style="width: 100%; height:1px;"></span>
                                            </div>
                                        </div>
                                        <div class="row justify-content-end my-1">
                                            <div class="col-md-12 col-8 d-flex">
                                                <span class="ms-auto fw-light">{{ 'Subtotal : ' }}</span>
                                                <span class="text-primary-emmphasis ms-2 me-3"
                                                    id="sale_subtotal">{{ format_currency(0) }}</span>
                                            </div>
                                        </div>
                                        <div class="row justify-content-end">
                                            <div class="col-md-12 col-8 d-flex">
                                                <span class="border-secondary-subtle border-1 border-bottom my-auto"
                                                    style="width: 100%; height:1px;"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-4 col-md-2  text-end pe-0">
                                        <label for="add_sale_discount"
                                            class="form-label d-inline-block fw-light">{{ 'Discount :' }}</label>
                                    </div>
                                    <div class="col-8 col-md-4 ps-1">
                                        <input type="number" class="form-control form-control-sm d-inline-block"
                                            oninput="setDiscount()" name="discount" id="add_sale_discount"
                                            value="0" value="{{ old('discount') }}" required>
                                        <x-input-error :messages="$errors->get('discount')" class="mt-2" />
                                    </div>
                                    <div class="col-12 col-md-6 d-flex">
                                        <span class="ms-auto fw-light">{{ 'Total :' }}</span>
                                        <span class="me-3 ms-2 fw-normal text-primary-emphasis"
                                            id="sale_total">{{ format_currency(0) }}</span>
                                        <input type="hidden" name="transaction" id="add_sale_transaction">
                                    </div>
                                </div>
                                <div class="row mt-2 justify-content-center">
                                    <div class="col-12 col-lg-10">
                                        <button type="submit" form="formAddSale"
                                            class="btn btn-sm btn-primary w-100">{{ 'Add Transaction' }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Submission
        document.getElementById('formAddSale').addEventListener('submit', function(event) {
            if (sale_subtotal === 0) {
                let order_card_default = document.getElementById('order_card_default');
                order_card_default.classList.remove('border-0');
                order_card_default.classList.add('shadow-sm');

                event.preventDefault();

                collapse_toggle('collapseStandMenuTrigger')
            }
        });

        // Order
        const order_container = document.getElementById('sale_order_container');
        var order_index = 0;
        var sale_subtotal = 0;

        function setOrder(menu) {
            if (document.getElementById('order' + menu.id)) {
                return;
            }
            if (document.getElementById('order_default')) {
                document.getElementById('order_default').classList.add('d-none');
            }
            order_index += 1;
            // create order
            const order = document.createElement('div');
            order.id = 'order' + menu.id;
            order.className = 'col-12 mb-2 d-flex text-primary-emphasis';
            // create minus button
            const btn_min = document.createElement('button');
            btn_min.id = 'btn_min' + menu.id;
            btn_min.className = 'btn btn-sm btn-light p-0 px-2';
            btn_min.innerHTML = '-';
            btn_min.type = 'button';
            btn_min.onclick = function() {
                updateOrder(menu.id, false, menu.price);
            };
            order.appendChild(btn_min);
            // create amount span 
            const span_amn = document.createElement('span');
            span_amn.id = 'span_amn' + menu.id;
            span_amn.className = 'text-dark mx-2';
            span_amn.innerHTML = 1;
            order.appendChild(span_amn);
            // create plus button
            const btn_plus = document.createElement('button');
            btn_plus.id = 'btn_plus' + menu.id;
            btn_plus.className = 'btn btn-sm btn-light p-0 px-2';
            btn_plus.innerHTML = '+';
            btn_plus.type = 'button';
            btn_plus.onclick = function() {
                updateOrder(menu.id, true, menu.price);
            };
            order.appendChild(btn_plus);
            // create amount input
            const input_amn = document.createElement('input');
            input_amn.id = 'input_amn' + menu.id;
            input_amn.name = "order[" + order_index + "][amount]";
            input_amn.type = 'hidden';
            input_amn.value = 1;
            order.appendChild(input_amn);
            // create order name span 
            const span_name = document.createElement('span');
            span_name.id = 'span_name' + menu.id;
            span_name.className = 'ms-auto';
            span_name.innerHTML = menu.name + ' - ';
            order.appendChild(span_name);
            // create menu input
            const input_menu = document.createElement('input');
            input_menu.id = 'input_menu' + menu.id;
            input_menu.name = "order[" + order_index + "][menu_id]";
            input_menu.type = 'hidden';
            input_menu.value = menu.id;
            order.appendChild(input_menu);
            // create order total span 
            const span_total = document.createElement('span');
            span_total.id = 'span_total' + menu.id;
            span_total.className = 'ms-1 me-2 fw-light';
            span_total.innerHTML = formatRupiah(menu.price);
            order.appendChild(span_total);

            order_container.appendChild(order);

            setSubTotal(menu.price);
        }

        function updateOrder(id, add, price) {
            const input_amn = document.getElementById('input_amn' + id);
            const span_amn = document.getElementById('span_amn' + id);
            const order_total = document.getElementById('span_total' + id);

            const curr_amn = input_amn.value;
            const new_amn = add ? Number(curr_amn) + 1 : Number(curr_amn) - 1;

            if (new_amn > 0) {
                span_amn.innerHTML = new_amn;
                input_amn.value = new_amn;
                order_total.innerHTML = formatRupiah(new_amn * price);
            } else {
                document.getElementById('order' + id).remove();
            }
            var price = add ? price : -price;
            setSubTotal(price);
        }

        function setSubTotal(price) {
            let subtotal = document.getElementById('sale_subtotal');
            sale_subtotal += price;
            subtotal.innerHTML = formatRupiah(sale_subtotal);
            if (sale_subtotal == 0) {
                document.getElementById('order_default').classList.remove('d-none');
            }

            setTotal(price);
        }

        function setDiscount() {
            let total = document.getElementById('sale_total');
            let transaction = document.getElementById('add_sale_transaction');
            let discount = document.getElementById('add_sale_discount');
            transaction.value = sale_subtotal - discount.value;
            total.innerHTML = formatRupiah(sale_subtotal - discount.value);
        }

        function setTotal(price) {
            let total = document.getElementById('sale_total');
            let transaction = document.getElementById('add_sale_transaction');
            const new_price = Number(transaction.value) + price;
            transaction.value = new_price;
            total.innerHTML = formatRupiah(new_price);
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

        function collapse_toggle(trigger_id) {
            // if (trigger_id == 'collapseStandMenuTrigger') {
            //     var container = document.getElementById('collapseForm');
            //     var trigger = document.getElementById('collapseFormTrigger');
            //     if (container.classList.contains('show')) {
            //         container.classList.remove('show');
            //         trigger.classList.remove('bi-chevron-up');
            //         trigger.classList.add('bi-chevron-down');
            //     }
            // } else {
            // var container1 = document.getElementById('collapseStand');
            // var container2 = document.getElementById('collapseMenu');
            // var trigger = document.getElementById('collapseStandMenuTrigger');
            //     if (container1.classList.contains('show')) {
            //         container1.classList.remove('show');
            //         container2.classList.remove('show');
            //         trigger.classList.remove('bi-chevron-up');
            //         trigger.classList.add('bi-chevron-down');
            //     }
            // }

            if (trigger_id == 'collapseStandMenuTrigger') {
                var container1 = document.getElementById('collapseStand');
                var container2 = document.getElementById('collapseMenu');
                var trigger = document.getElementById('collapseStandMenuTrigger');
                if (container1.classList.contains('show')) {
                    container1.classList.remove('show');
                    container2.classList.remove('show');
                    trigger.classList.remove('bi-chevron-up');
                    trigger.classList.add('bi-chevron-down');
                } else {
                    container1.classList.add('show');
                    container2.classList.add('show');
                    trigger.classList.add('bi-chevron-up');
                    trigger.classList.remove('bi-chevron-down');
                }
            } else {
                var container = document.getElementById('collapseForm');
                var trigger = document.getElementById('collapseFormTrigger');
                if (container.classList.contains('show')) {
                    container.classList.remove('show');
                    trigger.classList.remove('bi-chevron-up');
                    trigger.classList.add('bi-chevron-down');
                } else {
                    container.classList.add('show');
                    trigger.classList.remove('bi-chevron-down');
                    trigger.classList.add('bi-chevron-up');
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

        // Clock
        function updateSaleClock() {
            var now = new Date();
            var hours = now.getHours().toString().padStart(2, '0');
            var minutes = now.getMinutes().toString().padStart(2, '0');
            var seconds = now.getSeconds().toString().padStart(2, '0');
            var timeString = hours + ':' + minutes + ':' + seconds;
            document.getElementById('sale_clock').textContent = timeString;
        }

        // Update the clock every second
        setInterval(updateSaleClock, 1000);
        // Initialize the clock immediately
        updateSaleClock();
    </script>
</x-app-layout>
