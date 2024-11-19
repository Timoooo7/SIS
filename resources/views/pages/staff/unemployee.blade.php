<x-app-layout>
    <?php $auth_user = Auth::user(); ?>
    <x-slot name="header">
        <a href="{{ route('role') }}" class="text-decoration-none text-primary-emphasis"><span
                class="fw-light">{{ 'Employee' }}</span></a>
        <i class="bi bi-chevron-compact-right me-2"></i>{{ __('Unemployee') }}
    </x-slot>

    <div class="container px-4">
        <div class="row">
            <div class="col-lg-6 mt-4">
                {{-- User Detail --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow p-3">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card border-0 border-bottom border-secondary rounded-0 rounded-top ">
                                        <span class="h4 text-dark mx-auto my-2">
                                            <i class="bi bi-person-vcard me-2"></i>
                                            <span id="unemployee_profile_name" class="">
                                                {{ 'Select Unemployee' }}
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row fs-6">
                                <div class="col-12">
                                    <div class="row justify-content-center mt-3">
                                        <div class="col-4 col-lg-4 d-flex">
                                            <span class="ms-auto fw-light">{{ 'Email' }}</span>
                                        </div>
                                        <div class="col-8 col-lg-6">
                                            <div class="span" id="unemployee_profile_email">{{ 'user@mail.com' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center mt-3">
                                        <div class="col-4 col-lg-4 d-flex">
                                            <span class="ms-auto fw-light">{{ 'Phone' }}</span>
                                        </div>
                                        <div class="col-8 col-lg-6">
                                            <div class="span" id="unemployee_profile_phone">{{ 'xxxxxxxxxxx' }}
                                            </div>
                                        </div>
                                    </div>
                                    @if ($auth_user->roles_id == 1)
                                        <form method="post" id="formAddEmployee" action="{{ route('employee.add') }}">
                                            <input type="hidden" id="unemployee_id" name="user_id" value="">
                                            @csrf
                                            @method('put')
                                            <div class="row justify-content-center mt-3">
                                                <div class="col-4 col-lg-4 d-flex">
                                                    <span class="ms-auto fw-light">{{ 'Role' }}</span>
                                                </div>
                                                <div class="col-8 col-lg-6">
                                                    <select name="roles_id" class="form-select form-select-sm" required>
                                                        <option selected disabled>{{ 'Choose here' }}</option>
                                                        @foreach ($roles as $role)
                                                            <option value="{{ $role->id }}"
                                                                id="unemployee_role_{{ $role->id }}">
                                                                {{ $role->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row justify-content-center mt-3">
                                                <div class="col-lg-10">
                                                    <div class="btn-group btn-group-sm w-100">
                                                        <button form="formAddEmployee" type="submit"
                                                            class="btn btn-primary" disabled id="submitAdd">
                                                            {{ 'Add Employee' }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-4">
                {{-- User Filter --}}
                <div class="card shadow-sm p-3">
                    <div class="row border-primary border-bottom pb-2">
                        <div class="d-flex">
                            <span class="text-primary-emphasis ms-2 me-auto my-auto h4">
                                <i class="bi bi-people me-2"></i>
                                <span class="d-none d-lg-inline">{{ 'Unemployee ' }}</span>
                                {{ 'List' }}
                            </span>
                        </div>
                    </div>
                    {{-- Filter --}}
                    <div class="row justify-content-center">
                        <div class="col-12  ">
                            <nav class="navbar py-0 mt-2">
                                <form method="post" id="searchDepartmentForm" role="search"
                                    action="{{ route('unemployee.filter') }}">
                                    @csrf
                                    @method('put')
                                </form>
                                <form method="post" id="formNameFilter" action="{{ route('unemployee.filter') }}">
                                    @csrf
                                    @method('put')
                                    <?php
                                    $is_name = $filter['category'] == 'name';
                                    $name_order = $filter['order'] == 'desc' ? 'desc' : 'asc';
                                    $name_icon = $is_name && $name_order == 'asc' ? 'up' : 'down';
                                    $name_value = $is_name && $name_order == 'asc' ? 'desc' : 'asc';
                                    $name_button = $is_name && $is_name ? '' : '';
                                    ?>
                                    <input type="hidden" name="category" value="name">
                                </form>
                                <form method="post" id="formDateFilter" action="{{ route('unemployee.filter') }}">
                                    @csrf
                                    @method('put')
                                    <?php
                                    $is_department = $filter['category'] == 'created_at';
                                    $department_order = $filter['order'] == 'desc' ? 'desc' : 'asc';
                                    $department_icon = $is_department && $department_order == 'asc' ? 'up' : 'down';
                                    $department_value = $is_department && $department_order == 'asc' ? 'desc' : 'asc';
                                    $department_button = $is_department && $is_name ? '' : '';
                                    ?>
                                    <input type="hidden" name="category" value="created_at">
                                </form>
                                <div class="container d-block px-0 bg-body-tertiary rounded">
                                    <?php $keyword_focus = $filter['keyword'] ? 'autofocus' : ''; ?>
                                    <div class="row">
                                        <div class="input-group input-group-sm">
                                            {{-- Name Filter --}}
                                            <button type="button" form="formNameFilter"
                                                class="btn btn-sm rounded-0 rounded-start text-light bg-secondary">
                                                <i class="bi bi-funnel-fill"></i>
                                                <span
                                                    class="ms-1 ps-2 border-start border-light d-none d-md-inline">{{ 'Filter' }}</span>
                                            </button>
                                            <button type="submit" form="formNameFilter"
                                                class="btn btn-sm btn-outline-secondary border-0 {{ $name_button }} rounded-0 my-0"
                                                name="order" value="{{ $name_value }}">
                                                <span class="d-none d-lg-inline">{{ 'Name' }}</span>
                                                <i class="bi bi-person d-lg-none"></i>
                                                <i class="bi bi-sort-alpha-{{ $name_icon }}-alt"></i>
                                            </button>
                                            <button type="submit" form="formDateFilter"
                                                class="btn btn-sm btn-outline-secondary border-0 {{ $department_button }} rounded-0 my-0"
                                                name="order" value="{{ $department_value }}">
                                                <span class="d-none d-lg-inline">{{ 'Date' }}</span>
                                                <i class="bi bi-calendar-check d-lg-none"></i>
                                                <i class="bi bi-sort-numeric-{{ $department_icon }}"></i>
                                            </button>
                                            {{-- Search Filter --}}
                                            <input type="text" name="keyword" class="form-control"
                                                form="searchDepartmentForm" {{ $keyword_focus }}
                                                value="{{ $filter['keyword'] }}"
                                                placeholder="{{ ' Search by name' }}">
                                            <button class="btn btn-outline-secondary border-0" type="submit"
                                                form="searchDepartmentForm">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
                {{-- User List --}}
                <div class="scroll-container-2 scroll-container-lg-2 pt-2 mt-2 rounded bg-secondary bg-opacity-25">
                    @if ($unemployees->count() == 0)
                        <div class="row mb-2">
                            <div class="col">
                                <span class="ms-3 text-secondary">{{ 'No unemployee found.' }}</span>
                            </div>
                        </div>
                    @endif
                    <?php $i = 1; ?>
                    @foreach ($unemployees as $unemployee)
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="card py-1 mx-2 shadow-sm card-bg-hover text-dark"
                                    id="unemployee_card_{{ $unemployee->id }}"
                                    onclick="return set_unemployee({{ $unemployee }})">
                                    <span class="d-flex">
                                        <span
                                            class="mx-2 border-end border-secondary text-secondary fw-light pe-2">{{ $i }}</span>
                                        <span class="">{{ $unemployee->name }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <?php $i++; ?>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        var selected_unemployee_id;

        function set_unemployee(unemployee) {
            var unemployee_card = document.getElementById('unemployee_card_' + unemployee.id);
            var unemployee_name = document.getElementById('unemployee_profile_name');
            var unemployee_email = document.getElementById('unemployee_profile_email');
            var unemployee_phone = document.getElementById('unemployee_profile_phone');
            var unemployee_id = document.getElementById('unemployee_id');
            var submitAdd = document.getElementById('submitAdd');

            unemployee_name.innerHTML = unemployee.name;
            unemployee_email.innerHTML = unemployee.email;
            unemployee_phone.innerHTML = unemployee.phone;
            unemployee_id.value = unemployee.id;
            submitAdd.removeAttribute('disabled');

            if (selected_unemployee_id) {
                var selected_unemployee_card = document.getElementById('unemployee_card_' + selected_unemployee_id);
                selected_unemployee_card.classList.remove('shadow', 'fw-normal');
                selected_unemployee_card.classList.add('shadow-sm', 'fw-light');
            }

            unemployee_card.classList.remove('shadow-sm', 'fw-light');
            unemployee_card.classList.add('shadow', 'fw-normal');
            selected_unemployee_id = unemployee.id;
        }
    </script>
</x-app-layout>
