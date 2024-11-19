<x-app-layout>
    <?php $auth_user = Auth::user(); ?>
    <x-slot name="header">
        {{ __('Employee') }}
    </x-slot>

    <div class="container ">
        <div class="row gx-4 mt-4">
            {{-- Employee --}}
            <div class="col-lg-5 ">
                {{-- User Detail --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow p-2">
                            <div class="row">
                                <div class="col-12">
                                    <div
                                        class="card border-0 border-bottom border-secondary rounded-0 rounded-top d-flex">
                                        <a href="" id="profile_link" class="text-decoration-none mx-auto mb-2">
                                            <span class="h4 text-dark">
                                                <i class="bi bi-person-vcard me-2"></i>
                                                <span id="employee_profile_name" class="">
                                                    {{ 'Select Employee' }}
                                                </span>
                                                <i class="bi bi-link-45deg text-primary"></i>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row fs-6">
                                <div class="col-12">
                                    <div class="row gx-2 mt-2">
                                        <div class="col-4 col-lg-4 d-flex">
                                            <span class="ms-auto fw-light">{{ 'Email' }}</span>
                                        </div>
                                        <div class="col-8 scroll-x-hidden">
                                            <div class="span" id="employee_profile_email">{{ 'user@mail.com' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row gx-2 mt-2">
                                        <div class="col-4 col-lg-4 d-flex">
                                            <span class="ms-auto fw-light">{{ 'Phone' }}</span>
                                        </div>
                                        <div class="col-8 scroll-x-hidden text-nowrap">
                                            <div class="span" id="employee_profile_phone">{{ 'xxxxxxxxxxx' }}</div>
                                        </div>
                                    </div>
                                    @if ($auth_user->roles_id == 1)
                                        <form method="post" id="formUpdateRole" action="{{ route('role.update') }}">
                                            @csrf
                                            @method('put')
                                            <input type="hidden" id="employee_id" name="user_id" value="">
                                    @endif
                                    <div class="row gx-2 mt-2">
                                        <div class="col-4 col-lg-4 d-flex">
                                            <span class="ms-auto fw-light">{{ 'Department' }}</span>
                                        </div>
                                        <div class="col-8 scroll-x-hidden text-nowrap">

                                            <div class="span" id="employee_profile_department">
                                                {{ 'Department Name' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row gx-2 mt-2">
                                        <div class="col-4 col-lg-4 d-flex">
                                            <span class="ms-auto fw-light">{{ 'Role' }}</span>
                                        </div>
                                        <div class="col-8 scroll-x-hidden text-nowrap">
                                            @if ($auth_user->roles_id == 1)
                                                <select name="roles_id" class="form-select form-select-sm" required>
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->id }}"
                                                            id="employee_role_{{ $role->id }}">
                                                            {{ $role->name }} </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <div class="span" id="employee_profile_role">{{ 'Role Name' }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row gx-2 mt-2">
                                        <div class="col-4 col-lg-4 d-flex">
                                            <span class="ms-auto fw-light">{{ 'Level' }}</span>
                                        </div>
                                        <div class="col-8 scroll-x-hidden text-nowrap">
                                            @if ($auth_user->roles_id == 1)
                                                <select name="level"
                                                    class="form-select form-select-sm {{ $level_list->count() == 0 ? 'disabled' : '' }}"
                                                    required>
                                                    <option class="text-secondary" value="0">{{ 'Unset' }}
                                                    </option>
                                                    @foreach ($level_list as $level)
                                                        <option value="{{ $level->level }}"
                                                            id="employee_level_{{ $level->level }}">
                                                            {{ $level->level }} </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <div class="span" id="employee_profile_level">{{ 0 }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($auth_user->roles_id == 1)
                                        <div class="row justify-content-center mt-2">
                                            <div class="col-lg-10">
                                                <div class="btn-group btn-group-sm w-100">
                                                    <button form="formUpdateRole" type="submit" class="btn btn-primary"
                                                        disabled id="submitUpdate">
                                                        {{ 'Update' }}
                                                    </button>
                                                    <button type="button" id="submitDelete" disabled
                                                        class="btn btn-secondary">
                                                        {{ 'Remove' }}
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
                {{-- Employee --}}
                <div class="row mt-4 mb-3">
                    <div class="col-12">
                        {{-- Employee Filter --}}
                        <div class="card shadow-sm p-2">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex border-primary border-bottom pb-2">
                                        <span class="text-primary-emphasis me-auto my-auto h4">
                                            <i class="bi bi-people me-0"></i>
                                            <span class="d-none d-lg-inline">{{ 'Employee ' }}</span>
                                            {{ 'List' }}
                                        </span>
                                        <a href="{{ route('unemployee') }}">
                                            <button class="d-inline-block btn btn-sm btn-primary">
                                                <i class="bi bi-person-rolodex"></i>
                                                <span class="d-none d-md-inline">{{ 'Unemployee' }}</span>
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            {{-- Filter --}}
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <nav class="navbar py-0 mt-2">
                                        <form method="post" id="searchDepartmentForm" role="search"
                                            action="{{ route('role.filter') }}">
                                            @csrf
                                            @method('put')
                                        </form>
                                        <form method="post" id="formNameFilter" action="{{ route('role.filter') }}">
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
                                        <form method="post" id="formDepartmentFilter"
                                            action="{{ route('role.filter') }}">
                                            @csrf
                                            @method('put')
                                            <?php
                                            $is_department = $filter['category'] == 'department_id';
                                            $department_order = $filter['order'] == 'desc' ? 'desc' : 'asc';
                                            $department_icon = $is_department && $department_order == 'asc' ? 'up' : 'down';
                                            $department_value = $is_department && $department_order == 'asc' ? 'desc' : 'asc';
                                            $department_button = $is_department && $is_name ? '' : '';
                                            ?>
                                            <input type="hidden" name="category" value="department_id">
                                        </form>
                                        <div class="container d-block px-0 bg-body-tertiary rounded">
                                            <?php $keyword_focus = $filter['keyword'] ? 'autofocus' : ''; ?>
                                            <div class="row">
                                                <div class="input-group input-group-sm">
                                                    {{-- Name Filter --}}
                                                    <button type="button" form=""
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
                                                    <button type="submit" form="formDepartmentFilter"
                                                        class="btn btn-sm btn-outline-secondary border-0 {{ $department_button }} rounded-0 my-0"
                                                        name="order" value="{{ $department_value }}">
                                                        <span class="d-none d-lg-inline">{{ 'Department' }}</span>
                                                        <i class="bi bi-house d-lg-none"></i>
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
                        {{-- Employee List --}}
                        <div
                            class="scroll-container-2 scroll-container-lg-2 pt-2 bg-opacity-25 bg-secondary mt-2 rounded">
                            @if ($employees->count() == 0)
                                <div class="row mb-2">
                                    <div class="col">
                                        <span class="ms-3 text-secondary">{{ 'No employee found.' }}</span>
                                    </div>
                                </div>
                            @endif
                            <?php $i = 1; ?>
                            @foreach ($employees as $employee)
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <div class="card card-bg-hover py-1 mx-2 fw-light shadow-sm  text-dark"
                                            id="employee_card_{{ $employee->id }}"
                                            onclick="return set_employee({{ $employee }})">
                                            <span class="d-flex">
                                                <span
                                                    class="mx-2 border-end border-secondary text-secondary fw-normal pe-2">{{ $i }}</span>
                                                <span class="fw-normal">{{ $employee->name }}</span>
                                                <span
                                                    class="ms-auto me-2 fst-italic fw-light">{{ $employee->department ? $employee->department->name : '' }}</span>
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
            {{-- Payroll --}}
            <div class="col-lg-7 mb-lg-4">
                <div class="card bg-secondary bg-opacity-25 border-secondary border-2">
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-2 mb-0">
                                <div class="d-flex m-2">
                                    <span class="h3 text-primary-emphasis me-auto">{{ 'Payroll' }}</span>
                                    <span class="bg-primary text-white p-2 rounded-pill shadow-sm pe-3">
                                        @if ($auth_user->roles_id == 2)
                                            <button
                                                class="btn btn-sm btn-secondary rounded-circle px-2 py-1 my-auto shadow-sm"
                                                data-bs-toggle="modal" data-bs-target="#balanceModal">
                                                <i class="bi bi-gear-fill"></i>
                                            </button>
                                            <!-- Balance Modal -->
                                            <div class="modal fade" id="balanceModal" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content shadow mx-3">
                                                        <div class="modal-header py-1 ps-3 pe-2">
                                                            <span class="modal-title fs-5 text-primary-emphasis">
                                                                <i
                                                                    class="bi bi-wallet border-secondary border-end me-2 pe-2"></i>
                                                                {{ 'Set Payroll Balance' }}
                                                            </span>
                                                            <button type="button" class="btn btn-sm ms-auto"
                                                                data-bs-dismiss="modal" aria-label="Close"><i
                                                                    class="bi bi-x-lg"></i></button>
                                                        </div>
                                                        <form method="post"
                                                            action="{{ route('payroll.balance.add') }}"
                                                            enctype="multipart/form-data">
                                                            @csrf
                                                            @method('put')
                                                            <div class="modal-body bg-light">
                                                                <div class="row mt-2 justify-content-center">
                                                                    <div class="col-4 col-lg-3">
                                                                        <label for="balance"
                                                                            class="form-label d-inline-block text-dark">{{ 'Balance' }}</label>
                                                                    </div>
                                                                    <div class="col-8 col-lg-7">
                                                                        <input type="number" name="payroll_balance"
                                                                            class="form-control form-control-sm"
                                                                            value="{{ old('payroll_balance') }}"
                                                                            id="payroll_balance">
                                                                        <x-input-error :messages="$errors->get('payroll_balance')"
                                                                            class="mt-2" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer p-1">
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-primary ">{{ 'Set Balance' }}</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <span
                                            class="ms-2 fw-light">{{ 'Balance : ' }}</span>{{ format_currency($payroll_balance) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4 mb-2">
                        <div class="col-12">
                            <div class="card mx-2 pb-2">
                                <div class="row">
                                    <div class="col-12 d-flex">
                                        <span class="border-bottom border-primary mx-2 w-100 mt-2 pb-2 d-flex"><i
                                                class="bi bi-diagram-3 border-2 border-secondary border-end me-2 pe-2 my-auto fs-5"></i><span
                                                class="my-auto h5">{{ 'Level' }}</span>
                                            @if ($auth_user->roles_id == 2)
                                                <!-- Button trigger Add Level Modal -->
                                                <button class="btn btn-sm btn-primary shadow-sm ms-auto"
                                                    data-bs-toggle="modal" data-bs-target="#addLevelModal"
                                                    id="addModalTrigger">
                                                    <i class="bi bi-gear-fill"></i>
                                                </button>
                                                <!-- Add Level Modal -->
                                                <div class="modal fade" id="addLevelModal" tabindex="-1"
                                                    aria-labelledby="newCashInModal" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content shadow mx-3">
                                                            <div class="modal-header py-1 ps-3 pe-2">
                                                                <span class="modal-title fs-5 text-primary-emphasis"
                                                                    id="newCashInModal">
                                                                    <i
                                                                        class="bi bi-gear-fill border-secondary border-end me-2 pe-2"></i>
                                                                    {{ 'Set Employee Level' }}
                                                                </span>
                                                                <button type="button" class="btn btn-sm ms-auto"
                                                                    data-bs-dismiss="modal" aria-label="Close"><i
                                                                        class="bi bi-x-lg"></i></button>
                                                            </div>
                                                            <form method="post"
                                                                action="{{ route('level.add.edit') }}"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                @method('put')
                                                                <div class="modal-body bg-light">
                                                                    <div class="row gx-2 justify-content-center">
                                                                        <div class="col-5 col-lg-4 text-end">
                                                                            <label for="level"
                                                                                class="form-label d-inline-block">{{ 'Level' }}</label>
                                                                        </div>
                                                                        <div class="col-6 col-lg-5">
                                                                            <input type="number" name="level"
                                                                                class="form-control form-control-sm"
                                                                                id="level" required
                                                                                value="{{ old('level') }}"
                                                                                placeholder="1, 2, 3, etc..">
                                                                            <x-input-error :messages="$errors->get('level')"
                                                                                class="mt-2" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="row gx-2 mt-2 justify-content-center">
                                                                        <div class="col-5 col-lg-4 text-end">
                                                                            <label for="salary"
                                                                                class="form-label d-inline-block">{{ 'Salary Budget' }}</label>
                                                                        </div>
                                                                        <div class="col-6 col-lg-5">
                                                                            <div class="d-flex">
                                                                                <input type="number" name="salary"
                                                                                    class="form-control form-control-sm"
                                                                                    placeholder="100"
                                                                                    value="{{ old('salary') }}"
                                                                                    required id="salary">
                                                                                <span
                                                                                    class=" ms-2">{{ '%' }}</span>
                                                                            </div>
                                                                            <x-input-error :messages="$errors->get('salary')"
                                                                                class="mt-2" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer p-1">
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-primary ">{{ 'Set' }}</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 d-flex">
                                        <div class="scroll-container-horizontal mx-2 d-block w-100">
                                            <div class="row gx-2 justify-content-lg-center mt-2"
                                                style="flex-wrap: nowrap;">
                                                <div class="col-2 col-lg-1 text-center">
                                                    <span
                                                        class="text-primary-emphasis fw-bold">{{ 'Level' }}</span>
                                                </div>
                                                <div class="col-3 col-lg-2 text-center">
                                                    <span
                                                        class="text-primary-emphasis fw-bold">{{ 'Budget(%)' }}</span>
                                                </div>
                                                <div class="col-4 col-lg-3 text-center">
                                                    <span
                                                        class="text-primary-emphasis fw-bold">{{ 'Budget(IDR)' }}</span>
                                                </div>
                                                <div class="col-4 col-lg-2 text-center">
                                                    <span class="text-primary-emphasis fw-bold">{{ 'Employee(' }}<i
                                                            class="bi bi-people-fill"></i>{{ ')' }}</span>
                                                </div>
                                                <div class="col-4 col-lg-3 text-center">
                                                    <span
                                                        class="text-primary-emphasis fw-bold">{{ 'Salary(IDR)' }}</span>
                                                </div>
                                            </div>
                                            @foreach ($level_list as $level)
                                                <?php $budget = ($level->salary * $payroll_balance) / 100; ?>
                                                <div class="row gx-2 justify-content-lg-center mt-2 "
                                                    style="flex-wrap: nowrap;">
                                                    <div class="col-2 col-lg-1 text-center">
                                                        <span class="">{{ $level->level }}</span>
                                                    </div>
                                                    <div class="col-3 col-lg-2 text-center">
                                                        <span class="">{{ $level->salary . '%' }}</span>
                                                    </div>
                                                    <div class="col-4 col-lg-3 text-center">
                                                        <span class="">{{ format_currency($budget) }}</span>
                                                    </div>
                                                    <div class="col-4 col-lg-2 text-center">
                                                        <span class="">{{ $level->employee . ' person' }}</span>
                                                    </div>
                                                    <div class="col-4 col-lg-3 text-center">
                                                        <span
                                                            class="">{{ format_currency($level->employee > 0 ? $budget / $level->employee : 0) }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @if ($level_list->count() == 0)
                                    <p class="m-2 fst-italic text-secondary">
                                        {{ 'Financial Officer need to add employee level.' }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var selected_employee;
        const department_length = {{ $departments->count() }};
        const role_length = {{ $roles->count() }};
        const auth_roles_id = {{ $auth_user->roles_id }};
        const remove_route = @json(route('role.remove'));
        const profile_route = @json(route('profile.edit'));

        function set_employee(employee) {
            var employee_link = document.getElementById('profile_link');
            var employee_name = document.getElementById('employee_profile_name');
            var employee_email = document.getElementById('employee_profile_email');
            var employee_phone = document.getElementById('employee_profile_phone');
            var employee_card = document.getElementById('employee_card_' + employee.id);
            var employee_department = document.getElementById('employee_profile_department');

            employee_link.setAttribute('href', profile_route + '/' + employee.id)
            employee_department.innerHTML = employee.department ? employee.department.name : '';
            employee_name.innerHTML = employee.name;
            employee_email.innerHTML = employee.email;
            employee_phone.innerHTML = employee.phone;
            if (selected_employee) {
                var selected_employee_card = document.getElementById('employee_card_' + selected_employee.id);
                selected_employee_card.classList.remove('shadow', 'fw-normal');
                selected_employee_card.classList.add('shadow-sm', 'fw-light');
                if (auth_roles_id == 1) {
                    var selected_employee_role = document.getElementById('employee_role_' + selected_employee.roles_id);
                    var selected_employee_level = document.getElementById('employee_level_' + selected_employee.level);
                    selected_employee_level?.removeAttribute('selected');
                    selected_employee_role?.removeAttribute('selected');
                }
            } else {
                if (auth_roles_id == 1) {
                    var selected_employee_department = document.getElementById('employee_department_0');
                    selected_employee_department?.removeAttribute('selected');
                }
            }

            employee_card.classList.remove('shadow-sm', 'fw-light');
            employee_card.classList.add('shadow', 'fw-normal');

            selected_employee = employee;

            if (auth_roles_id == 1) {
                var employee_department_id = employee.department_id ? employee.department_id : 0;
                var submitUpdate = document.getElementById('submitUpdate');
                var submitDelete = document.getElementById('submitDelete');
                var employee_id = document.getElementById('employee_id');
                var employee_role_option_selected = document.getElementById('employee_role_' + employee.roles_id);
                var employee_level_option_selected = document.getElementById('employee_level_' + employee.level);

                submitUpdate.removeAttribute('disabled');
                submitDelete.removeAttribute('disabled');
                submitDelete.onclick = function() {
                    confirmation((remove_route + '/' + employee.id), ('Are you sure want to remove ' + employee.name +
                        ' from employee?'));
                }
                employee_id.value = employee.id;
                employee_role_option_selected?.setAttribute('selected', '');
                employee_level_option_selected?.setAttribute('selected', '');
            } else {
                var employee_role = document.getElementById('employee_profile_role');
                var employee_level = document.getElementById('employee_profile_level');

                employee_role.innerHTML = employee.roles.name;
                employee_level.innerHTML = employee.level ? employee.level : 'Unset';
            }
        }
    </script>
</x-app-layout>
