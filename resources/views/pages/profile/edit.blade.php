<?php
$auth_user = Auth::user();
?>
<x-app-layout>
    <x-slot name="header">
        {{ __('Profile') }}
    </x-slot>

    <div class="container px-4">
        <div class="row mt-4 gx-3">
            {{-- Task --}}
            <div class="col-12 {{ $is_authenticated ? 'col-lg-4' : 'col-lg-8' }} order-lg-1 order-2 mb-3">
                {{-- Log Book Program --}}
                <div class="row">
                    <div class="col-12">
                        <nav class="navbar rounded bg-white shadow p-2">
                            <div class="container d-block px-0 ">
                                <div class="row mb-2 px-3">
                                    <div class="col-12 border-bottom border-primary pb-1">
                                        <i class="bi bi-book-half fs-5 me-2"></i>
                                        <span class="text-primary-emphasis fs-5">{{ 'Log Book' }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 d-flex">
                                        <div
                                            class="scroll-container-horizontal scroll-container-horizontal-lg bg-light rounded p-2 d-flex">
                                            <?php $i = 1; ?>
                                            @foreach ($program_list as $program)
                                                <div class="card px-2 py-1 shadow-sm fw-normal me-2 {{ $i == 1 ? 'border-primary border-1' : '' }}"
                                                    id="program_item_{{ $i }}"
                                                    onclick="setProgram({{ $program }},{{ $i }});">
                                                    {{ $program->name }}
                                                </div>
                                                <?php $i++; ?>
                                            @endforeach
                                            @if ($program_list->count() == 0)
                                                <span
                                                    class="fw-light fst-italic">{{ ($auth_user->id == $profile->id ? 'You' : $profile->name) . ' are not listed in any program.' }}</span>
                                            @endif
                                        </div>
                                        @if ($is_authenticated)
                                            <button class="btn btn-sm btn-primary my-auto ms-2 me-1"
                                                data-bs-toggle="modal" data-bs-target="#addLogBookModal">
                                                <i class="bi bi-plus-lg"></i>
                                            </button>
                                            {{-- Modal --}}
                                            <div class="modal fade" id="addLogBookModal" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered px-3 px-lg-0">
                                                    <div class="modal-content shadow mt-5">
                                                        <div class="modal-header py-1 ps-3 pe-2">
                                                            <span class="modal-title fs-5 text-primary-emphasis">
                                                                <i
                                                                    class="bi bi-book border-secondary border-end me-2 pe-2"></i>
                                                                {{ 'Add Log' }}
                                                            </span>
                                                            <button type="button" class="btn btn-sm ms-auto"
                                                                data-bs-dismiss="modal" aria-label="Close"><i
                                                                    class="bi bi-x-lg"></i></button>
                                                        </div>
                                                        <div class="modal-body p-1 px-3">
                                                            <form method="post" id="formAddLogbook"
                                                                enctype="multipart/form-data"
                                                                action="{{ route('logbook.add', ['id' => $profile->id]) }}">
                                                                @csrf
                                                                @method('put')
                                                                <div class="row mt-2 justify-content-center gx-3">
                                                                    <div class="col-4 col-lg-3 text-end">
                                                                        <label for="add_logbook_program"
                                                                            class="form-label d-inline-block">{{ 'Program' }}</label>
                                                                    </div>
                                                                    <div class="col-8 col-lg-7">
                                                                        <input type="hidden" name="logbook_program_id"
                                                                            id="add_logbook_program_input"
                                                                            value="{{ $program_list->first()->id }}">
                                                                        <span
                                                                            id="add_logbook_program_span">{{ $program_list->first()->name }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-2 justify-content-center gx-3">
                                                                    <div class="col-4 col-lg-3 text-end">
                                                                        <label for="add_logbook_image"
                                                                            class="form-label d-inline-block">{{ 'Image' }}</label>
                                                                    </div>
                                                                    <div class="col-8 col-lg-7">
                                                                        <input type="file"
                                                                            class="form-control form-control-sm"
                                                                            name="logbook_image" id="add_logbook_image"
                                                                            value="{{ old('logbook_image') }}"
                                                                            required>
                                                                        <x-input-error :messages="$errors->get('logbook_image')"
                                                                            class="mt-2" />
                                                                    </div>
                                                                </div>
                                                                <div class="row justify-content-center my-2 gx-3">
                                                                    <div class="col-4 col-lg-3 text-end">
                                                                        <label for="add_logbook_description"
                                                                            class="form-label d-inline-block">{{ 'Description' }}</label>
                                                                    </div>
                                                                    <div class="col-8 col-lg-7">
                                                                        <textarea class="form-control form-control-sm" rows="4" name="logbook_description" id="add_logbook_description"
                                                                            required required>
                                                                        </textarea>
                                                                        <x-input-error :messages="$errors->get('logbook_description')"
                                                                            class="mt-2" />
                                                                    </div>
                                                                </div>
                                                                <div class="row justify-content-center">
                                                                    <div class="col-12 col-lg-10">
                                                                        <button class="btn btn-sm btn-primary w-100"
                                                                            type="submit">
                                                                            {{ 'Submit Log' }}
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
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
                {{-- Log Book List --}}
                <div class="row mt-3">
                    <div class="col-12">
                        <div id="log_container"
                            class="scroll-container-2 scroll-container-lg-2 bg-secondary bg-opacity-25 px-2 pt-2 rounded">
                            <?php $i = 1;
                            $program_id = $program_list->first() ? $program_list->first()->id : 0;
                            $active_log = $logbook->where('program_id', '=', $program_id);
                            ?>
                            <div class="row gx-2">
                                @foreach ($active_log as $log)
                                    <div class="col-12 {{ $is_authenticated ? 'col-lg-12' : 'col-lg-6' }}">
                                        <div class="card card-bg-hover mb-2 shadow p-2">
                                            <div class="row gx-2">
                                                <div class="col-lg-3 col-4 position-relative">
                                                    <img src="/storage/images/log/{{ $log->program->name }}/{{ $log->image }}"
                                                        alt="image" class="rounded" data-bs-toggle="modal"
                                                        data-bs-target="#logImageModal"
                                                        onclick="setLogImage('{{ $log->program->name }}','{{ $log->image }}')"
                                                        style="object-fit: contain; max-width: 100%;">
                                                    <span
                                                        class="border-white bg-primary rounded-circle position-absolute top-0 start-0 text-white px-2">
                                                        {{ $i++ }}
                                                    </span>
                                                </div>
                                                <div class="col-lg-9 col-8">
                                                    <div class="row">
                                                        <div class="col-12 d-flex">
                                                            <span class="text-secondary">
                                                                {{-- This is temporary property to hold formated date value --}}
                                                                {{ $log->program->budget }}
                                                            </span>
                                                            @if ($is_authenticated)
                                                                <button class="btn btn-sm btn-secondary ms-auto"
                                                                    onclick="confirmation('{{ route('logbook.delete', ['id' => $log->id]) }}','Are you sure want to delete this log?')">
                                                                    <i class="bi bi-trash3"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <span class="fw-light">{{ $log->title }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if ($active_log->count() == 0)
                                <span class="bg-light rounded text-secondary fw-light pb-1 d-block px-2 mb-2">
                                    {{ ($is_authenticated ? 'You are ' : $profile->name . ' is ') . ' not have any log in this program.' }}
                                </span>
                            @endif
                            <!-- Log Image Modal -->
                            <div class="modal fade" id="logImageModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content shadow mx-3 mt-5">
                                        <div class="modal-header py-1 ps-3 pe-2">
                                            <span class="modal-title fs-5 text-primary-emphasis">
                                                <i class="bi bi-image border-secondary border-end me-2 pe-2"></i>
                                                {{ 'Log Image' }}
                                            </span>
                                            <button type="button" class="btn btn-sm ms-auto" data-bs-dismiss="modal"
                                                aria-label="Close"><i class="bi bi-x-lg"></i></button>
                                        </div>
                                        <div class="modal-body bg-light p-1 px-3">
                                            <div class="row justify-content-center mt-2">
                                                <div class="col-12 d-flex">
                                                    <img src="" alt="image" class="rounded mx-auto"
                                                        style="width: 100%; height 100%;  object-fit: contain; max-height:320px;"
                                                        id="log_image">
                                                </div>
                                            </div>
                                            <div class="row justify-content-center">
                                                <div class="col-12 d-flex">
                                                    <a href="" target="blank" style="text-decoration-none"
                                                        class="mx-auto" id="log_download" download>
                                                        <button class="btn btn-sm btn-light">
                                                            <span class="fw-light" id="log_name"></span>
                                                            <i class="bi bi-download text-primary"></i>
                                                        </button>
                                                    </a>
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
            {{-- Profile --}}
            <div class="col-12 {{ $is_authenticated ? 'col-lg-8' : 'col-lg-4' }} order-lg-2 order-1 mb-3">
                <div class="card shadow">
                    <div class="row pb-3 gx-2 justify-content-center">
                        {{-- Profile Image --}}
                        <div class="col-12 {{ $is_authenticated ? 'col-lg-5' : 'col-lg-12' }}">
                            <div class="row justify-content-center">
                                <div class="col-11 {{ $is_authenticated ? 'col-lg-11' : 'col-lg-8' }}">
                                    <div class="card position-relative w-100" style="padding-bottom:100%">
                                        <img src="/storage/images/profile/{{ $profile->profile_image !== null ? $profile->profile_image : 'example.png' }}"
                                            alt="image"
                                            class="rounded-circle shadow mt-3 position-absolute top-0 start-0 w-100 h-100"
                                            style="object-fit: cover;">
                                    </div>
                                </div>
                            </div>
                            <h4 class="text-center mt-4 text-primary-emphasis mb-0"><span
                                    class="border-1 border-bottom border-primary pb-1">{{ $profile->name }}</span>
                            </h4>
                            <p class="text-center mt-2">{{ $profile->roles->name }}</p>
                            @if (!$is_authenticated)
                                <p class="text-center mb-0 text-nowrap"><i
                                        class="bi bi-envelope-at me-2 pe-2 border-end"></i><span
                                        class="scroll-x-hidden">{{ $profile->email }}</span></p>
                                <p class="text-center"><i class="bi bi-whatsapp me-2 pe-2 border-end"></i><span
                                        class="scroll-x-hidden">{{ $profile->phone }}</span></p>
                            @endif
                        </div>
                        {{-- Profile Detail --}}
                        @if ($is_authenticated)
                            <div class="col-12 col-lg-7 px-3">
                                {{-- Update Profile --}}
                                <div class="d-flex mt-0 mt-lg-3">
                                    <div class="border-top border-1 border-primary my-auto w-100"></div>
                                    <span
                                        class="text-secondary fst-italic fw-bold mx-2 text-nowrap">{{ 'Personal Information' }}</span>
                                    <div class="border-top border-1 border-primary my-auto w-100"></div>
                                </div>
                                <form method="post" id="formProfileUpdate" enctype="multipart/form-data"
                                    action="{{ route('profile.update') }}">
                                    @csrf
                                    @method('put')
                                </form>
                                <div class="row mt-2 justify-content-center gx-2">
                                    <div class="col-1 col-lg-1 text-end">
                                        <label for="profile_email"
                                            class="form-label d-inline-block text-secondary mb-0"><i
                                                class="bi bi-envelope-at"></i></label>
                                    </div>
                                    <div class="col-8 col-lg-9 scroll-x-hidden">
                                        <span class="text-nowrap">{{ $profile->email }}</span>
                                    </div>
                                </div>
                                <div class="row mt-2 justify-content-center gx-2">
                                    <div class="col-1 col-lg-1 d-flex">
                                        <label for="profile_phone"
                                            class="form-label d-inline-block  text-secondary my-auto ms-auto"><i
                                                class="bi bi-whatsapp"></i></label>
                                    </div>
                                    <div class="col-8 col-lg-9 d-flex">
                                        <input type="number" class="form-control form-control-sm d-inline-block "
                                            name="phone" id="profile_phone" value="{{ $auth_user->phone }}"
                                            placeholder="08xxxxxxxxxx" form="formProfileUpdate" required>
                                        <x-input-error :messages="$errors->get('phone')" />
                                    </div>
                                </div>
                                <div class="row mt-2 justify-content-center gx-2">
                                    <div class="col-1 col-lg-1 d-flex">
                                        <label for="profile_image"
                                            class="form-label d-inline-block  text-secondary my-auto ms-auto"><i
                                                class="bi bi-person-bounding-box"></i></label>
                                    </div>
                                    <div class="col-8 col-lg-9 d-flex">
                                        <input type="file" class="form-control form-control-sm "
                                            name="profile_image" id="profile_image" form="formProfileUpdate"
                                            placeholder="profile photo" value="{{ old('profile_image') }}">
                                        <x-input-error :messages="$errors->get('profile_image')" class="mt-2" />
                                    </div>
                                </div>
                                <div class="row mt-2 gx-3 justify-content-center">
                                    <div class="col-10 d-flex">
                                        <button class="btn btn-sm btn-primary w-100" type="submit"
                                            form="formProfileUpdate">
                                            {{ 'Update Profile' }}
                                        </button>
                                    </div>
                                </div>
                                {{-- Change Password --}}
                                <div class="d-flex mt-3">
                                    <div class="border-top border-1 border-primary my-auto w-100"></div>
                                    <span
                                        class="text-secondary fst-italic fw-bold mx-2 text-nowrap">{{ 'Change Password' }}</span>
                                    <div class="border-top border-1 border-primary my-auto w-100"></div>
                                </div>
                                <form method="post" id="formPasswordUpdate"
                                    action="{{ route('password.change') }}">
                                    @csrf
                                    @method('put')
                                    <div class="row justify-content-center mt-2 gx-2">
                                        <div class="col-1 col-lg-1 text-end scroll-x-hidden">
                                            <label for="old_password"
                                                class="form-label my-1 text-secondary text-nowrap"><i
                                                    class="bi bi-person-lock"></i></label>
                                        </div>
                                        <div class="col-8 col-lg-9">
                                            <div class="input-group input-group-sm">
                                                <input type="password" class="form-control form-control-sm"
                                                    form="formPasswordUpdate" name="old_password" id="old_password"
                                                    placeholder="old password" value="{{ old('old_password') }}"
                                                    autocomplete="password" required>
                                                <button type="button" class="btn bg-light text-secondary"
                                                    onclick="show_password('old_password','old_password_icon')">
                                                    <i class="bi bi-eye-slash-fill" id="old_password_icon"></i>
                                                </button>
                                            </div>
                                            <x-input-error :messages="$errors->get('old_password')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="row justify-content-center mt-2 gx-2">
                                        <div class="col-1 col-lg-1 text-end">
                                            <label for="new_password scroll-x-hidden"
                                                class="form-label my-1 text-secondary text-nowrap"><i
                                                    class="bi bi-key"></i></label>
                                        </div>
                                        <div class="col-8 col-lg-9">
                                            <div class="input-group input-group-sm">
                                                <input type="password" class="form-control form-control-sm"
                                                    form="formPasswordUpdate" name="password" id="new_password"
                                                    placeholder="new password" value="{{ old('password') }}"
                                                    required>
                                                <button type="button" class="btn bg-light text-secondary"
                                                    onclick="show_password('new_password','new_password_icon')">
                                                    <i class="bi bi-eye-slash-fill" id="new_password_icon"></i>
                                                </button>
                                            </div>
                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="row justify-content-center mt-2 gx-2">
                                        <div class="col-1 col-lg-1 text-end scroll-x-hidden">
                                            <label for="confirm_password"
                                                class="form-label my-1 text-secondary text-nowrap"><i
                                                    class="bi bi-key-fill"></i></label>
                                        </div>
                                        <div class="col-8 col-lg-9">
                                            <div class="input-group input-group-sm">
                                                <input type="password" class="form-control form-control-sm"
                                                    name="password_confirmation" id="confirm_password"
                                                    form="formPasswordUpdate" placeholder="confirm password"
                                                    value="{{ old('password_confirmation') }}" required>
                                                <button type="button" class="btn bg-light text-secondary"
                                                    onclick="show_password('confirm_password','confirm_password_icon')">
                                                    <i class="bi bi-eye-slash-fill" id="confirm_password_icon"></i>
                                                </button>
                                            </div>
                                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="row mt-2 gx-3 justify-content-center">
                                        <div class="col-10 d-flex">
                                            <button class="btn btn-sm btn-primary w-100" type="submit"
                                                form="formPasswordUpdate">
                                                {{ 'Change Password' }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        const logbook = @json($logbook);
        const is_authenticated = @json($is_authenticated);
        const delete_route = @json(route('logbook.delete'));
        const profile = @json($profile);
        var program_index = 1;

        function setLog(id) {
            const container = document.getElementById('log_container');
            const log_list = logbook.filter(log_item => log_item.program.id == id)
            container.innerHTML = '';
            for (let i = 0; i < log_list.length; i++) {
                const log = log_list[i];

                const card = document.createElement('div');
                card.setAttribute('class', 'card card-bg-hover mb-2 shadow p-2');

                const row = document.createElement('div');
                row.setAttribute('class', 'row gx-2');
                card.appendChild(row);

                const col1 = document.createElement('div');
                col1.setAttribute('class', 'col-lg-3 col-4 position-relative');
                row.appendChild(col1);

                const img = document.createElement('img');
                img.src = '/storage/images/log/' + log.program.name + '/' + log.image;
                img.alt = 'image';
                img.setAttribute('class', 'rounded');
                img.setAttribute('data-bs-toggle', 'modal');
                img.setAttribute('data-bs-target', '#logImageModal');
                img.setAttribute('style', 'object-fit: contain; max-width: 100%;');
                img.onclick = function() {
                    setLogImage(log.prrogram.name, log.image);
                }
                col1.appendChild(img);

                const span_index = document.createElement('span');
                span_index.setAttribute('class',
                    'border-white bg-primary rounded-circle position-absolute top-0 start-0 text-white px-2');
                span_index.innerHTML = i + 1;
                col1.appendChild(span_index);

                const col2 = document.createElement('div');
                col2.setAttribute('class', 'col-lg-9 col-8');
                row.appendChild(col2);

                const rowv1 = document.createElement('div');
                rowv1.setAttribute('class', 'row');
                col2.appendChild(rowv1);

                const col_title = document.createElement('div');
                col_title.setAttribute('class', 'col-12 d-flex');
                rowv1.appendChild(col_title);

                const span_title = document.createElement('span');
                span_title.setAttribute('class', 'text-secondary');
                span_title.innerHTML = log.program.budget;
                col_title.appendChild(span_title);

                if (is_authenticated) {
                    const btn_delete = document.createElement('button');
                    btn_delete.setAttribute('class', 'btn btn-sm btn-secondary ms-auto');
                    btn_delete.onclick = function() {
                        confirmation(delete_route + '/' + log.id, 'Are you sure want to delete this log?');
                    }
                    col_title.appendChild(btn_delete);

                    const icon_delete = document.createElement('i');
                    icon_delete.setAttribute('class', 'bi bi-trash3');
                    btn_delete.appendChild(icon_delete);
                }

                const rowv2 = document.createElement('div');
                rowv2.setAttribute('class', 'row');
                col2.appendChild(rowv2);

                const col_description = document.createElement('div');
                col_description.setAttribute('class', 'col-12');
                rowv2.appendChild(col_description);

                const span_description = document.createElement('span');
                span_description.setAttribute('class', 'fw-light');
                span_description.innerHTML = log.title;
                col_description.appendChild(span_description);

                container.appendChild(card);
            };

            if (log_list.length == 0) {
                const span_empty = document.createElement('span');
                span_empty.setAttribute('class', 'bg-light rounded text-secondary fw-light pb-1 d-block px-2 mb-2');
                span_empty.innerHTML = (is_authenticated ? 'You are ' : profile.name + ' is ') +
                    ' not have any log in this program.';
            }
        }

        function setProgram(program, index) {
            const program_input = document.getElementById('add_logbook_program_input');
            const program_span = document.getElementById('add_logbook_program_span');

            const prev_program = document.getElementById('program_item_' + program_index);
            prev_program.classList.remove('border-1', 'border-primary');
            program_index = index;

            const active_program = document.getElementById('program_item_' + index);
            active_program.classList.add('border-1', 'border-primary');
            program_input.value = program.id;

            program_span.innerHTML = program.name;
            setLog(program.id);
        }

        function setLogImage(folder, file) {
            const image = document.getElementById('log_image');
            const download = document.getElementById('log_download');
            const name = document.getElementById('log_name');

            image.setAttribute('src', '/storage/images/log/' + folder + '/' + file);
            download.setAttribute('href', '/storage/images/log/' + folder + '/' + file);
            name.innerHTML = file;
        }
    </script>
</x-app-layout>
