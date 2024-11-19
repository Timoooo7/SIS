<x-app-layout>
    <?php $auth_user = Auth::user(); ?>
    <x-slot name="header">
        {{ 'Departments' }}
    </x-slot>

    <div class="container">
        {{-- Department Dashboard --}}
        <div class="row">
            <div class="col-12">
                <div class="card border shadow p-3 mt-4 rounded mx-2">
                    {{-- Header --}}
                    <div class="d-flex border-primary border-bottom pb-2">
                        <span class="text-primary-emphasis ms-2 me-auto my-auto h4">
                            <i class="bi bi-houses me-2 "></i>
                            {{ __('Department List') }}
                        </span>
                        @if ($auth_user->roles->id == 1)
                            <!-- Button trigger Add Department Modal -->
                            <button class="btn btn-sm btn-primary bg-opacity-50 shadow-sm d-inline-block ms-2 fw-light"
                                data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                                <i class="bi bi-house-add"></i>
                                <span class="border-start border-light ms-1 ps-2 d-none d-md-inline-block">
                                    {{ 'New Department' }}
                                </span>
                            </button>
                            <!-- Add Department Modal -->
                            <div class="modal fade" id="addDepartmentModal" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content shadow mx-3">
                                        <div class="modal-header py-1 ps-3 pe-2">
                                            <span class="modal-title fs-5 text-primary-emphasis"
                                                id="exampleModalLabel"><i
                                                    class="bi bi-house-add border-secondary border-end me-2 pe-2"></i>
                                                New Department
                                            </span>
                                            <button type="button" class="btn btn-sm ms-auto" data-bs-dismiss="modal"
                                                aria-label="Close"><i class="bi bi-x-lg"></i></button>
                                        </div>
                                        <form method="post" action="{{ route('department.add') }}">
                                            @csrf
                                            @method('put')
                                            <div class="modal-body bg-light">
                                                <div class="row justify-content-center">
                                                    <div class="col-4 col-lg-3">
                                                        <label for="add_department_name"
                                                            class="form-label d-inline-block">Name</label>
                                                    </div>
                                                    <div class="col-8 col-lg-7">
                                                        <input type="text"
                                                            class="form-control form-control-sm d-inline-block"
                                                            name="name" id="add_department_name" required>
                                                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                                    </div>
                                                </div>
                                                <div class="row mt-2 justify-content-center">
                                                    <div class="col-4 col-lg-3">
                                                        <label for="add_department_manager"
                                                            class="form-label d-inline-block">Manager</label>
                                                    </div>
                                                    <div class="col-8 col-lg-7">
                                                        <select name="manager_id" id="add_department_manager"
                                                            class="form-select form-select-sm"
                                                            aria-label="Default select example" required>
                                                            <option selected value="" disabled>Choose here
                                                            </option>
                                                            <?php $i = 0; ?>
                                                            @foreach ($not_manager_list as $user)
                                                                {{-- User can not be manager more than one departments --}}
                                                                @if (!$user->manager)
                                                                    <option value="{{ $user->id }}">
                                                                        {{ $user->name }}
                                                                    </option>
                                                                    <?php $i++; ?>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        @if ($i == 0)
                                                            <span class="fst-italic text-secondary">
                                                                {{ 'All employee already belongs to a Department. Please add new employee to be a Manager.' }}
                                                            </span>
                                                        @endif
                                                        <x-input-error :messages="$errors->get('manager_id')" class="mt-2" />
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
                    </div>
                    {{-- Department List --}}
                    <div class="row mx-1 mt-2 bg-light">
                        <div class="scroll-container-horizontal-lg scroll-container-horizontal mx-auto">
                            <?php $department_number = 1; ?>
                            @foreach ($departments as $department)
                                <?php
                                $is_department_active = $active_department->name == $department->name;
                                $department_card_class = $is_department_active ? 'shadow' : 'shadow-sm';
                                $department_divider_class = $is_department_active ? 'border-2' : 'border-1';
                                $department_text_class = $is_department_active ? 'text-dark' : 'text-secondary fw-light';
                                $department_number_class = $is_department_active ? 'fw-normal' : 'fw-light';
                                $department_text_secondary_class = $is_department_active ? 'text-dark' : 'text-secondary';
                                ?>
                                {{-- Department List Item --}}
                                <div class="d-inline-block me-2 my-2">
                                    <a href="{{ route('department', ['array_id' => $department_number - 1]) }}"
                                        class="text-decoration-none">
                                        <div class="card rounded d-block card-bg-hover {{ $department_card_class }}">
                                            <div class="row py-2">
                                                <div
                                                    class="col-auto ms-3 fw-light {{ $department_number_class }} pt-1">
                                                    <span class="fs-2 d-none d-lg-inline">
                                                        {{ $department_number }}
                                                    </span>
                                                    <span class="fs-6 d-lg-none">
                                                        {{ $department_number }}
                                                    </span>
                                                </div>
                                                <div
                                                    class="col-auto me-3 border-start border-secondary {{ $department_divider_class }}">
                                                    <p class="m-0 {{ $department_text_class }} fs-5">
                                                        {{ $department->name }}
                                                        <span class="d-none d-md-inline">{{ ' Department' }}</span>
                                                    </p>
                                                    <p
                                                        class="m-0 {{ $department_text_secondary_class }} d-none d-lg-block fw-light">
                                                        <i class="bi bi-person-gear me-2"></i>
                                                        {{ $department->manager->name }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <?php $department_number++; ?>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Department --}}
        <div class="row mt-4">
            <div class="col-12">
                {{-- CEO Feature Modal --}}
                @if ($auth_user->roles->id == 1 && $active_department)
                    <!-- Edit Department Modal -->
                    <div class="modal fade" id="editDepartment" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content shadow mx-3">
                                <div class="modal-header py-1 ps-3 pe-2">
                                    <span class="modal-title fs-6 text-primary-emphasis" id="exampleModalLabel">
                                        <i class="bi bi-house border-secondary border-end me-2 pe-2"></i>
                                        {{ 'Update ' . $active_department->name }}
                                        <span class="d-none d-lg-inline">Department</span>
                                    </span>
                                    <button type="button" class="btn btn-sm ms-auto" data-bs-dismiss="modal"
                                        aria-label="Close"><i class="bi bi-x-lg"></i></button>
                                </div>
                                <form method="post" action="{{ route('department.update', $active_department->id) }}">
                                    @csrf
                                    @method('put')
                                    <div class="modal-body bg-light">
                                        <div class="row justify-content-center mt-0">
                                            <div class="col-5 col-lg-4">
                                                <label for="edit_department_name" class="form-label text-sm">New
                                                    Name</label>
                                            </div>
                                            <div class="col-7 col-lg-6">
                                                <input id="edit_department_name" class="form-control form-control-sm"
                                                    type="text" required name="name" required
                                                    value="{{ $active_department->name }}"
                                                    placeholder="current: {{ $active_department->name }}" />
                                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="row justify-content-center mt-2 ">
                                            <div class="col-5 col-lg-4 ">
                                                <label for="edit_department_manager"
                                                    class="form-label d-inline-block">New
                                                    Manager</label>
                                            </div>
                                            <div class="col-7 col-lg-6">
                                                <select name="manager_id" id="edit_department_manager"
                                                    class="form-select form-select-sm bg-opacity-100" required>
                                                    <option value="" class="text-secondary">Choose here
                                                    </option>
                                                    <?php $i = 0; ?>
                                                    @foreach ($not_manager_list as $user)
                                                        <?php $selected = $active_department->manager->id == $user->id ? 'selected' : ''; ?>
                                                        <option value="{{ $user->id }}" {{ $selected }}>
                                                            {{ $user->name }}
                                                        </option>
                                                        <?php $i++; ?>
                                                    @endforeach
                                                </select>
                                                @if ($i == 0)
                                                    <span class="fst-italic text-secondary">
                                                        {{ 'All employee already in other Department. Please add new employee to be a Manager.' }}
                                                    </span>
                                                @endif
                                                <x-input-error :messages="$errors->get('manager_id')" class="mt-2" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer p-1">
                                        <button type="submit" class="btn btn-sm btn-primary shadow">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Delete Department Modal -->
                    <div class="modal fade" id="delDepartment" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content shadow mx-3">
                                <div class="modal-header py-1 ps-3 pe-2">
                                    <span class="modal-title text-primary-emphasis fs-5" id="exampleModalLabel">
                                        <i class="bi bi-house border-secondary border-end me-2 pe-2"></i>
                                        {{ $active_department->name }}
                                        <span class="d-none d-lg-inline">Department</span>
                                    </span>
                                    <button type="button" class="btn btn-sm ms-auto" data-bs-dismiss="modal"
                                        aria-label="Close"><i class="bi bi-x-lg"></i></button>
                                </div>
                                <form method="post"
                                    action="{{ route('department.delete', $active_department->id) }}">
                                    @csrf
                                    @method('post')
                                    <div class="modal-body bg-light">
                                        <div class="row justify-content-center">
                                            <div class="col-12 col-lg-10">
                                                <p class="m-0" style="text-align: justify;">
                                                    {{ 'All program and progress in ' .
                                                        $active_department->name .
                                                        ' Department will be lost. Please make sure you have back up all the data needed.' }}
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
                                                    <input id="password" class="form-control" type="password"
                                                        name="password" required autocomplete="current-password" />
                                                    <button type="button" class="btn bg-light"
                                                        onclick="show_password('password','password_icon_del')">
                                                        <i class="bi bi-eye-slash-fill" id="password_icon_del"></i>
                                                    </button>
                                                </div>
                                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer p-1">
                                        <button type="submit" class="btn btn-sm btn-danger shadow">Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="card bg-white  border-bottom border-primary shadow-sm mx-2 p-2">
                    <div class="d-flex">
                        <span class="text-primary-emphasis h4 m-0">
                            <i class="bi bi-house border-secondary border-end me-2 pe-2"></i>
                            {{ $active_department ? $active_department->name : 'Not found' }}
                        </span>
                        {{-- CEO Feature Trigger --}}
                        @if ($auth_user->roles->id == 1)
                            {{-- Edit Department Modal Trigger --}}
                            <button class="btn btn-light btn-sm ms-auto py-0 px-2" data-bs-toggle="modal"
                                data-bs-target="#editDepartment">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            {{-- Delete Department Modal Trigger --}}
                            <button class="btn btn-secondary btn-sm ms-2 py-0 px-2" data-bs-toggle="modal"
                                data-bs-target="#delDepartment">
                                <i class="bi bi-trash3"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @if ($active_department)
            <div class="row mb-5">
                <div class="col-12">
                    <div class="row gx-2">
                        {{-- Department Detail --}}
                        <div class="col-12 col-lg-3 mt-3">
                            <div class="card mx-2 shadow-sm border-0 bg-white ">
                                <div class="row">
                                    <div class="col-12 d-flex">
                                        <span class="border-secondary border-bottom my-auto ms-2"
                                            style="width: 100%; height:1px;"></span>
                                        <span
                                            class="text-primary-emphasis fw-bold fst-italic mx-2 my-auto">{{ 'Detail' }}</span>
                                        <span class="border-secondary border-bottom my-auto me-2"
                                            style="width: 100%; height:1px;"></span>
                                    </div>
                                </div>
                                <div class="d-md-block {{ $active_department->program->count() > 3 ? 'collapse show' : '' }}"
                                    id="collapseDetail">
                                    <div class="row text-nowrap gx-2">
                                        <div class="col-5 text-end">
                                            <span class="text-secondary">
                                                {{ 'Manager : ' }}
                                            </span>
                                        </div>
                                        <div class="col-7 d-flex">
                                            <span class="text-dark fw-normal me-2 scroll-x-hidden">
                                                <span class="text-nowrap">
                                                    {{ $active_department->manager->name }}
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12 d-flex">
                                            <span class="border-secondary border-bottom my-auto ms-2"
                                                style="width: 100%; height:1px;"></span>
                                            <span
                                                class="text-primary-emphasis fw-bold fst-italic mx-2 my-auto text-nowrap">{{ 'Cash Flow' }}</span>
                                            <span class="border-secondary border-bottom my-auto me-2"
                                                style="width: 100%; height:1px;"></span>
                                        </div>
                                    </div>
                                    <div class="row text-nowrap gx-2">
                                        <div class="col-5 text-end">
                                            <span class="text-secondary">
                                                {{ 'Disbursement : ' }}
                                            </span>
                                        </div>
                                        <div class="col-7">
                                            <span class="text-dark fw-normal">
                                                {{ format_currency($active_department->disbursement) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row text-nowrap gx-2">
                                        <div class="col-5 text-end">
                                            <span class="text-secondary">
                                                {{ 'Expense : ' }}
                                            </span>
                                        </div>
                                        <div class="col-7">
                                            <span class="text-dark fw-normal">
                                                {{ format_currency($active_department->expense) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row text-nowrap gx-2">
                                        <div class="col-5 text-end">
                                            <span class="text-secondary">
                                                {{ 'Staff : ' }}
                                            </span>
                                        </div>
                                        <div class="col-7">
                                            <span class="text-dark fw-normal">
                                                {{ $staff_list->count() . ' staff' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-9 mt-3 ">
                            <div class="row px-2">
                                {{-- Staff List --}}
                                <div class="col-12 col-lg-6">
                                    <div class="card bg-white shadow-sm">
                                        <div class="row my-1">
                                            <div class="col-12 d-flex">
                                                <span
                                                    class="text-dark fw-bold text-primary-emphasis ms-2 text-nowrap my-auto">{{ 'Staff List' }}</span>
                                                {{-- Manager Feature --}}
                                                @if ($auth_user->id == $active_department->manager_id)
                                                    <!-- Add Staff Trigger -->
                                                    <button
                                                        class="btn btn-sm ms-auto btn-primary text-nowrap me-1 my-0"
                                                        data-bs-toggle="modal" data-bs-target="#addStaffModal">
                                                        <span class="bi bi-plus-lg "></span>
                                                    </button>

                                                    <!-- Add Staff Modal -->
                                                    <div class="modal fade" id="addStaffModal" tabindex="-1"
                                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content shadow mx-3">
                                                                <div class="modal-header py-1 ps-3 pe-2">
                                                                    <span
                                                                        class="modal-title fs-5 text-primary-emphasis"
                                                                        id="exampleModalLabel"><i
                                                                            class="bi bi-person-add border-light border-end me-2 pe-2"></i>
                                                                        {{ 'New Staff' }}
                                                                    </span>
                                                                    <button type="button" class="btn btn-sm ms-auto"
                                                                        data-bs-dismiss="modal" aria-label="Close"><i
                                                                            class="bi bi-x-lg"></i></button>
                                                                </div>
                                                                <form method="post"
                                                                    action="{{ route('department.staff.add', ['id' => $active_department->id]) }}">
                                                                    @csrf
                                                                    @method('put')
                                                                    <div class="modal-body bg-light">
                                                                        <div class="row mt-2 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_staff"
                                                                                    class="form-label d-inline-block">{{ 'Staff' }}</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <select name="staff_id" id="add_staff"
                                                                                    class="form-select form-select-sm"
                                                                                    aria-label="Default select example"
                                                                                    required>
                                                                                    <option selected value=""
                                                                                        disabled>
                                                                                        {{ 'Choose here' }}
                                                                                    </option>
                                                                                    @foreach ($not_manager_list as $user)
                                                                                        {{-- User can not be pic more than one program --}}
                                                                                        @if ($user->department_id == null || $user->department_id == 0)
                                                                                            <option
                                                                                                value="{{ $user->id }}">
                                                                                                {{ $user->name }}
                                                                                            </option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </select>
                                                                                <x-input-error :messages="$errors->get('staff_id')"
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
                                            </div>
                                        </div>
                                    </div>
                                    {{-- List Item --}}
                                    <div class="scroll-container scroll-container-lg mt-2 pt-2 rounded bg-secondary mb-3"
                                        id="staff_list_container" style="--bs-bg-opacity:.2">
                                        @foreach ($staff_list as $staff)
                                            <div class="card card-bg-hover shadow-sm py-1 mx-2 d-flex mb-2">
                                                <div class="row gx-2 px-1">
                                                    <div class="col-1 col-lg-1 d-flex">
                                                        <div class="card position-relative shadow-sm w-100 rounded-circle border-primary border-2 my-auto"
                                                            style="padding-bottom:100%">
                                                            <img src="/storage/images/profile/{{ $staff->profile_image !== null ? $staff->profile_image : 'example.png' }}"
                                                                alt="image"
                                                                class="rounded-circle position-absolute top-0 start-0 w-100 h-100"
                                                                style="object-fit: cover;">
                                                        </div>
                                                    </div>
                                                    <div class="col-11 col-lg-11 d-flex">
                                                        <a href="{{ route('profile.edit', ['id' => $staff->id]) }}"
                                                            class="text-decoration-none text-dark text-nowrap scroll-x-hidden my-auto">
                                                            <span class="fw-normal">{{ $staff->name }}</span>
                                                        </a>
                                                        <button class="btn btn-sm btn-secondary ms-auto"
                                                            type="button"
                                                            onclick="confirmation('{{ route('department.staff.remove', ['id' => $staff->id]) }}', 'Are you sure want to remove {{ $staff->name }} from {{ $active_department->name }} Department Staff?')">
                                                            <i class="bi bi-trash3"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                {{-- Program List --}}
                                <div class="col-12 col-lg-6">
                                    <div class="card bg-white shadow-sm">
                                        <div class="row my-1">
                                            <div class="col-12 d-flex">
                                                <span
                                                    class="text-dark fw-bold text-primary-emphasis ms-2 text-nowrap my-auto">{{ 'Program List' }}</span>
                                                {{-- Manager Feature --}}
                                                @if ($auth_user->id == $active_department->manager_id)
                                                    <!-- Add Program Trigger -->
                                                    <button
                                                        class="btn btn-sm ms-auto btn-primary text-nowrap me-1 my-0"
                                                        data-bs-toggle="modal" data-bs-target="#addProgramModal">
                                                        <span class="bi bi-plus-lg "></span>
                                                    </button>

                                                    <!-- Add Program Modal -->
                                                    <div class="modal fade" id="addProgramModal" tabindex="-1"
                                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content shadow mx-3">
                                                                <div class="modal-header py-1 ps-3 pe-2">
                                                                    <span
                                                                        class="modal-title fs-5 text-primary-emphasis"
                                                                        id="exampleModalLabel"><i
                                                                            class="bi bi-house-add border-light border-end me-2 pe-2"></i>
                                                                        {{ 'New Program' }}
                                                                    </span>
                                                                    <button type="button" class="btn btn-sm ms-auto"
                                                                        data-bs-dismiss="modal" aria-label="Close"><i
                                                                            class="bi bi-x-lg"></i></button>
                                                                </div>
                                                                <form method="post"
                                                                    action="{{ route('program.add', ['id' => $active_department->id]) }}">
                                                                    @csrf
                                                                    @method('put')
                                                                    <div class="modal-body bg-light">
                                                                        <div class="row justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_program_name"
                                                                                    class="form-label d-inline-block">{{ 'Name' }}</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="text"
                                                                                    class="form-control form-control-sm d-inline-block"
                                                                                    name="name"
                                                                                    id="add_program_name" required>
                                                                                <x-input-error :messages="$errors->get('name')"
                                                                                    class="mt-2" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_program_pic"
                                                                                    class="form-label d-inline-block">{{ 'In Charge' }}</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <select name="pic_id"
                                                                                    id="add_program_pic"
                                                                                    class="form-select form-select-sm"
                                                                                    aria-label="Default select example"
                                                                                    required>
                                                                                    <option selected value=""
                                                                                        disabled>{{ 'Choose here' }}
                                                                                    </option>
                                                                                    <?php $i = 0; ?>
                                                                                    @foreach ($staff_list as $staff)
                                                                                        {{-- User can not be pic more than one program --}}
                                                                                        @if (!$staff->program)
                                                                                            <option
                                                                                                value="{{ $staff->id }}">
                                                                                                {{ $staff->name }}
                                                                                            </option>
                                                                                            <?php $i++; ?>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </select>
                                                                                @if ($i == 0)
                                                                                    <span
                                                                                        class="fst-italic text-secondary">
                                                                                        {{ 'All of your staff are In Charge of a program. Please add new staff to your department' }}
                                                                                    </span>
                                                                                @endif
                                                                                <x-input-error :messages="$errors->get('pic_id')"
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
                                            </div>
                                        </div>
                                    </div>
                                    {{-- List Item --}}
                                    <div class="scroll-container scroll-container-lg mt-2 pt-2 rounded bg-secondary"
                                        id="program_list_container" style="--bs-bg-opacity:.2">
                                        @foreach ($active_department->program as $program)
                                            <div class="row mb-2">
                                                <div class="col-12">
                                                    <div class="card card-bg-hover shadow-sm py-1 mx-2">
                                                        <a href="{{ route('program', ['id' => $program->id]) }}"
                                                            class="text-decoration-none text-dark d-flex text-nowrap scroll-x-hidden mx-2">
                                                            <span
                                                                class="fw-normal me-auto">{{ $program->name }}</span>

                                                            <span
                                                                class="fw-light border-end border-start border-1 mx-2 px-2"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                data-bs-custom-class="custom-tooltip"
                                                                data-bs-title="Person In Charge of this program.">
                                                                <i class="bi bi-person-check"></i>
                                                                {{ $program->user->name }}
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
    <script>
        function collapse_toggle(trigger_id) {
            var trigger = document.getElementById(trigger_id);
            var program = document.getElementById('program_list_container');
            if (trigger.classList.contains('bi-chevron-up')) {
                trigger.classList.remove('bi-chevron-up');
                trigger.classList.add('bi-chevron-down');
                program.classList.remove('scroll-container');
                program.classList.add('scroll-container-2');
            } else {
                trigger.classList.remove('bi-chevron-down');
                trigger.classList.add('bi-chevron-up');
                program.classList.remove('scroll-container-2');
                program.classList.add('scroll-container');
            }
        }
    </script>
</x-app-layout>
