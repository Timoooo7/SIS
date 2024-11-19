<?php
$auth_user = Auth::user();
?>

<x-app-layout>
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    <div class="container">
        <div class="row gx-3">
            <div class="col-12 col-lg-7">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="carousel slide position-relative shadow  rounded" id="billboardCarousel"
                            data-bs-ride="carousel">
                            <div class="carousel-inner rounded">
                                <?php $i = 1; ?>
                                @foreach ($billboard_list as $billboard)
                                    <div class="carousel-item {{ $i == 1 ? 'active' : '' }}">
                                        <div class="card"
                                            data-bs-target='{{ $auth_user->roles_id == 1 ? '#setBillboardModal' : '' }}'
                                            data-bs-toggle='{{ $auth_user->roles_id == 1 ? 'modal' : '' }}'
                                            onclick='setBillboard({{ $billboard }})'>
                                            <div class="row g-2 justify-content-center">
                                                <div
                                                    class="col-lg-{{ $billboard->text ? '4' : '12' }} col-{{ $billboard->text ? '5' : '12' }} {{ $billboard->image ? '' : 'd-none' }}">
                                                    <img src="/storage/images/billboard/{{ $billboard->image }}"
                                                        alt="image" class="rounded bg-secondary bg-opacity-25"
                                                        style="height:auto; width:100%; aspect-ratio: {{ $billboard->text ? '1' : '2.5' }}; object-fit:cover;">
                                                </div>
                                                <div
                                                    class="col-lg-{{ $billboard->image ? '8' : '12' }} col-{{ $billboard->image ? '7' : '12' }} {{ $billboard->text ? '' : 'd-none' }}">
                                                    <div class="row mt-2">
                                                        <div class="col-12 d-flex">
                                                            <div
                                                                class="scroll-x-hidden me-2 {{ !$billboard->image ? 'ms-2' : '' }}">
                                                                <span
                                                                    class="h3 text-primary-emphasis">{{ $billboard->title }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-0">
                                                        <div class="col-12">
                                                            <p style="text-align: justify;"
                                                                class="me-2 justify-text {{ !$billboard->image ? 'ms-2' : '' }}">
                                                                {{ $billboard->text }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $i++; ?>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#billboardCarousel"
                                data-bs-slide="prev">

                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#billboardCarousel"
                                data-bs-slide="next">

                            </button>
                        </div>
                    </div>
                    @if ($auth_user->roles_id == 1)
                        {{-- Billboard Settings --}}
                        <div class="modal fade" id="setBillboardModal" tabindex="-1"
                            aria-labelledby="setBillboardModal" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content shadow mx-3">
                                    <div class="modal-header py-1 ps-3 pe-2">
                                        <span class="modal-title fs-5 text-primary-emphasis">
                                            <i class="bi bi-gear border-secondary border-end me-2 pe-2"></i>
                                            {{ 'Billboard Settings' }}
                                        </span>
                                        <button type="button" class="btn btn-sm ms-auto" data-bs-dismiss="modal"
                                            aria-label="Close"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                    <div class="modal-body bg-light">
                                        <div class="row g-2 justify-content-center">
                                            <div class="col-4">
                                                <button class="btn btn-sm btn-primary w-100"
                                                    data-bs-target="#addBillboardModal"
                                                    data-bs-toggle="modal">{{ 'New Billboard' }}</button>
                                            </div>
                                            <div class="col-4">
                                                <button class="btn btn-sm btn-secondary w-100" data-bs-dismiss="modal"
                                                    id="btn_billboard_delete">{{ 'Delete Billboard' }}</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer p-1">
                                        <button type="button" class="btn btn-sm btn-primary"
                                            data-bs-dismiss="modal">{{ 'Cancel' }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Add Billboard Modal --}}
                        <div class="modal fade" id="addBillboardModal" tabindex="-1"
                            aria-labelledby="addBillboardModal" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content shadow mx-3">
                                    <div class="modal-header py-1 ps-3 pe-2">
                                        <span class="modal-title fs-5 text-primary-emphasis">
                                            <i class="bi bi-plus-lg border-secondary border-end me-2 pe-2"></i>
                                            {{ 'New Billboard' }}
                                        </span>
                                        <button type="button" class="btn btn-sm ms-auto" data-bs-dismiss="modal"
                                            aria-label="Close"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                    <form method="post" action="{{ route('billboard.add') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('put')
                                        <div class="modal-body bg-light bg-opacity-100">
                                            <div class="row gx-2 justify-content-center text-end">
                                                <div class="col-2 col-lg-2">
                                                    <label for="dashboard_title"
                                                        class="form-label d-inline-block  text-secondary">{{ 'Title' }}</label>
                                                </div>
                                                <div class="col-10 col-lg-8">
                                                    <input type="text"
                                                        class="form-control form-control-sm d-inline-block"
                                                        placeholder="Type here.." name="dashboard_title"
                                                        value="{{ old('dashboard_title') }}" id="dashboard_title"
                                                        required>
                                                    <x-input-error :messages="$errors->get('dashboard_title')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="row gx-2 justify-content-center mt-2">
                                                <div class="col-2 col-lg-2 text-end">
                                                    <label for="billboardType1"
                                                        class="form-label d-inline-block text-secondary">{{ 'Type' }}</label>
                                                </div>
                                                <div class="col-10 col-lg-8">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox"
                                                            onclick="setInput('dashboard_image'), setBillboardInput('image')"
                                                            id="billboardType1" checked name="typeImage">
                                                        <label class="form-check-label"
                                                            for="billboardType1">{{ 'image' }}</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox"
                                                            onclick="setInput('dashboard_text'), setBillboardInput('text')"
                                                            id="billboardType2" checked name="typeText">
                                                        <label class="form-check-label"
                                                            for="billboardType2">{{ 'text' }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row gx-2 mt-2 justify-content-center"
                                                id="attachment_input_image">
                                                <div class="col-2 col-lg-2 text-end">
                                                    <label for="dashboard_image"
                                                        class="form-label d-inline-block text-secondary">{{ 'Image' }}</label>
                                                </div>
                                                <div class="col-10 col-lg-8">
                                                    <input type="file" class="form-control form-control-sm"
                                                        name="dashboard_image" id="dashboard_image" required
                                                        value="{{ old('dashboard_image') }}">
                                                    <span class="mt-1 text-secondary"
                                                        style="font-size: 0.8rem;">{{ 'max: 5Mb, ratio: 1/1' }}</span>
                                                    <x-input-error :messages="$errors->get('dashboard_image')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="row gx-2 mt-2 justify-content-center"
                                                id="attachment_input_text">
                                                <div class="col-2 col-lg-2 text-end">
                                                    <label for="dashboard_text"
                                                        class="form-label d-inline-block text-secondary">{{ 'Text' }}</label>
                                                </div>
                                                <div class="col-10 col-lg-8">
                                                    <textarea class="form-control form-control-sm" rows="4" name="dashboard_text" id="dashboard_text" required>{{ old('dashboard_text') }}</textarea>
                                                    <x-input-error :messages="$errors->get('dashboard_text')" class="mt-2" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer p-1">
                                            <button type="submit"
                                                class="btn btn-sm btn-primary">{{ 'Set' }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <h5
                                class="mx-2 mt-2 border-bottom border-2 border-dark-subtle pb-1 text-primary-emphasis d-flex">
                                <i class="bi bi-paperclip me-1 text-primary rounded bg-primary-subtle p-1"></i><span
                                    class="my-auto">{{ 'Important Attachment' }}</span>
                                @if ($auth_user->roles_id == 1)
                                    <button class="btn btn-sm btn-primary my-auto ms-auto p-0 px-1"
                                        data-bs-toggle="modal" data-bs-target="#addAttachmentModal"><i
                                            class="bi bi-plus-lg"></i></button>
                                @endif
                            </h5>
                            @if ($auth_user->roles_id == 1)
                                {{-- Add Attachment Modal --}}
                                <div class="modal fade" id="addAttachmentModal" tabindex="-1"
                                    aria-labelledby="addAttachmentModal" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content shadow mx-3">
                                            <div class="modal-header py-1 ps-3 pe-2">
                                                <span class="modal-title fs-5 text-primary-emphasis">
                                                    <i class="bi bi-plus-lg border-secondary border-end me-2 pe-2"></i>
                                                    {{ 'New Attachment' }}
                                                </span>
                                                <button type="button" class="btn btn-sm ms-auto"
                                                    data-bs-dismiss="modal" aria-label="Close"><i
                                                        class="bi bi-x-lg"></i></button>
                                            </div>
                                            <form method="post" action="{{ route('attachment.add') }}"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('put')
                                                <div class="modal-body bg-light bg-opacity-100">
                                                    <div class="row gx-2 justify-content-center text-end">
                                                        <div class="col-3 col-lg-3 text-end">
                                                            <label for="attachment_title"
                                                                class="form-label d-inline-block text-secondary">{{ 'Title' }}</label>
                                                        </div>
                                                        <div class="col-8 col-lg-8">
                                                            <input type="text"
                                                                class="form-control form-control-sm d-inline-block"
                                                                placeholder="Type here.." name="attachment_title"
                                                                value="{{ old('attachment_title') }}"
                                                                id="attachment_title" required>
                                                            <x-input-error :messages="$errors->get('attachment_title')" class="mt-2" />
                                                        </div>
                                                    </div>
                                                    <div class="row gx-2 justify-content-center mt-2">
                                                        <div class="col-3 col-lg-3 text-end">
                                                            <label for="billboardType1"
                                                                class="form-label d-inline-block text-secondary">{{ 'Type' }}</label>
                                                        </div>
                                                        <div class="col-8 col-lg-8">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio"
                                                                    name="attachment_type" id="attachmentType1"
                                                                    checked onclick="setAttachmentInput('document')"
                                                                    value="document">
                                                                <label class="form-check-label"
                                                                    for="attachmentType1">{{ 'Document' }}</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio"
                                                                    name="attachment_type" id="attachmentType2"
                                                                    onclick="setAttachmentInput('link')"
                                                                    value="link">
                                                                <label class="form-check-label"
                                                                    for="attachmentType2">{{ 'Link' }}</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row gx-2 mt-2 justify-content-center"
                                                        id="attachment_input_document">
                                                        <div class="col-3 col-lg-3 text-end">
                                                            <label for="attachment_document"
                                                                class="form-label d-inline-block text-secondary">{{ 'Document' }}</label>
                                                        </div>
                                                        <div class="col-8 col-lg-8">
                                                            <input type="file" class="form-control form-control-sm"
                                                                name="attachment_document" id="attachment_document"
                                                                value="{{ old('attachment_document') }}">
                                                            <span class="mt-1 text-secondary fw-light"
                                                                style="font-size: 0.8rem;">{{ '5Mb - pdf, docx, jpg, jpeg, png, heic' }}</span>
                                                            <x-input-error :messages="$errors->get('attachment_document')" class="mt-1" />
                                                        </div>
                                                    </div>
                                                    <div class="row gx-2 mt-2 justify-content-center d-none"
                                                        id="attachment_input_link">
                                                        <div class="col-3 col-lg-3 text-end">
                                                            <label for="attachment_link"
                                                                class="form-label d-inline-block text-secondary">{{ 'Link' }}</label>
                                                        </div>
                                                        <div class="col-8 col-lg-8">
                                                            <input type="text" name="attachment_link"
                                                                id="attachment_link"
                                                                class="form-control form-control-sm"
                                                                value="{{ old('attachment_link') }}">
                                                            <x-input-error :messages="$errors->get('dashboard_text')" class="mt-1" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer p-1">
                                                    <button type="submit"
                                                        class="btn btn-sm btn-primary">{{ 'Set' }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="row g-2 px-2 pb-2">
                                <div class="col-6">
                                    <h6 class="text-dark ps-2 d-flex"><i
                                            class="bi bi-file-earmark-text border-end border-primary me-2 pe-2"></i>{{ 'Documents' }}
                                    </h6>
                                    <div class="scroll-container scroll-container-lg">
                                        <div class="row gx-1">
                                            <div class="col-{{ $auth_user->roles_id == 1 ? '10' : '12' }}">
                                                <div class="list-group rounded-0">
                                                    <?php $document_list = []; ?>
                                                    @foreach ($attachment_list as $attachment)
                                                        @if ($attachment->type == 0)
                                                            <a href="/storage/document/attachment/{{ $attachment->document }}"
                                                                class="list-group-item list-group-item-action list-group-item-light border-top border-0 p-1"
                                                                download>
                                                                <div class="scroll-x-hidden">
                                                                    <span
                                                                        class="text-decoration-none text-nowrap">{{ $attachment->title }}</span>
                                                                </div>
                                                            </a>
                                                            <?php $document_list[] = ['id' => $attachment->id, 'title' => $attachment->title]; ?>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="col-2 {{ $auth_user->roles_id !== 1 ? 'd-none' : '' }}">
                                                <div class="list-group rounded-0">
                                                    @if ($auth_user->roles_id == 1)
                                                        @foreach ($document_list as $document)
                                                            <button type="button"
                                                                class="list-group-item list-group-item-action list-group-item-dark border-top border-0 border-dark-subtle p-1"
                                                                onclick="confirmation('{{ route('attachment.remove', ['id' => $document['id']]) }}', 'Are you sure want to remove {{ $document['title'] }} from attachment?')">
                                                                <div class="d-flex">
                                                                    <i class="bi bi-trash3 mx-auto"></i>
                                                                </div>
                                                            </button>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h6 class="text-dark ps-2 d-flex"><i
                                            class="bi bi-link-45deg border-end border-primary me-2 pe-2"></i>{{ 'Links' }}
                                    </h6>
                                    <div class="scroll-container scroll-container-lg">
                                        <div class="row gx-1">
                                            <div class="col-{{ $auth_user->roles_id == 1 ? '10' : '12' }}">
                                                <?php $link_list = []; ?>
                                                <div class="list-group rounded-0 ">
                                                    @foreach ($attachment_list as $attachment)
                                                        @if ($attachment->type == 1)
                                                            <a href="{{ $attachment->link }}" target="_blank"
                                                                class="list-group-item list-group-item-action list-group-item-light border-top border-0 p-1"
                                                                download>
                                                                <div class="scroll-x-hidden">
                                                                    <span
                                                                        class="text-decoration-none text-nowrap">{{ $attachment->title }}</span>
                                                                </div>
                                                            </a>
                                                            <?php $link_list[] = ['id' => $attachment->id, 'title' => $attachment->title]; ?>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="col-2 {{ $auth_user->roles_id !== 1 ? 'd-none' : '' }}">
                                                <div class="list-group rounded-0">
                                                    @if ($auth_user->roles_id == 1)
                                                        @foreach ($link_list as $link)
                                                            <button type="button"
                                                                class="list-group-item list-group-item-action list-group-item-dark border-top border-0 border-dark-subtle p-1"
                                                                onclick="confirmation('{{ route('attachment.remove', ['id' => $link['id']]) }}', 'Are you sure want to remove {{ $link['title'] }} from attachment?')">
                                                                <div class="d-flex">
                                                                    <i class="bi bi-trash3 mx-auto"></i>
                                                                </div>
                                                            </button>
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
            </div>
            <div class="col-12 col-lg-5">
                <div class="row mt-4 h-100">
                    <div class="col-12 h-100">
                        <div class="card bg-secondary bg-opacity-25 w-100">
                            <div class="w-100 rounded-top bg-white">
                                <h4 class="fst-italic bg-light bg-opacity-75 rounded-top p-2 d-flex mb-0">
                                    <span
                                        class="mx-auto text-primary-emphasis bg-white w-100 rounded text-center p-1"><i
                                            class="bi bi-send-fill me-2 pe-2 border-end border-2 border-secondary text-primary"></i>{{ 'SEEO POST' }}</span>
                                    <button class="btn btn-sm btn-light d-lg-none d-block" onclick="expand()"><i
                                            class="bi bi-arrows-expand" id="expandTrigger"></i></button>
                                </h4>
                            </div>
                            <div class="d-flex">
                                <div class="scroll-container-lg-3 scroll-container-3 px-2 mt-3 bg-opacity-0"
                                    style="min-height: 120px;" id="expandContainer">
                                    <div class="row g-3 pb-3">
                                        @foreach ($post_list as $post)
                                            <div class="col-lg-6 col-12 ">
                                                <div class="card">
                                                    <h6 class="mb-0 d-flex mt-2">
                                                        <div
                                                            class="d-flex border-bottom border-secondary-subtle mx-2 w-100 pb-1">
                                                            @if (!$post->anonymus)
                                                                <a href="{{ !$post->anonymus ? route('profile.edit', ['id' => $post->user_id]) : '' }}"
                                                                    class="text-decoration-none m-0 d-flex">
                                                            @endif
                                                            <img src="/storage/images/profile/{{ $post->anonymus ? 'example.png' : $post->user->profile_image }}"
                                                                alt="image" class="rounded-circle me-2 my-auto"
                                                                style="width: 1.5rem; height: 1.5rem;">
                                                            <span
                                                                class="my-auto text-dark">{{ $post->anonymus ? 'Anonymus' : $post->user->name }}</span>
                                                            @if (!$post->anonymus)
                                                                </a>
                                                            @endif
                                                            <button class="btn btn-sm btn-light ms-auto"
                                                                onclick="confirmation('{{ route('post.remove', ['id' => $post->id]) }}', 'Are you sure want to remove post from {{ $post->user_id ? $post->user->name : 'anonymus' }} ?')"><i
                                                                    class="bi bi-trash3"></i></button>
                                                        </div>
                                                    </h6>
                                                    <p class="px-2 mb-1" style="text-align: justify;">
                                                        {{ $post->text }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if ($post_list->count() == 0)
                                            <div class="col-12">
                                                <div class="d-flex">
                                                    <span
                                                        class="text-white text-opacity-50 fs-1 bg-opacity-0 mx-auto"><i
                                                            class="bi bi-send-slash me-2 pe-2 border-end border-white border-2 border-opacity-50"></i>{{ 'Oh No!' }}</span>
                                                </div>
                                                <div class="d-flex">
                                                    <span
                                                        class="fs-5 text-white text-opacity-50 mx-auto">{{ 'Let`s add a new post.' }}</span>
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
        </div>
    </div>
    </div>

    {{-- FAB --}}
    <button class="fab btn btn-primary p-2" data-bs-toggle="modal" data-bs-target="#addPost">
        <i class="bi bi-send-plus fs-3"></i>
    </button>

    <!-- Add Post Modal -->
    <div class="modal fade" id="addPost" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered px-3 px-lg-0">
            <div class="modal-content shadow mt-5">
                <div class="modal-header py-1 ps-3 pe-2">
                    <span class="modal-title fs-5 text-primary-emphasis">
                        <i class="bi bi-send-plus border-secondary border-end me-2 pe-2"></i>
                        {{ 'New Post' }}
                    </span>
                    <button type="button" class="btn btn-sm ms-auto" data-bs-dismiss="modal" aria-label="Close"><i
                            class="bi bi-x-lg"></i></button>
                </div>
                <form method="post" action="{{ route('post.add') }}">
                    @csrf
                    @method('put')
                    <div class="modal-body bg-light">
                        <div class="row justify-content-center mt-2 gx-2">
                            <div class="col-12 col-lg-10">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                        name="post_username" id="post_username">
                                    <label class="form-check-label" for="post_username">{{ 'Anonymus' }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center mt-2 gx-2">
                            <div class="col-12 col-lg-10">
                                <textarea class="form-control form-control-sm" rows="4" name="post_text" id="post_text" required>{{ old('post_text') }}</textarea>
                                <x-input-error :messages="$errors->get('post_text')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer p-1">
                        <button type="submit" class="btn btn-sm btn-primary">{{ 'Post' }} <i
                                class="bi bi-send ms-2 ps-2 border-start border-light"></i> </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const route_remove_billboard = @json(route('billboard.remove'));
        const auth_user = @json($auth_user);

        function setInput(input_id) {
            const input = document.getElementById(input_id);
            if (input.hasAttribute('required')) {
                input.removeAttribute('required');
            } else {
                input.setAttribute('required', 'true');
            }
        }

        function setBillboard(billboard) {
            if (auth_user.roles_id == 1) {
                const btn_delete = document.getElementById('btn_billboard_delete');
                btn_delete.onclick = function() {
                    confirmation(route_remove_billboard + '/' + billboard.id, 'Are you sure want to delete ' + billboard
                        .title + ' Billboard?', 'setBillboardModal');
                }
            }
        }

        function setAttachmentInput(type) {
            const input_link = document.getElementById('attachment_input_link');
            const input_document = document.getElementById('attachment_input_document');
            if (type == 'link') {
                if (input_link.classList.contains('d-none')) {
                    input_link.classList.remove('d-none');
                }
                if (!input_document.classList.contains('d-none')) {
                    input_document.classList.add('d-none');
                }
            } else if (type == 'document') {
                if (input_document.classList.contains('d-none')) {
                    input_document.classList.remove('d-none');
                }
                if (!input_link.classList.contains('d-none')) {
                    input_link.classList.add('d-none');
                }
            }
        }

        function setBillboardInput(type) {
            const input_text = document.getElementById('attachment_input_text');
            const input_image = document.getElementById('attachment_input_image');
            if (type == 'text') {
                if (input_text.classList.contains('d-none')) {
                    input_text.classList.remove('d-none');
                } else {
                    input_text.classList.add('d-none');
                }
            } else if (type == 'image') {
                if (input_image.classList.contains('d-none')) {
                    input_image.classList.remove('d-none');
                } else {
                    input_image.classList.add('d-none');
                }
            }
        }

        function expand() {
            const trigger = document.getElementById('expandTrigger');
            const container = document.getElementById('expandContainer');

            if (trigger.classList.contains('bi-arrows-expand')) {
                trigger.classList.remove('bi-arrows-expand');
                trigger.classList.add('bi-arrows-collapse');
                container.classList.remove('scroll-container-3');
                container.classList.add('scroll-container-5');
                container.scrollIntoView();
            } else {
                trigger.classList.remove('bi-arrows-collapse');
                trigger.classList.add('bi-arrows-expand');
                container.classList.remove('scroll-container-5');
                container.classList.add('scroll-container-3');
            }

        }
    </script>
</x-app-layout>
