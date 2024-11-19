<?php
$auth_user = Auth::user();
?>
<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('good.product') }}" class="text-decoration-none text-primary-emphasis"><span
                class="fw-light">{{ 'Goods Product' }}</span></a>
        <i class="bi bi-chevron-compact-right me-2"></i>{{ 'Detail' }}
    </x-slot>

    <div class="container px-4 pb-4">
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 bg-white shadow p-2">
                    <div class="row px-2">
                        {{-- Product Image --}}
                        <div class="col-lg-6 col-12 ">
                            <div id="carousel_product_image" class="carousel slide">
                                <div class="carousel-inner rounded">
                                    @if ($product->image->count() > 0)
                                        <?php $i = 1; ?>
                                        @foreach ($product->image as $image)
                                            <div class="carousel-item {{ $i <= 1 ? 'active' : '' }}"
                                                id="product_carousel_{{ $i }}">
                                                <img src="/storage/images/product/{{ $image->image }}" alt="image"
                                                    class="rounded" style="object-fit: contain; max-width: 100%;"
                                                    id="{{ $image->id }}">
                                                <div
                                                    class="carousel-caption text-dark bg-light bg-opacity-75 rounded-3 shadow-sm p-0">
                                                    <h5 class="mb-0 mt-1">{{ $image->note }}</h5>
                                                    <a class="mb-0 mt-0 text-decoration-none text-secondary"
                                                        href="/storage/images/product/{{ $image->image }}"
                                                        download="{{ $image->image }}">{{ $image->image }} <i
                                                            class="bi bi-download"></i></a>
                                                </div>
                                            </div>
                                            <?php $i++; ?>
                                        @endforeach
                                    @else
                                        <div class="carousel-item active">
                                            <img src="/storage/images/product/example.png" alt="image"
                                                class="rounded" style="object-fit: contain; max-width: 100%;"
                                                id="product_image">
                                        </div>
                                    @endif
                                </div>
                                <button class="carousel-control-prev rounded-start" type="button"
                                    style="background: linear-gradient(to right, #5454545f 0%, #5454542f 20%, #54545400)"
                                    data-bs-target="#carousel_product_image" data-bs-slide="prev">
                                    <i class="bi bi-caret-left text-light bg-opacity-0 fs-1 rounded-3"
                                        aria-hidden="true"></i>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next rounded-end" type="button"
                                    style="background: linear-gradient(to left, #5454545f 0%, #5454542f 20%, #54545400)"
                                    data-bs-target="#carousel_product_image" data-bs-slide="next">
                                    <i class="bi bi-caret-right text-light bg-opacity-0 fs-1 rounded-3"
                                        aria-hidden="true"></i>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div
                                        class="scroll-container-horizontal-lg scroll-container-horizontal bg-light rounded p-2">
                                        <?php $i = 1; ?>
                                        @if ($product->image->count() > 0)
                                            @foreach ($product->image as $image)
                                                <img src="/storage/images/product/{{ $image->image }}" alt="image"
                                                    class="rounded shadow-sm"
                                                    onclick="setCarousel({{ $i++ }})"
                                                    style="object-fit: contain; max-width: 22%;">
                                            @endforeach
                                        @endif
                                        @if ($auth_user->id == $product->pic_id)
                                            <!-- Button trigger Add Image Modal -->
                                            <img src="/storage/images/product/{{ 'product_add.png' }}" alt="image"
                                                class="rounded shadow-sm" style="object-fit: contain; max-width: 22%;"
                                                data-bs-toggle="modal" data-bs-target="#addImageModal">
                                            <!-- Add Image Modal -->
                                            <div class="modal fade" id="addImageModal" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content shadow mx-3">
                                                        <div class="modal-header py-1 ps-3 pe-2">
                                                            <span class="modal-title fs-5 text-primary-emphasis"
                                                                id="exampleModalLabel"><i
                                                                    class="bi bi-shop border-secondary border-end me-2 pe-2"></i>
                                                                {{ 'New Product Image' }}
                                                            </span>
                                                            <button type="button" class="btn btn-sm ms-auto"
                                                                data-bs-dismiss="modal" aria-label="Close"><i
                                                                    class="bi bi-x-lg"></i></button>
                                                        </div>
                                                        <form method="post" id="formAddProduct"
                                                            enctype="multipart/form-data"
                                                            action="{{ route('good.product.image.add', ['id' => $product->id]) }}">
                                                            @csrf
                                                            @method('put')
                                                            <div class="modal-body bg-light">
                                                                <div class="row justify-content-center ">
                                                                    <div class="col-4 col-lg-3">
                                                                        <label for="add_image_note"
                                                                            class="form-label d-inline-block">{{ 'Note' }}</label>
                                                                    </div>
                                                                    <div class="col-8 col-lg-7">
                                                                        <input type="text"
                                                                            class="form-control form-control-sm d-inline-block"
                                                                            placeholder="front look, top view, etc.."
                                                                            name="note" id="add_image_note" required>
                                                                        <x-input-error :messages="$errors->get('note')"
                                                                            class="mt-2" />
                                                                    </div>
                                                                </div>
                                                                <div class="row justify-content-center mt-3">
                                                                    <div class="col-4 col-lg-3">
                                                                        <label for="add_image_file"
                                                                            class="form-label d-inline-block">{{ 'Image' }}</label>
                                                                    </div>
                                                                    <div class="col-8 col-lg-7">
                                                                        <input type="file"
                                                                            class="form-control form-control-sm"
                                                                            name="image" id="add_image_file"
                                                                            value="{{ old('image') }}">
                                                                        <span class="mt-2"
                                                                            style="font-size: 0.8rem;">{{ 'max: 5Mb, ratio: 1/1' }}</span>
                                                                        <x-input-error :messages="$errors->get('image')"
                                                                            class="mt-2" />
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Product Detail --}}
                        <div class="col-lg-6 col-12 ">
                            <div class="row mt-2">
                                <div class="col-12 d-flex">
                                    <span class="fw-normal text-primary fs-5">{{ $product->category }}</span>
                                    {{-- Status Button --}}
                                    <form class="ms-auto me-1 my-auto"
                                        action="{{ route('good.product.transaction.status', ['id' => $product->id]) }}"
                                        method="post">
                                        @csrf
                                        @method('put')
                                        <div
                                            onclick="{{ $auth_user->roles_id == 3 ? '' : 'notif("You can`t change the product transaction status. Please contact Operational Officer.")' }}">
                                            <button
                                                class="btn btn-sm shadow-sm border-0 d-flex {{ $auth_user->roles_id == 3 ? '' : 'disabled' }} {{ $product->operational_id == 0 ? 'btn-light' : 'btn-success' }}">
                                                <i
                                                    class="bi bi-{{ $product->operational_id == 0 ? 'ban' : 'arrow-down-up' }}"></i>
                                                <span
                                                    class="d-none d-lg-block border-start ps-2 ms-1 {{ $product->operational_id == 0 ? 'border-secondary-subtle' : 'border-light' }}">{{ $product->operational_id == 0 ? 'Offline' : 'Online' }}</span></button>
                                        </div>
                                    </form>
                                    {{-- Delete Button --}}
                                    @if ($auth_user->roles_id == 3)
                                        <?php
                                        $total_sale = $product->variant->sum('sale');
                                        ?>
                                        <div class="my-auto"
                                            onclick="{{ $total_sale == 0 ? '' : 'notif("This product has sales. You can not delete this product.")' }}">
                                            <button
                                                class="ms-1 ms-lg-1 me-2 d-flex btn btn-sm btn-secondary {{ $total_sale > 0 ? 'disabled' : '' }}"
                                                onclick="confirmation('{{ route('good.product.delete', ['id' => $product->id]) }}', '{{ 'Are you sure want to delete ' . $product->name . ' from Product List?' }}')">
                                                <i class="bi bi-trash"></i>
                                                <span
                                                    class="d-none d-lg-block border-start border-light ps-2 ms-1">{{ 'Delete' }}</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-12 d-flex">
                                    <span class="fw-bold text-dark fs-2">{{ $product->name }}</span>
                                </div>
                            </div>
                            <div class="row border-bottom border-light-emphasis border-2 pb-2">
                                <div class="col-12">
                                    <div
                                        class="scroll-container-horizontal-lg scroll-container-horizontal bg-light p-2">
                                        @if ($product->variant->count() > 0)
                                            <?php $i = 1; ?>
                                            @foreach ($product->variant as $variant)
                                                <button id="variant_trigger_{{ $i }}"
                                                    class="btn btn-sm btn-outline-secondary border-1 fs-6 button-variant-trigger {{ $i == 1 ? 'active' : '' }}"
                                                    onclick="setVariant({{ $variant }}, 'variant_trigger_{{ $i }}')"
                                                    @if ($i == 1) onload="setVariant({{ $variant }})" @endif>{{ $variant->name }}</button>
                                                <?php $i++; ?>
                                            @endforeach
                                        @else
                                            <button
                                                class="btn btn-sm btn-outline-secondary border-1 fs-6">{{ 'No variant found' }}</button>
                                        @endif

                                        @if ($auth_user->id == $product->pic_id)
                                            <button class="btn btn-sm btn-primary fs-6 px-2" data-bs-toggle="modal"
                                                data-bs-target="#addProductVariantModal"><i
                                                    class="bi bi-plus-lg"></i></button>
                                            <!-- Add Product Variant Modal -->
                                            <div class="modal fade" id="addProductVariantModal" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content shadow mx-3">
                                                        <div class="modal-header py-1 ps-3 pe-2">
                                                            <span class="modal-title fs-5 text-primary-emphasis"
                                                                id="exampleModalLabel"><i
                                                                    class="bi bi-bag-plus border-secondary border-end me-2 pe-2"></i>
                                                                {{ 'New Variant' }}
                                                            </span>
                                                            <button type="button" class="btn btn-sm ms-auto"
                                                                data-bs-dismiss="modal" aria-label="Close"><i
                                                                    class="bi bi-x-lg"></i></button>
                                                        </div>
                                                        <form method="post" id="formAddProductVariant"
                                                            action="{{ route('good.product.variant.add', ['id' => $product->id]) }}">
                                                            @csrf
                                                            @method('put')
                                                            <div class="modal-body bg-light">
                                                                <div class="row justify-content-center">
                                                                    <div class="col-4 col-lg-3">
                                                                        <label for="add_variant_name"
                                                                            class="form-label d-inline-block">{{ 'Name' }}</label>
                                                                    </div>
                                                                    <div class="col-8 col-lg-7">
                                                                        <input type="text"
                                                                            class="form-control form-control-sm d-inline-block"
                                                                            name="name" id="add_variant_name"
                                                                            required>
                                                                        <x-input-error :messages="$errors->get('name')"
                                                                            class="mt-2" />
                                                                    </div>
                                                                </div>
                                                                <div class="row justify-content-center mt-3">
                                                                    <div class="col-4 col-lg-3">
                                                                        <label for="add_variant_price"
                                                                            class="form-label d-inline-block">{{ 'Price' }}</label>
                                                                    </div>
                                                                    <div class="col-8 col-lg-7">
                                                                        <input type="number"
                                                                            class="form-control form-control-sm d-inline-block"
                                                                            name="price" id="add_variant_price"
                                                                            required>
                                                                        <x-input-error :messages="$errors->get('price')"
                                                                            class="mt-2" />
                                                                    </div>
                                                                </div>
                                                                <div class="row justify-content-center mt-3">
                                                                    <div class="col-4 col-lg-3">
                                                                        <label for="add_variant_stock"
                                                                            class="form-label d-inline-block">{{ 'Stock' }}</label>
                                                                    </div>
                                                                    <div class="col-8 col-lg-7">
                                                                        <input type="number"
                                                                            class="form-control form-control-sm d-inline-block"
                                                                            name="stock" id="add_variant_stock"
                                                                            required>
                                                                        <x-input-error :messages="$errors->get('stock')"
                                                                            class="mt-2" />
                                                                    </div>
                                                                </div>
                                                                <div class="row justify-content-center mt-3">
                                                                    <div class="col-4 col-lg-3">
                                                                        <label for="add_variant_description"
                                                                            class="form-label d-inline-block">{{ 'Description' }}</label>
                                                                    </div>
                                                                    <div class="col-8 col-lg-7">
                                                                        <textarea class="form-control form-control-sm" rows="4" name="description" id="add_variant_description"
                                                                            required>
                                                                        </textarea>
                                                                        <x-input-error :messages="$errors->get('description')"
                                                                            class="mt-2" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer p-1">
                                                                <button type="submit" form="formAddProductVariant"
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
                            <div class="row">
                                <div class="col-12 d-flex">
                                    <span class="fs-3 fw-light ms-2 me-3"
                                        id="variant_rate">{{ $product->variant->count() > 0 ? $product->variant->first()->rate : 0.0 }}</span>
                                    <span class="fw-normal text-warning fs-3">
                                        <i class="bi bi-star{{ $product->variant->count() > 0 ? ($product->variant->first()->rate >= 1 ? '-fill' : '') : '' }}"
                                            id="rate_icon_1"></i>
                                        <i class="bi bi-star{{ $product->variant->count() > 0 ? ($product->variant->first()->rate >= 2 ? '-fill' : '') : '' }}"
                                            id="rate_icon_2"></i>
                                        <i class="bi bi-star{{ $product->variant->count() > 0 ? ($product->variant->first()->rate >= 3 ? '-fill' : '') : '' }}"
                                            id="rate_icon_3"></i>
                                        <i class="bi bi-star{{ $product->variant->count() > 0 ? ($product->variant->first()->rate >= 4 ? '-fill' : '') : '' }}"
                                            id="rate_icon_4"></i>
                                        <i class="bi bi-star{{ $product->variant->count() > 0 ? ($product->variant->first()->rate >= 5 ? '-fill' : '') : '' }}"
                                            id="rate_icon_5"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 d-flex">
                                    <span class="fw-normal text-dark fs-3"
                                        id="variant_price">{{ format_currency($product->variant->count() > 0 ? $product->variant->first()->price : 0) }}</span>
                                </div>
                            </div>
                            <div class="row mt-2 text-secondary fs-6">
                                <div class="col-lg-2 col-3 d-flex">
                                    <span class="fw-normal">{{ 'In Charge ' }}</span>
                                </div>
                                <div class="col-lg-10 col-9">
                                    <span id="variant_sale" class="me-1">{{ $product->pic->name }}</span>
                                </div>
                            </div>
                            <div class="row mt-2 text-secondary fs-6">
                                <div class="col-lg-2 col-3 d-flex">
                                    <span class="fw-normal">{{ 'Sale ' }}</span>
                                </div>
                                <div class="col-lg-10 col-9">
                                    <span id="variant_sale"
                                        class="me-1">{{ $product->variant->count() > 0 ? $product->variant->first()->sale : 0 }}</span>{{ 'pcs' }}
                                </div>
                            </div>
                            <div
                                class="row mt-2 text-secondary fs-6 border-bottom border-light-emphasis border-2 pb-2">
                                <div class="col-lg-2 col-3 d-flex">
                                    <span class="fw-normal ">{{ 'Stock ' }}</span>
                                </div>
                                <div class="col-lg-10 col-9 d-flex">
                                    <span id="variant_stock"
                                        class="me-1">{{ $product->variant->count() > 0 ? $product->variant->first()->stock : 0 }}</span>{{ 'pcs' }}
                                    @if ($auth_user->id == $product->pic_id)
                                        {{-- Update Stock Button --}}
                                        <button class="btn btn-sm btn-light d-flex my-auto shadow-sm ms-auto"
                                            data-bs-toggle="modal" data-bs-target="#updateStockModal">
                                            <i class="bi bi-box-arrow-in-down "></i>
                                            <span
                                                class="d-none d-md-block border-start border-secondary-subtle ms-1 ps-2">{{ 'Update Stock' }}</span>
                                        </button>
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
                                                        <button type="button" class="btn btn-sm ms-auto"
                                                            data-bs-dismiss="modal" aria-label="Close"><i
                                                                class="bi bi-x-lg"></i></button>
                                                    </div>
                                                    <div class="modal-body bg-light p-1 px-3">
                                                        <div class="row justify-content-center">
                                                            <div class="col-4 col-lg-4">
                                                                <label class="form-label d-flex"><span
                                                                        class="ms-auto">{{ 'Variant :' }}</span></label>
                                                            </div>
                                                            <div class="col-6 col-lg-6">
                                                                <span
                                                                    id="variant_old_name">{{ $product->variant->count() > 0 ? $product->variant->first()->name : '' }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="row justify-content-center">
                                                            <div class="col-4 col-lg-4">
                                                                <label class="form-label d-flex"><span
                                                                        class="ms-auto">{{ 'Stock :' }}</span></label>
                                                            </div>
                                                            <div class="col-6 col-lg-6">
                                                                <span
                                                                    id="variant_old_stock">{{ $product->variant->count() > 0 ? $product->variant->first()->stock : 0 }}</span>
                                                            </div>
                                                        </div>
                                                        <form method="post" id="formUpdateStock"
                                                            action="{{ route('good.product.stock.update', ['id' => $product->variant->count() > 0 ? $product->variant->first()->id : 0]) }}">
                                                            @csrf
                                                            @method('put')
                                                            <div class="row justify-content-center">
                                                                <div class="col-4 col-lg-4">
                                                                    <label class="form-label d-flex"
                                                                        for="variant_add_stock"><span
                                                                            class="ms-auto">{{ 'Add :' }}</span></label>
                                                                </div>
                                                                <div class="col-6 col-lg-6">
                                                                    <input type="number"
                                                                        class="form-control form-control-sm d-inline-block"
                                                                        name="update_stock" id="variant_add_stock"
                                                                        value="{{ old('update_stock') }}" required>
                                                                    <x-input-error :messages="$errors->get('update_stock')" class="mt-2" />
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
                            </div>
                            <div class="row mt-2">
                                <div class="col-12 d-flex">
                                    <span class="fw-normal text-dark fs-6">{{ 'Description ' }}</span>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    @if ($auth_user->id == $product->pic_id)
                                        <form id="formUpdateDescription" method="post"
                                            action="{{ route('good.product.description.update', ['id' => $product->variant->count() > 0 ? $product->variant->first()->id : 0]) }}">
                                            @csrf
                                            @method('put')
                                            <textarea class="form-control form-control-sm" rows="4" cols="auto" name="update_description"
                                                id="update_description" required>{{ $product->variant->count() > 0 ? $product->variant->first()->description : '' }}</textarea>
                                            <button class="btn btn-sm btn-primary ms-auto d-flex mt-2 shadow-sm"
                                                type="submit">
                                                <i class="bi bi-pencil-square border-end border-light pe-2 me-1"></i>
                                                {{ 'Update' }}</button>
                                        </form>
                                    @else
                                        <span class="fw-normal text-secondary fs-6"
                                            id="variant_description">{!! $product->variant->count() > 0 ? nl2br(e($product->variant->first()->description)) : '' !!}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($auth_user->roles_id == 3 || $auth_user->product->count() > 0)
        {{-- FAB --}}
        <button class="fab btn btn-primary p-2" data-bs-toggle="modal" data-bs-target="#addCartModal">
            <i class="bi bi-cart-plus fs-3"></i>
        </button>

        <!-- Cart Modal -->
        <div class="modal fade" id="addCartModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered px-3 px-lg-0">
                <div class="modal-content shadow mt-5">
                    <div class="modal-header py-1 ps-3 pe-2">
                        <span class="modal-title fs-5 text-primary-emphasis">
                            <i class="bi bi-cart4 border-secondary border-end me-2 pe-2"></i>
                            {{ 'Add Goods Order' }}
                        </span>
                        <button type="button" class="btn btn-sm ms-auto" data-bs-dismiss="modal"
                            aria-label="Close"><i class="bi bi-x-lg"></i></button>
                    </div>
                    <div class="modal-body p-1 px-3">
                        <div class="row my-2">
                            {{-- Cart List --}}
                            <div class="col-12 col-lg-5">
                                <h6 class="border-bottom border-primary-subtle mb-1 pb-1 fw-light">
                                    {{ 'Cart List' }}
                                </h6>
                                <div
                                    class="scroll-container-horizontal scroll-container-lg-0 bg-light mb-2 pt-2 px-3 rounded">
                                    <?php $i = 1; ?>
                                    @foreach ($cart_list as $cart)
                                        <div class="card card-bg-hover shadow-sm mb-2 px-2 d-inline-block d-lg-block"
                                            onclick="set_selected_cart({{ $cart }})">
                                            <div class="row">
                                                <div class="col-12 d-flex">
                                                    <span
                                                        class="fw-light border-end border-secondary-subtle pe-2 me-2">{{ $i }}</span>
                                                    <span>{{ $cart->customer }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $i++; ?>
                                    @endforeach
                                    @if ($cart_list->count() <= 0)
                                        <div class="card shadow-sm mb-2 px-2">
                                            <span class="fst-italic fw-light">{{ 'No Cart Found' }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            {{-- Add Order Form --}}
                            <div class="col-12 col-lg-7">
                                <form method="post" id="formAddOrder">
                                    @csrf
                                    @method('put')</form>
                                <div class="row gx-2">
                                    <div class="col-3 text-end">
                                        <span class="fw-light mb-1">{{ 'Customer ' }}</span><br>
                                        <span class="fw-light">{{ 'Goods ' }}</span><br>
                                        <span class="fw-light">{{ 'Variant ' }}</span><br>
                                        <span class="fw-light">{{ 'Amount ' }}</span>
                                    </div>
                                    <div class="col-9">
                                        <div class="scroll-x-hidden"><span
                                                class="text-dark scroll-x-hidden text-nowrap"
                                                id="order_customer"></span></div>
                                        <div class="scroll-x-hidden"><span
                                                class="text-dark scroll-x-hidden text-nowrap">{{ $product->name }}</span>
                                        </div>
                                        <div class="scroll-x-hidden"><span class="text-dark text-nowrap"
                                                id="order_variant"></span></div>
                                        <input type="hidden" id="input_order_variant" name="variant_id"
                                            form="formAddOrder">
                                        <button class="btn btn-sm btn-light px-2" onclick="update_amount(false)"><i
                                                class="bi bi-dash"></i></button><span class="mx-2 text-dark"
                                            id="order_amount">{{ 1 }}</span><button
                                            class="btn btn-sm btn-light" onclick="update_amount(true)"><i
                                                class="bi bi-plus"></i></button>
                                        <input type="hidden" id="input_order_amount" name="amount"
                                            form="formAddOrder" value="1">
                                    </div>
                                </div>
                                <div class="row mb-1 mt-2 pt-2 border-top border-light-emphasis">
                                    <div class="col-12">
                                        <div onclick="{{ $product->operational_id <= 0 ? "notif('This product is offline. Please ask Operational Officer to set it online.');" : '' }}"
                                            {{ $product->operational_id <= 0 ? 'data-bs-dismiss=modal' : '' }}>
                                            <button
                                                class="btn btn-sm btn-primary w-100 shadow-sm {{ $product->operational_id <= 0 ? 'disabled' : '' }}"
                                                form="formAddOrder" type="submit"><i
                                                    class="bi bi-cart-plus border-end border-light me-2 pe-2"></i>{{ 'Add to Cart' }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        const auth_user = <?php echo $auth_user; ?>;
        const product = <?php echo $product; ?>;
        const cart_list = <?php echo $cart_list; ?>;
        const update_description_route = "<?php echo route('good.product.description.update'); ?>";
        const update_stock_route = "<?php echo route('good.product.stock.update'); ?>";
        const add_order_route = product.operational_id > 0 ? "<?php echo route('good.order.add'); ?>" : "";
        var active_carousel_index = 1;

        window.onload = function() {
            if (product.variant.length > 0) {
                set_active_variant(product.variant[0]);
            }
            if (cart_list.length > 0) {
                set_selected_cart(cart_list[0]);
            }
        }

        function setCarousel(index) {
            const active_carousel = document.getElementById('product_carousel_' + active_carousel_index);
            const new_carousel = document.getElementById('product_carousel_' + index);

            active_carousel.classList.remove('active');
            new_carousel.classList.add('active');

            active_carousel_index = index;
        }

        function setVariant(variant, trigger_id) {
            deactiveVariantTrigger();

            let trigger = document.getElementById(trigger_id);
            let price = document.getElementById('variant_price');
            let sale = document.getElementById('variant_sale');
            let stock = document.getElementById('variant_stock');
            let update_description = document.getElementById('update_description');
            let old_stock = document.getElementById('variant_old_stock');
            let old_name = document.getElementById('variant_old_name');
            let description = document.getElementById('variant_description');
            let rate = document.getElementById('variant_rate');
            let form_update_description = document.getElementById('formUpdateDescription');
            let form_update_stock = document.getElementById('formUpdateStock');
            let rate1 = document.getElementById('rate_icon_1');
            let rate2 = document.getElementById('rate_icon_2');
            let rate3 = document.getElementById('rate_icon_3');
            let rate4 = document.getElementById('rate_icon_4');
            let rate5 = document.getElementById('rate_icon_5');

            rate1.setAttribute('class', variant.rate >= 1 ? 'bi bi-star-fill' : 'bi bi-star');
            rate2.setAttribute('class', variant.rate >= 2 ? 'bi bi-star-fill' : 'bi bi-star');
            rate3.setAttribute('class', variant.rate >= 3 ? 'bi bi-star-fill' : 'bi bi-star');
            rate4.setAttribute('class', variant.rate >= 4 ? 'bi bi-star-fill' : 'bi bi-star');
            rate5.setAttribute('class', variant.rate >= 5 ? 'bi bi-star-fill' : 'bi bi-star');

            trigger.classList.add('active');
            rate.innerHTML = variant.rate
            sale.innerHTML = variant.sale;
            price.innerHTML = formatRupiah(variant.price);
            stock.innerHTML = variant.stock;
            if (auth_user.id == product.pic_id) {
                update_description.innerHTML = variant.description;
                old_stock.innerHTML = variant.stock;
                old_name.innerHTML = variant.name;
                form_update_description.setAttribute('action', update_description_route + '/' + variant.id)
                form_update_stock.setAttribute('action', update_stock_route + '/' + variant.id)
            } else {
                description.innerHTML = variant.description.replace(/\n/g, '<br>');
            }

            set_active_variant(variant)
        }

        function set_active_variant(variant) {
            var order_variant = document.getElementById('order_variant');
            var input_order_variant = document.getElementById('input_order_variant');

            order_variant.innerHTML = variant.name;
            input_order_variant.value = variant.id;

            return true;
        }

        function set_selected_cart(cart) {
            var order_customer = document.getElementById('order_customer');
            var input_form = document.getElementById('formAddOrder');

            order_customer.innerHTML = cart.customer;
            input_form.setAttribute('action', add_order_route + '/' + cart.id);

            return true;
        }

        function update_amount(is_add) {
            var order_amount = document.getElementById('order_amount');
            var input_order_amount = document.getElementById('input_order_amount');

            order_amount.innerHTML = parseInt(order_amount.innerHTML) + (is_add ?
                1 : ((parseInt(order_amount.innerHTML) > 1 ? -1 : 0)));
            input_order_amount.value = parseInt(input_order_amount.value) + (
                is_add ?
                1 : (parseInt(input_order_amount.value) > 1 ? -1 : 0));
        }

        function deactiveVariantTrigger() {
            let trigger = document.getElementsByClassName('button-variant-trigger');
            for (let i = 0; i < trigger.length; i++) {
                trigger[i].classList.remove('active');
            }
        }

        function dismiss_modal(id) {
            var modal = document.getElementById(id);

            modal.classList.remove('show');
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
