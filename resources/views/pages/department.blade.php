<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Department') }}
        </h2>
    </x-slot>

    <div class="container py-3 px-10">
        {{-- Department Dashboard --}}
        <div class="col-lg-12 mx-auto mb-4">
            <div class="border border-info shadow bg-primary p-3 sm:p-6 rounded-lg mx-2" style="--bs-bg-opacity: .02;">
                <div class="text-primary h5 pb-2 border-primary border-bottom mx-2">
                    {{ __('Department Dashboard') }}
                </div>
                <div class="row">
                    <div class="col-lg-8 border-secondary border-end">
                        <?php $total_program = 0; ?>
                        <ul style="list-style-type:circle">
                            @foreach ($departments as $department)
                                <li class="ms-2 mb-1">
                                    <span class="fw-bold">{{ $department->name . ' Department ' }} <span
                                            class="fw-light fst-italic">has</span>
                                        {{ count($department->program()->get()) }} <span
                                            class="fw-light">program.</span> </span>
                                    <span class="float-end me-2">
                                        <i class="text-secondary">{{ 'Managed by ' }}</i>
                                        <span class="ms-2 fw-bold">{{ $department->manager->name }}</span>
                                    </span>
                                </li>
                                <?php $total_program += count($department->program()->get()); ?>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-lg-4 ">
                        <div class="row mx-auto sm:px-6 px-0 space-y-3 bg-white rounded-lg">
                            <h6 class="fw-bold">Date Time <span
                                    class="text-center fw-light fst-italic float-end">{{ now()->format('d/M/y H:i:s') }}</span>
                            </h6>
                            <p class="text-secondary mb-0"> All data on the display are based on this date and time.
                                Reload
                                the
                                page to update the newest data.</p>
                        </div>
                        <p class="ms-2 mt-1 mb-0">Total Departments : {{ count($departments) }} </p>
                        <p class="ms-2 mb-2 ">Total Programs : {{ $total_program }} </p>
                        @if (Auth::user()->roles->id == 1)
                            <!-- Button trigger Add Department Modal -->
                            <button class="text-dark btn bg-white shadow py-1 ms-2" data-bs-toggle="modal"
                                style="width: 90%;" data-bs-target="#addDepartmentModal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-plus-lg d-inline text-primary" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                                </svg>
                                Add Department
                            </button>

                            <!-- Add Department Modal -->
                            <div class="modal fade" id="addDepartmentModal" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Department</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form method="post" action="{{ route('department.add') }}">
                                            @csrf
                                            @method('put')
                                            <div class="modal-body">
                                                <div class="row g-3 align-items-center justify-content-center">
                                                    <div class="col-2">
                                                        <label for="department_name"
                                                            class="form-label d-inline-block">Name</label>
                                                    </div>
                                                    <div class="col-6">
                                                        <input type="text" class="form-control d-inline-block"
                                                            name="name" id="department_name" required>
                                                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                                    </div>
                                                </div>
                                                <div class="row g-3 mt-1 align-items-center justify-content-center">
                                                    <div class="col-2">
                                                        <label for="department_manager"
                                                            class="form-label d-inline-block">Manager</label>
                                                    </div>
                                                    <div class="col-6">
                                                        <select name="manager_id" id="department_manager"
                                                            class="form-select" aria-label="Default select example"
                                                            required>
                                                            <option selected value="" disabled>Choose the manager
                                                                here
                                                            </option>
                                                            @foreach ($users as $user)
                                                                {{-- User can not be manager more than one departments --}}
                                                                @if ($user->department())
                                                                    <option value="{{ $user->id }}">
                                                                        {{ $user->name }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        <x-input-error :messages="$errors->get('manager_id')" class="mt-2" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn shadow text-primary">Add</button>
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
        {{-- Departments Item --}}
        <div class="row mx-auto sm:px-6 px-0 space-y-6">
            @if (!$departments)
                <h3 class="mt-4 fst-italic fw-light text-primary text-center">Ooops, no department found. Please ask CEO
                    to add the
                    departments. </h3>
            @endif
            @foreach ($departments as $department)
                <div class="col-lg-6 mt-0 mb-4 px-2">
                    <div class="bg-white p-3 shadow sm:p-6 sm:rounded-lg">
                        {{-- Delete Department --}}
                        @if (Auth::user()->roles->id == 1)
                            {{-- Delete Department Modal Trigger --}}
                            <button class="btn pt-1 float-end px-1" data-bs-toggle="modal"
                                data-bs-target="#delDepartment{{ $department->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-trash3 text-danger d-inline" viewBox="0 0 16 16">
                                    <path
                                        d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                </svg> Delete
                            </button>
                            <!-- Delete Department Modal -->
                            <div class="modal fade" id="delDepartment{{ $department->id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-top border-warning">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">
                                                {{ $department->name }}
                                                Department</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form method="post"
                                            action="{{ route('department.delete', $department->id) }}">
                                            @csrf
                                            @method('post')
                                            <div class="modal-body">
                                                <p class="mx-5">All program and progress in
                                                    {{ $department->name }}
                                                    Department will be lost. Please make sure you have back up all
                                                    the
                                                    data
                                                    needed.</p>
                                                <div class="row g-3 align-items-center">
                                                    <div class="col-auto mx-auto border-bottom">
                                                        <label for="password"
                                                            class="form-label d-inline-block">Password
                                                            Needed to Authorize</label>
                                                    </div>
                                                </div>
                                                <div class="row g-3 align-items-center mt-1">
                                                    <div class="col-auto ms-auto">
                                                        <label for="pic"
                                                            class="form-label d-inline-block">Password</label>
                                                    </div>
                                                    <div class="col-auto ms-2 me-auto">
                                                        <input id="password" class="form-control" type="password"
                                                            name="password" required
                                                            autocomplete="current-password" />
                                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
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
                        @endif
                        {{-- Edit Department --}}
                        @if (Auth::user()->roles->id == 1)
                            {{-- Edit Department Modal Trigger --}}
                            <button class="btn float-end pt-1  px-1" data-bs-toggle="modal"
                                data-bs-target="#editDepartment{{ $department->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-pen-fill text-warning d-inline"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001" />
                                </svg>
                                Edit
                            </button>
                            <!-- Edit Department Modal -->
                            <div class="modal fade" id="editDepartment{{ $department->id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-top border-warning">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">
                                                {{ $department->name }}
                                                Department</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form method="post"
                                            action="{{ route('department.update', $department->id) }}"
                                            onsubmit="return confirm('Are you sure ALL DATA is filled correctly?')">
                                            @csrf
                                            @method('put')
                                            <div class="modal-body">
                                                <div class="row g-3 align-items-center">
                                                    <div class="col-auto mx-auto border-bottom">
                                                        <label for="name"
                                                            class="form-label d-inline-block">Setting new department
                                                            name.</label>
                                                    </div>
                                                </div>
                                                <div class="row g-3 mt-1">
                                                    <div class="col-3 ms-auto text-end">
                                                        <label for="name" class="form-label d-inline-block">New
                                                            name</label>
                                                    </div>
                                                    <div class="col-7 ms-2 me-auto">
                                                        <input id="name" class="form-control" type="text"
                                                            required name="name" required
                                                            value="{{ $department->name }}"
                                                            placeholder="current: {{ $department->name }}" />
                                                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                                    </div>
                                                </div>
                                                <div class="row g-3 mt-1">
                                                    <div class="col-3 ms-auto text-end">
                                                        <label for="department_manager"
                                                            class="form-label d-inline-block">New Manager</label>
                                                    </div>
                                                    <div class="col-7 ms-2 me-auto">
                                                        <select name="manager_id" id="department_manager"
                                                            class="form-select" aria-label="Default select example"
                                                            required>
                                                            <option value="">Choose new manager here
                                                            </option>
                                                            @foreach ($users as $user)
                                                                <?php $selected = $department->manager->id == $user->id ? 'selected' : ''; ?>
                                                                <option value="{{ $user->id }}"
                                                                    {{ $selected }}>
                                                                    {{ $user->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <x-input-error :messages="$errors->get('manager_id')" class="mt-2" />
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
                        <div class="h5 fw-bold ">{{ $department->name }} Department Programs</div>
                        {{-- Manager Feature --}}
                        @if (Auth::user()->id == $department->manager_id)
                            <div class="d-flex justify-content-between mt-3 mb-1 py-1 bg-light">
                                {{-- Add program --}}
                                <span class="fst-italic fs-6">Only accessible by Department Manager</span>
                                <!-- Button trigger Add Program Modal -->
                                <button
                                    class="btn text-dark py-0 me-1 px-1 float-end bg-white border-secondary border-start-0 border-end-0"
                                    data-bs-toggle="modal" data-bs-target="#addProgramModal{{ $department->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-plus d-inline text-primary"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                    </svg>
                                    Add Program
                                </button>

                                <!-- Add Program Modal -->
                                <div class="modal fade" id="addProgramModal{{ $department->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-top border-warning">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Add
                                                    {{ $department->name }}
                                                    Program</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form method="post"
                                                action="{{ route('program.add', ['id' => $department->manager_id]) }}"
                                                onsubmit="return confirm('Are you sure ALL DATA is filled correctly?')">
                                                @csrf
                                                @method('put')
                                                <input type="hidden" name="department_id"
                                                    value="{{ $department->id }}">
                                                <div class="modal-body">
                                                    <div class="row g-3 align-items-center">
                                                        <div class="col-5">
                                                            <label for="department_name"
                                                                class="form-label d-inline-block float-end">Program
                                                                Name</label>
                                                        </div>
                                                        <div class="col-6 ms-2">
                                                            <input type="text" class="form-control d-inline-block"
                                                                required name="name" id="department_name">
                                                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                                        </div>
                                                    </div>
                                                    <div class="row g-3 align-items-center mt-1">
                                                        <div class="col-5">
                                                            <label for="pic"
                                                                class="form-label d-inline-block float-end">Person
                                                                In Charge</label>
                                                        </div>
                                                        <div class="col-6 ms-2 ">
                                                            <select name="pic_id" id="pic" class="form-select"
                                                                aria-label="Default select example" required>
                                                                <option selected value="" disabled>Choose the PIC
                                                                    here
                                                                </option>
                                                                @foreach ($users as $user)
                                                                    {{-- User may only have one programs --}}
                                                                    @if ($user->program())
                                                                        <option value="{{ $user->id }}">
                                                                            {{ $user->name }}
                                                                        </option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
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
                            </div>
                        @endif
                        <table class="table">
                            <thead class="table-primary">
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">PIC</th>
                            </thead>
                            <tbody class="table-group-divider">
                                <?php $i = 1; ?>
                                @foreach ($department->program as $program)
                                    <tr>
                                        <th scope="row">{{ $i }}</th>
                                        <td><a href="{{ route('program', $program->id) }}"
                                                class="text-decoration-none text-blue-900 ">{{ $program->name }}</a>
                                        </td>
                                        <td>{{ $program->user->name }}</td>
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
