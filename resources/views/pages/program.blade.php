<x-app-layout>
    <x-slot name="header">
        <nav aria-label="breadcrumb w-auto b-0" style="--bs-breadcrumb-divider: '>';">
            <ol class="breadcrumb">
                <li class="breadcrumb-item " aria-current="page"><a href="{{ route('department') }}"
                        class="text-decoration-none text-black">{{ $program->department->name }} Department</a></li>
                <li class="breadcrumb-item font-semibold text-xl text-gray-800 leading-tight" aria-current="page">
                    {{ __('Program') }}</li>
            </ol>
        </nav>

    </x-slot>

    <div class="container py-3 px-10">
        <div class="row mx-auto sm:px-6 px-2">
            {{-- Program Dashboard --}}
            <div class="col-lg-12 mt-0 mb-4 px-2">
                <div class="bg-primary p-3 sm:p-6 shadow rounded-lg border border-info" style="--bs-bg-opacity: .02">
                    <div class="h5 text-primary border-primary border-bottom pb-2 mx-2">
                        {{ __($program->name . ' Dashboard') }}
                        {{-- Manager Feature --}}
                        @if (Auth::user()->id == $program->department->manager->id)
                            {{-- Delete Program --}}
                            <button class="btn pt-1 float-end px-1" data-bs-toggle="modal" data-bs-target="#delProgram">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-trash3 text-danger d-inline" viewBox="0 0 16 16">
                                    <path
                                        d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                </svg> Delete
                            </button>
                            <!-- Delete Program Modal -->
                            <div class="modal fade text-dark fs-6 fw-light" id="delProgram" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-top border-warning">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">
                                                {{ $program->name }}
                                                Program</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>

                                        <form method="post" action="{{ route('program.delete', $program->id) }}">
                                            @csrf
                                            @method('put')
                                            <div class="modal-body">
                                                <p class="mx-5 fw-light">All budget, disbursement, and expense in
                                                    {{ $program->name }}
                                                    Program will be lost. Please make sure you have back up all
                                                    the
                                                    data
                                                    needed.</p>
                                                <div class="row g-3 align-items-center">
                                                    <div class="col-auto mx-auto border-bottom">
                                                        <label for="password" class="form-label d-inline-block">Password
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
                                                            name="password" required autocomplete="current-password" />
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
                            {{-- Edit Program --}}
                            <button class="btn float-end pt-1  px-1" data-bs-toggle="modal"
                                data-bs-target="#editProgram">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-pen-fill text-warning d-inline"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001" />
                                </svg>
                                Edit
                            </button>
                            <!-- Edit Program Modal -->
                            <div class="modal fade fs-6 text-dark" id="editProgram" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-top border-warning">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">
                                                {{ $program->name }}
                                                Program</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form method="post" action="{{ route('program.update', $program->id) }}"
                                            onsubmit="return confirm('Are you sure ALL DATA is filled correctly?')">
                                            @csrf
                                            @method('put')
                                            <div class="modal-body">
                                                <div class="row g-3 align-items-center">
                                                    <div class="col-auto mx-auto border-bottom">
                                                        <label for="name" class="form-label d-inline-block">Setting
                                                            new program name.</label>
                                                    </div>
                                                </div>
                                                <div class="row g-3 mt-1">
                                                    <div class="col-3 ms-auto text-end">
                                                        <label for="name" class="form-label mb-0">New
                                                            name</label>
                                                    </div>
                                                    <div class="col-7 ms-2 me-auto">
                                                        <input id="name" class="form-control" type="text"
                                                            required name="name" required
                                                            placeholder="current: {{ $program->name }}" />
                                                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                                    </div>
                                                </div>
                                                <div class="row g-3 mt-1">
                                                    <div class="col-3 ms-auto text-end">
                                                        <label for="pic_id" class="form-label d-inline-block">New
                                                            PIC</label>
                                                    </div>
                                                    <div class="col-7 ms-2 me-auto">
                                                        <select name="pic_id" id="pic_id" class="form-select"
                                                            required>
                                                            <option selected class="text-secondary" value="">
                                                                Choose new PIC here
                                                            </option>
                                                            @foreach ($users as $user)
                                                                @if (!$user->program)
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
                                                <button type="submit"
                                                    class="btn border-warning shadow">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="row mx-auto sm:px-6 px-0 space-y-3">
                        <div class="col-lg-3 pt-2 bg-white rounded-lg">
                            <h6 class="fw-bold">Date Time <span
                                    class="text-center fw-light fst-italic float-end">{{ now()->format('d/M/Y h:i:s') }}</span>
                            </h6>
                            <p class="text-secondary mb-0">All data on the display are based on this date and time.
                                Reload
                                the
                                page to update the newest data.</p>
                        </div>
                        <div class="col-lg-5">
                            <div class="fs-6 my-1">Person in Charge: <span
                                    class="float-end">{{ $program->user->name }}</span></div>
                            <?php
                            
                            if ($program->financial_id != null) {
                                $approval = 'Approved';
                                $text = 'success';
                            } else {
                                $approval = 'Unapproved';
                                $text = 'secondary';
                            }
                            ?>
                            <div class="fs-6 my-1">Total Budget <span
                                    class="fw-bold text-{{ $text }}">({{ $approval }}
                                    @if ($program->financial_id != null)
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor"
                                            class="bi bi-check-circle-fill text-success d-inline mb-1"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                            fill="currentColor" class="bi bi-ban text-secondary d-inline mb-1"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M15 8a6.97 6.97 0 0 0-1.71-4.584l-9.874 9.875A7 7 0 0 0 15 8M2.71 12.584l9.874-9.875a7 7 0 0 0-9.874 9.874ZM16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0" />
                                        </svg>
                                    @endif
                                    )
                                </span>:
                                <span class=" float-end">{{ Number::currency($program->budget, in: 'IDR') }}</span>
                            </div>
                            <div class="fs-6 my-1">Total Disbursement: <span class="float-end">
                                    {{ Number::currency($program->disbursement, in: 'IDR') }}</span>
                            </div>
                            <div class="fs-6 my-1">Total Expense: <span
                                    class="float-end">{{ Number::currency($program->expense, in: 'IDR') }}</span>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <?php
                            $d = $program->disbursement;
                            $e = $program->expense;
                            $b = $program->budget;
                            if ($b == 0) {
                                $disbursement_percentage = 0;
                            } else {
                                $disbursement_percentage = round(($d / $b) * 100);
                            }
                            if ($d == 0) {
                                $expense_percentage = 0;
                            } else {
                                $expense_percentage = round(($e / $d) * 100);
                            }
                            
                            function color($value)
                            {
                                if ($value < 85) {
                                    return 'info';
                                } elseif ($value <= 100) {
                                    return 'success';
                                } else {
                                    return 'danger';
                                }
                            }
                            ?>
                            <div data-bs-toggle="tooltip" data-bs-placement="left"
                                data-bs-title="This is showing disburse percentage from approved budget.">
                                <p class="text-secondary mb-0 mt-2">Disbursement percentage.</p>
                                <div class="progress mt-1" role="progressbar" aria-label="Disbursement"
                                    aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                                    <div class="progress-bar bg-{{ color($disbursement_percentage) }}"
                                        style="width: {{ $disbursement_percentage }}%">
                                        {{ $disbursement_percentage }}%</div>
                                </div>
                            </div>
                            <div data-bs-toggle="tooltip" data-bs-placement="left"
                                data-bs-title="This is showing expense percentage from disburse fund.">
                                <p class="text-secondary mb-0 mt-2">Expense percentage.</p>
                                <div class="progress mt-1" role="progressbar" aria-label="Expense"
                                    aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                                    <div class="progress-bar bg-{{ color($expense_percentage) }}"
                                        style="width: {{ $expense_percentage }}%">
                                        {{ $expense_percentage }}%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mx-auto sm:px-6 px-2">
            {{-- Budget Items --}}
            <div class="col-lg-5 mb-4">
                <div class="bg-white p-3 sm:p-6 shadow rounded-lg">
                    <div class="h5 fw-bold d-inline-block">{{ __('Budget Item') }}</div>
                    <div class="float-end">
                        @if ($program->financial_id == null)
                            {{-- Person In Charge Feature --}}
                            @if (Auth::user()->id == $program->pic_id)
                                <!-- Add Budget Button Trigger Modal -->
                                <button class="btn text-dark pt-0 pe-2" data-bs-toggle="modal"
                                    data-bs-target="#addBudgetItemModal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-plus d-inline text-primary"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                    </svg>
                                    Add Budget Item
                                </button>

                                <!-- Add Budget Item Modal -->
                                <div class="modal fade" id="addBudgetItemModal" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-top border-warning">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Add
                                                    {{ $program->name }}
                                                    Budget Item</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form method="post"
                                                action="{{ route('budget_item.add', $program->id) }}">
                                                @csrf
                                                @method('put')
                                                <div class="modal-body">
                                                    <div class="text-center mb-2"><span
                                                            class="ms-3 border-bottom pb-1 d-block"
                                                            style="font-size: 12px">
                                                            <span class="text-danger ">*</span>
                                                            Don't use
                                                            comma
                                                            (,) or dot (.). Write the numbers only.</span></div>
                                                    <div class="row g-3 align-items-center">
                                                        <div class="col-3">
                                                            <label for="name"
                                                                class="form-label d-inline-block float-end">Item
                                                                Name</label>
                                                        </div>
                                                        <div class="col-8 ms-2">
                                                            <input type="text" class="form-control d-inline-block"
                                                                name="name" id="name"
                                                                value="{{ old('name') }}">
                                                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                                        </div>
                                                    </div>
                                                    <div class="row g-3 align-items-center mt-1">
                                                        <div class="col-3">
                                                            <label for="price"
                                                                class="form-label d-inline-block float-end">Price<span
                                                                    class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="col-8 ms-2">
                                                            <input type="number" class="form-control d-inline-block"
                                                                name="price" id="price"
                                                                value="{{ old('price') }}">
                                                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                                                        </div>
                                                    </div>
                                                    <div class="row g-3 align-items-center mt-1">
                                                        <div class="col-3">
                                                            <label for="unit"
                                                                class="form-label d-inline-block float-end">Unit</label>
                                                        </div>
                                                        <div class="col-8 ms-2">
                                                            <input type="text" class="form-control d-inline-block"
                                                                name="unit" id="unit"
                                                                value="{{ old('unit') }}"
                                                                placeholder="pcs, person, box, etc..">
                                                            <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                                                        </div>
                                                    </div>
                                                    <div class="row g-3 align-items-center mt-1">
                                                        <div class="col-3">
                                                            <label for="qty"
                                                                class="form-label d-inline-block float-end">Quantity<span
                                                                    class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="col-8 ms-2">
                                                            <input type="number" class="form-control d-inline-block"
                                                                name="qty" id="qty"
                                                                value="{{ old('qty') }}">
                                                            <x-input-error :messages="$errors->get('qty')" class="mt-2" />
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
                            @endif
                            {{-- Financial Feature --}}
                            @if (Auth::user()->roles_id == 2)
                                <form method="post" class="d-inline ms-0"
                                    action="{{ route('budget_item.validate', ['id' => $program->id]) }}">
                                    @csrf
                                    @method('put')
                                    <button type="submit"
                                        class="border-start border-success bg-success-subtle text-success ps-2 pe-2 "
                                        value="{{ Auth::user()->id }}" name="financial_id" data-bs-dismiss="modal">
                                        VALIDATE
                                    </button>
                                </form>
                            @endif
                        @else
                            <span class="text-secondary fst-italic d-inline-block">Budget Approved by
                                {{ $program->financial->name }}</span>
                            {{-- Financial Feature --}}
                            @if (Auth::user()->roles_id == 2)
                                <button type="submit"
                                    class="d-inline-block bg-danger-subtle  border-start border-danger text-danger ps-2 pe-2 ms-2"
                                    value="0" name="financial_id" form="formValidationBudget"
                                    data-bs-dismiss="modal">
                                    UNVALIDATE
                                </button>
                                <form method="post" id="formValidationBudget"
                                    action="{{ route('budget_item.validate', ['id' => $program->id]) }}">
                                    @csrf
                                    @method('put')
                                </form>
                            @endif
                        @endif
                    </div>
                    {{-- Budget Item Table --}}
                    <table class="table">
                        <thead class="table-primary">
                            <th scope="col">#</th>
                            <th scope="col">Item</th>
                            <th scope="col">Price</th>
                            <th scope="col">Unit</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                        </thead>
                        <tbody class="table-group-divider">
                            @if (Auth::user()->id == $program->pic_id)
                                {{-- Action form --}}
                                <form method="post" id="formUpdateBudgetItem"
                                    action="{{ route('budget_item.update', ['pic_id' => $program->pic_id]) }}">
                                    @csrf
                                    @method('put')
                                    <tr>
                                        <td colspan="5"><span class="fst-italic" style="font-size: 12px">This
                                                function only accessible
                                                by PIC only.</span></td>
                                        <td colspan="1" class="text-end"> Action:
                                            {{-- action button --}}
                                            @if (count($budget_items) > 0 && $program->financial_id == null)
                                                <button type="submit" form="formUpdateBudgetItem"
                                                    class="px-1 d-inline" value="update" name="action">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor"
                                                        class="bi bi-pen-fill text-primary" viewBox="0 0 16 16">
                                                        <path
                                                            d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001" />
                                                    </svg>
                                                </button>

                                                <button type="submit" form="formUpdateBudgetItem"
                                                    class="px-1 d-inline" value="delete" name="action"
                                                    onclick="return confirm('Confirm to delete this item?')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor"
                                                        class="bi bi-trash3 text-danger" viewBox="0 0 16 16">
                                                        <path
                                                            d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                                    </svg>
                                                </button>
                                            @else
                                                <span class="text-secondary fst-italic">disabled</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="border-warning">
                                        <?php $disabled = count($budget_items) > 0 && $program->financial_id == null ? '' : 'disabled'; ?>
                                        <td colspan="3">
                                            <select {{ $disabled }} name="id" id="pic"
                                                class="form-select py-0" aria-label="Default select example" required>
                                                <option selected value="null">select item</option>
                                                @foreach ($budget_items as $budget_item)
                                                    <option value="{{ $budget_item->id }}">
                                                        {{ $budget_item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get('id')" class="mt-2" />
                                        </td>
                                        <td>
                                            <input {{ $disabled }} type="text"
                                                class="form-control py-0 d-inline-block" style="max-width: 110px;"
                                                name="unit_budget" id="unit" placeholder="unit">
                                            <x-input-error :messages="$errors->get('unit_budget')" class="mt-2" />
                                        </td>
                                        <td colspan="1">
                                            <input {{ $disabled }} type="number"
                                                class="form-control py-0 d-inline-block" name="quantity_budget"
                                                id="qty" style="max-width: 70px;" placeholder="qty">
                                            <x-input-error :messages="$errors->get('quantity_budget')" class="mt-2" />
                                        </td>
                                        <td>
                                            <input {{ $disabled }} type="number"
                                                class="form-control py-0 d-inline-block" name="price_budget"
                                                id="price" placeholder="price">
                                            <x-input-error :messages="$errors->get('price_budget')" class="mt-2" />
                                        </td>
                                    </tr>
                                </form>
                            @endif
                            <?php $i = $budget_items->perPage() * ($budget_items->currentPage() - 1) + 1; ?>
                            @foreach ($budget_items as $budget_item)
                                <tr>
                                    <th scope="row">{{ $i }}</th>
                                    <td>{{ $budget_item->name }}</td>
                                    <td>{{ $budget_item->price }}</td>
                                    <td>{{ $budget_item->unit }}</td>
                                    <td class="text-center">{{ $budget_item->qty }}</td>
                                    <td>{{ Number::currency($budget_item->total_price, in: 'IDR') }}</td>
                                </tr>
                                <?php $i++; ?>
                            @endforeach
                            {{ $budget_items->links() }}
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Disbursement Items --}}
            <div class="col-lg-7 mb-4">
                <div class="bg-white p-3 sm:p-6 shadow rounded-lg">
                    <div class="h5 fw-bold d-inline-block">{{ __('Disbursement Item') }}</div>
                    @if (Auth::user()->roles->id == 2)
                        @if ($program->financial_id != null)
                            <!-- Add Disbursement Button Trigger Modal -->
                            <button class="btn text-dark float-end pt-0 pe-2" data-bs-toggle="modal"
                                data-bs-target="#addDisbursementItemModal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-plus d-inline text-primary" viewBox="0 0 16 16">
                                    <path
                                        d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                </svg>
                                Add Disbursement Item
                            </button>

                            <!-- Add Disbursement Item Modal -->
                            <div class="modal fade" id="addDisbursementItemModal" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-top border-warning">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Add
                                                {{ $program->name }}
                                                Disbursement Item</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form method="post"
                                            action="{{ route('disbursement_item.add', $program->id) }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('put')
                                            <div class="modal-body">
                                                <div class="text-center mb-2"><span class="ms-3 pb-1 d-block"
                                                        style="font-size: 12px">
                                                        <span class="text-danger ">*</span>
                                                        Don't use
                                                        comma
                                                        (,) or dot (.). Write the numbers only.</span>
                                                </div>
                                                <div class="text-center mb-2">
                                                    <span class="ms-3 border-bottom pb-1 d-block"
                                                        style="font-size: 12px">
                                                        <span class="text-danger ">**</span>
                                                        Max size: 5Mb, Type: .jpg, .jpeg, .png, .heic.
                                                    </span>
                                                </div>
                                                <div class="row g-3 align-items-center">
                                                    <div class="col-3">
                                                        <label for="name"
                                                            class="form-label d-inline-block float-end">Item
                                                            Name</label>
                                                    </div>
                                                    <div class="col-8 ms-2">
                                                        <input type="text" class="form-control d-inline-block"
                                                            name="disbursement_name" id="disbursement_name"
                                                            value="{{ old('disbursement_name') }}" required>
                                                        <x-input-error :messages="$errors->get('disbursement_name')" class="mt-2" />
                                                    </div>
                                                </div>
                                                <div class="row g-3 align-items-center mt-1">
                                                    <div class="col-3">
                                                        <label for="disbursement_price"
                                                            class="form-label d-inline-block float-end">Price<span
                                                                class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-8 ms-2">
                                                        <input type="number" class="form-control d-inline-block"
                                                            name="disbursement_price" id="disbursement_price"
                                                            value="{{ old('disbursement_price') }}" required>
                                                        <x-input-error :messages="$errors->get('disbursement_price')" class="mt-2" />
                                                    </div>
                                                </div>
                                                <div class="row g-3 align-items-center mt-1">
                                                    <div class="col-3">
                                                        <label for="disbursement_reciept"
                                                            class="form-label d-inline-block float-end">Reciept<span
                                                                class="text-danger ">**</span></label>
                                                    </div>
                                                    <div class="col-8 ms-2">
                                                        <input type="file"
                                                            class="form-control form-control-sm d-inline-block"
                                                            name="disbursement_reciept" id="disbursement_reciept"
                                                            value="{{ old('disbursement_reciept') }}" required>
                                                        <x-input-error :messages="$errors->get('disbursement_reciept')" class="mt-2" />
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn shadow border-primary">Add</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <span class="float-end text-secondary fst-italic">Waiting for Budget Approval</span>
                        @endif
                    @endif
                    {{-- Disbursement Item Table --}}
                    <table class="table">
                        <thead class="table-primary">
                            <th scope="col">#</th>
                            <th scope="col">Item</th>
                            <th scope="col">Price</th>
                            <th scope="col">Financial</th>
                            <th scope="col">Receipt</th>
                            <th scope="col">Last Update</th>
                        </thead>
                        <tbody class="table-group-divider">
                            @if (Auth::user()->roles->id == 2)
                                <?php $disabled = $program->financial_id != null && count($disbursement_items) > 0 ? '' : 'disabled'; ?>
                                {{-- Action form --}}
                                <form method="post" id="formUpdateDisbursementItem"
                                    action="{{ route('disbursement_item.update') }}">
                                    @csrf
                                    @method('put')
                                    <tr>
                                        <td colspan="6"><span class="fst-italic" style="font-size: 12px">This
                                                function only accessible
                                                by Financial only.</span>
                                            <span class="float-end"> Action:
                                                {{-- action button --}}
                                                @if (count($disbursement_items) > 0 && $program->financial_id != null)
                                                    <button type="submit" form="formUpdateDisbursementItem"
                                                        class="px-1 d-inline" value="update" name="action">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor"
                                                            class="bi bi-pen-fill text-primary" viewBox="0 0 16 16">
                                                            <path
                                                                d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001" />
                                                        </svg>
                                                    </button>

                                                    <button type="submit" form="formUpdateDisbursementItem"
                                                        class="px-1 d-inline" value="delete" name="action"
                                                        onclick="return confirm('Confirm to delete this item?')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor"
                                                            class="bi bi-trash3 text-danger" viewBox="0 0 16 16">
                                                            <path
                                                                d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                                        </svg>
                                                    </button>
                                                @else
                                                    <span class="d-inline fst-italic">disabled</span>
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="border-warning">
                                        <td colspan="2">
                                            <select {{ $disabled }} name="id_disbursement"
                                                style="max-width: 100%;" class="form-select py-0 mx-auto"
                                                aria-label="Default select example" required>
                                                <option selected value="null">select item</option>
                                                @foreach ($disbursement_items as $disbursement_item)
                                                    <option value="{{ $disbursement_item->id }}">
                                                        {{ $disbursement_item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get('id_disbursement')" class="mt-2" />
                                        </td>
                                        <td>
                                            <input {{ $disabled }} type="number"
                                                class="form-control py-0 d-inline-block" name="price_disbursement"
                                                id="price" placeholder="price">
                                            <x-input-error :messages="$errors->get('price_disbursement')" class="mt-2" />
                                        </td>
                                        <td colspan="3">
                                            <input {{ $disabled }} type="file"
                                                class="form-control py-0 d-inline-block" name="reciept_disbursement"
                                                style="max-width: 100%;" placeholder="reciept.png">
                                            <x-input-error :messages="$errors->get('reciept_disbursement')" class="mt-2" />
                                        </td>
                                    </tr>
                                </form>
                            @endif
                            <?php $i = $disbursement_items->perPage() * ($disbursement_items->currentPage() - 1) + 1; ?>
                            @foreach ($disbursement_items as $disbursement_item)
                                <tr>
                                    <th scope="row">{{ $i }}</th>
                                    <td>{{ $disbursement_item->name }}</td>
                                    <td>{{ Number::currency($disbursement_item->price, in: 'IDR') }}</td>
                                    <td>{{ $disbursement_item->financial->name }}</td>
                                    <td class="text-center">
                                        <button data-bs-toggle="modal" data-bs-target="#recieptModal"
                                            onclick="setReceipt('{{ $disbursement_item->id }}' ,'{{ $disbursement_item->reciept }}', '{{ Auth::user()->roles_id }}' , 'none', '{{ Auth::user()->id }}', 'disbursement')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-receipt text-info d-inline"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27m.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0z" />
                                                <path
                                                    d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5m8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5" />
                                            </svg>
                                        </button>
                                    </td>
                                    <td>{{ $disbursement_item->updated_at->format('d/M/Y H:i:s') }}</td>
                                </tr>
                                <?php $i++; ?>
                            @endforeach
                            {{ $disbursement_items->links() }}
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Expense Items --}}
            <div class="col-lg-12 mb-4">
                <div class="bg-white p-3 sm:p-6 shadow rounded-lg">
                    <div class="h5 fw-bold d-inline-block">{{ __('Expense Item') }}</div>
                    @if (Auth::user()->id == $program->pic_id)
                        @if ($program->financial_id != null)
                            <!-- Add Expense Button Trigger Modal -->
                            <button class="btn text-dark float-end pt-0 pe-2" data-bs-toggle="modal"
                                data-bs-target="#addExpenseItemModal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-plus d-inline text-primary" viewBox="0 0 16 16">
                                    <path
                                        d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                </svg>
                                Add Expense Item
                            </button>

                            <!-- Add Expense Item Modal -->
                            <div class="modal fade" id="addExpenseItemModal" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-top border-warning">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Add
                                                {{ $program->name }}
                                                Expense Item</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form method="post" enctype="multipart/form-data"
                                            action="{{ route('expense_item.add', $program->id) }}">
                                            @csrf
                                            @method('put')
                                            <div class="modal-body">
                                                <div class="text-center mb-2">
                                                    <span class="ms-3 d-block" style="font-size: 12px">
                                                        <span class="text-danger ">*</span>
                                                        Don't use comma (,) or dot (.). Write the numbers only.
                                                    </span>
                                                </div>
                                                <div class="text-center mb-2">
                                                    <span class="ms-3 border-bottom pb-1 d-block"
                                                        style="font-size: 12px">
                                                        <span class="text-danger ">**</span>
                                                        Max size: 5Mb, Type: .jpg, .jpeg, .png, .heic.
                                                    </span>
                                                </div>
                                                <div class="row g-3 align-items-center">
                                                    <div class="col-3">
                                                        <label for="name"
                                                            class="form-label d-inline-block float-end">Item
                                                            Name</label>
                                                    </div>
                                                    <div class="col-8 ms-2">
                                                        <input type="text" class="form-control d-inline-block"
                                                            name="expense_name" id="name" required
                                                            value="{{ old('expense_name') }}">
                                                        <x-input-error :messages="$errors->get('expense_name')" class="mt-2" />
                                                    </div>
                                                </div>
                                                <div class="row g-3 align-items-center mt-1">
                                                    <div class="col-3">
                                                        <label for="price"
                                                            class="form-label d-inline-block float-end">Price<span
                                                                class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-8 ms-2">
                                                        <input type="number" class="form-control d-inline-block"
                                                            name="expense_price" id="price" required
                                                            value="{{ old('expense_price') }}">
                                                        <x-input-error :messages="$errors->get('expense_price')" class="mt-2" />
                                                    </div>
                                                </div>
                                                <div class="row g-3 align-items-center mt-1">
                                                    <div class="col-3">
                                                        <label for="unit"
                                                            class="form-label d-inline-block float-end">Unit</label>
                                                    </div>
                                                    <div class="col-8 ms-2">
                                                        <input type="text" class="form-control d-inline-block"
                                                            name="expense_unit" id="unit" required
                                                            value="{{ old('expense_unit') }}"
                                                            placeholder="pcs, person, box, etc..">
                                                        <x-input-error :messages="$errors->get('expense_unit')" class="mt-2" />
                                                    </div>
                                                </div>
                                                <div class="row g-3 align-items-center mt-1">
                                                    <div class="col-3">
                                                        <label for="qty"
                                                            class="form-label d-inline-block float-end">Quantity<span
                                                                class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-8 ms-2">
                                                        <input type="number" class="form-control d-inline-block"
                                                            name="expense_qty" id="qty" required
                                                            value="{{ old('expense_qty') }}">
                                                        <x-input-error :messages="$errors->get('expense_qty')" class="mt-2" />
                                                    </div>
                                                </div>
                                                <div class="row g-3 align-items-center mt-1">
                                                    <div class="col-3">
                                                        <label for="expense_reciept"
                                                            class="form-label d-inline-block float-end">Reciept<span
                                                                class="text-danger ">**</span></label>
                                                    </div>
                                                    <div class="col-8 ms-2">
                                                        <div class="row" id="receipt_upload">
                                                            <input type="file"
                                                                class="form-control form-control-sm ms-3"
                                                                style="width: 92%" name="expense_reciept"
                                                                id="expense_reciept"
                                                                value="{{ old('expense_reciept') }}">
                                                            <x-input-error :messages="$errors->get('expense_reciept')" class="mt-2" />
                                                        </div>
                                                        <div class="row" id="receipt_same" hidden>
                                                            <div class="input-group input-group-sm">
                                                                <label for="expense_receipt_same"
                                                                    class="input-group-text">Same as item</label>
                                                                <select name="expense_receipt_same"
                                                                    class="form-select py-0 d-inline"
                                                                    id="expense_receipt_same">
                                                                    <?php $i = 1; ?>
                                                                    @foreach ($inverted_expense_items as $expense_item)
                                                                        <?php $default = $i === 1 ? 'selected' : ''; ?>
                                                                        <option value="{{ $expense_item->id }}"
                                                                            {{ $default }}>
                                                                            {{ $expense_item->name }}</option>
                                                                        <?php $i++; ?>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        @if ($program->expense > 0)
                                                            <label for="same_receipt_check"
                                                                class="inline-flex items-center mt-1"
                                                                onclick="sameReceipt()">
                                                                <input id="same_receipt_check" type="checkbox"
                                                                    name="same_receipt_check"
                                                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                                                <span
                                                                    class="ms-2 text-sm text-gray-600">{{ __('same as exist item') }}</span>
                                                            </label>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn shadow border-primary">Add</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <span class="float-end text-secondary fst-italic">Waiting for Budget Approval</span>
                        @endif
                    @endif
                    {{-- Expense Item Table --}}
                    <table class="table">
                        <thead class="table-primary">
                            <th scope="col">#</th>
                            <th scope="col">Item</th>
                            <th scope="col">Price</th>
                            <th scope="col">Unit</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                            <th scope="col">Reciept</th>
                            <th scope="col">Last Update</th>
                        </thead>
                        <tbody class="table-group-divider">
                            @if (Auth::user()->id == $program->pic_id)
                                {{-- Action form --}}
                                <form method="post" id="formUpdateExpenseItem" enctype="multipart/form-data"
                                    action="{{ route('expense_item.update', ['pic_id' => $program->pic_id]) }}">
                                    @csrf
                                    @method('put')
                                    <tr>
                                        <td colspan="5"><span class="fst-italic" style="font-size: 12px">This
                                                function only accessible
                                                by PIC only.</span></td>
                                        <td colspan="3" class="text-end"> Action:
                                            {{-- action button --}}
                                            @if (count($expense_items) > 0 && $program->financial_id != null)
                                                <button type="submit" form="formUpdateExpenseItem"
                                                    class="px-1 d-inline" value="update" name="action">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor"
                                                        class="bi bi-pen-fill text-primary" viewBox="0 0 16 16">
                                                        <path
                                                            d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001" />
                                                    </svg>
                                                </button>

                                                <button type="submit" form="formUpdateExpenseItem"
                                                    class="px-1 d-inline" value="delete" name="action"
                                                    onclick="return confirm('Confirm to delete this item?')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor"
                                                        class="bi bi-trash3 text-danger" viewBox="0 0 16 16">
                                                        <path
                                                            d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                                    </svg>
                                                </button>
                                            @else
                                                <span class="bg-light text-secondary fst-italic">disabled</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="border-warning">
                                        <?php $disabled = count($expense_items) > 0 && $program->financial_id != null ? '' : 'disabled'; ?>
                                        <td colspan="2">
                                            <select {{ $disabled }} name="id" id="pic"
                                                class="form-select py-0" aria-label="Default select example" required>
                                                <option selected value="null">select item</option>
                                                @foreach ($expense_items as $expense_item)
                                                    @if ($expense_item->financial_id == null)
                                                        <option value="{{ $expense_item->id }}">
                                                            {{ $expense_item->name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get('id_expense')" class="mt-2" />
                                        </td>
                                        <td>
                                            <input {{ $disabled }} type="number"
                                                class="form-control py-0 d-inline-block" name="price_expense"
                                                id="price" placeholder="price">
                                            <x-input-error :messages="$errors->get('price_expense')" class="mt-2" />
                                        </td>
                                        <td>
                                            <input {{ $disabled }} type="text"
                                                class="form-control py-0 d-inline-block" style="max-width: 110px;"
                                                name="unit_expense" id="unit" placeholder="unit">
                                            <x-input-error :messages="$errors->get('unit_expense')" class="mt-2" />
                                        </td>
                                        <td>
                                            <input {{ $disabled }} type="number"
                                                class="form-control py-0 d-inline-block" name="quantity_expense"
                                                id="qty" style="max-width: 70px;" placeholder="qty">
                                            <x-input-error :messages="$errors->get('quantity_expense')" class="mt-2" />
                                        </td>
                                        <td colspan="3">
                                            <input {{ $disabled }} type="file"
                                                class="form-control py-0 d-inline-block" name="reciept_expense"
                                                style="max-width: 100%;" placeholder="reciept.png">
                                            <x-input-error :messages="$errors->get('reciept_expense')" class="mt-2" />
                                        </td>
                                    </tr>
                                </form>
                            @endif
                            <?php $i = $expense_items->perPage() * ($expense_items->currentPage() - 1) + 1; ?>
                            @foreach ($expense_items as $expense_item)
                                <tr>
                                    <th scope="row">{{ $i }}</th>
                                    <td>{{ $expense_item->name }}</td>
                                    <td>{{ $expense_item->price }}</td>
                                    <td>{{ $expense_item->unit }}</td>
                                    <td class="ps-4">{{ $expense_item->qty }}</td>
                                    <td>{{ Number::currency($expense_item->total_price, in: 'IDR') }}</td>
                                    <td>
                                        <?php
                                        $financial_name = $expense_item->financial_id != null ? $expense_item->financial->name : 'none';
                                        ?>
                                        <button data-bs-toggle="modal" data-bs-target="#recieptModal"
                                            onclick="setReceipt('{{ $expense_item->id }}' ,'{{ $expense_item->reciept }}', '{{ Auth::user()->roles_id }}' , '{{ $financial_name }}', '{{ Auth::user()->id }}', 'expense')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-receipt text-info d-inline"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27m.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0z" />
                                                <path
                                                    d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5m8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5" />
                                            </svg>
                                            @if ($expense_item->financial_id != null)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor"
                                                    class="bi bi-check-circle-fill text-success d-inline"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-ban text-secondary d-inline"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M15 8a6.97 6.97 0 0 0-1.71-4.584l-9.874 9.875A7 7 0 0 0 15 8M2.71 12.584l9.874-9.875a7 7 0 0 0-9.874 9.874ZM16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0" />
                                                </svg>
                                            @endif
                                        </button>
                                    </td>
                                    <td>{{ $expense_item->updated_at->format('d/M/Y H:i:s') }}</td>
                                </tr>
                                <?php $i++; ?>
                            @endforeach
                            {{ $expense_items->links() }}
                        </tbody>
                        <!-- Receipt Modal -->
                        <div class="modal fade" id="recieptModal" tabindex="-1" aria-labelledby="recieptModal"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header py-1 bg-info-subtle">
                                        <h1 class="modal-title fs-5">Receipt</h1>
                                        <span id="validationReceipt" class="fst-italic text-secondary mx-auto"></span>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body pt-0">
                                        <p class="mb-0 text-center">
                                            <span class="ms-auto mb-0 d-inline-block" id="receiptTitle">Tess</span>
                                            <a class="d-inline-block mt-0 align-middle" id="downloadReceipt" download>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-download text-primary"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                                                    <path
                                                        d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z" />
                                                </svg>
                                            </a>
                                        </p>
                                        <img id="receiptImage" alt="reciept image" class="rounded"
                                            style="height: auto; width: 100%;">
                                    </div>
                                    {{-- Feature for Financial --}}
                                    <div id="receiptFooter">
                                        <p class="me-auto">Action: <span class="fst-italic text-secondary">this
                                                feature only appear
                                                to Financial</span></p>
                                        <form method="post" id="formValidateExpenseItem"
                                            enctype="multipart/form-data"
                                            action="{{ route('expense_item.validate') }}">
                                            @csrf
                                            @method('put')
                                            <input type="hidden" id="receiptId" name="id" value="">
                                            <button type="submit" id="receiptButton" value=""
                                                form="formValidateExpenseItem" name="financial_id"
                                                data-bs-dismiss="modal">
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </table>
                </div>
            </div>
        </div>
        <div class="row mx-auto sm:px-6 px-2">

        </div>
    </div>
    {{-- script --}}
    <script>
        let receiptImage = document.getElementById('receiptImage');
        let downloadReceipt = document.getElementById('downloadReceipt');
        let receiptName = document.getElementById('receiptTitle');
        let validationReceipt = document.getElementById('validationReceipt');
        let receiptFooter = document.getElementById('receiptFooter');
        let receiptId = document.getElementById('receiptId');
        let receiptButton = document.getElementById('receiptButton');

        function setReceipt(id, receipt, role, financial_name, financial_id, item) {
            if (item == 'disbursement') {
                validationReceipt.innerText = '';
                receiptImage.setAttribute('src', '/storage/images/receipt/disbursement/' + receipt);
                downloadReceipt.setAttribute('href', '/storage/images/receipt/disbursement/' + receipt);
                receiptName.innerText = receipt;
                receiptFooter.setAttribute('class', 'hidden');
            } else {
                receiptImage.setAttribute('src', '/storage/images/receipt/expense/' + receipt);
                downloadReceipt.setAttribute('href', '/storage/images/receipt/expense/' + receipt);
                receiptId.setAttribute('value', id);
                receiptName.innerText = receipt;
                // Feature for Financial
                if (role == 2) {
                    receiptFooter.setAttribute('class', 'modal-footer');
                } else {
                    receiptFooter.setAttribute('class', 'hidden')
                }
                // check for validated item
                if (financial_name != 'none') {
                    validationReceipt.innerText = 'Validated by ' + financial_name;
                    receiptButton.innerText = 'UNVALIDATE';
                    receiptButton.setAttribute('value', '');
                    receiptButton.setAttribute('class', 'btn text-danger shadow');
                    receiptButton.setAttribute('onclick', 'return confirm(\'Confirm to UNVALIDATE this receipt?\')');
                } else {
                    validationReceipt.innerText = 'Unvalidated';
                    receiptButton.innerText = 'VALIDATE';
                    receiptButton.setAttribute('value', financial_id);
                    receiptButton.setAttribute('class', 'btn text-success shadow');
                    receiptButton.setAttribute('onclick', 'return confirm(\'Confirm to VALIDATE this receipt?\')');
                }
            }
        }

        let receiptUpload = document.getElementById('receipt_upload');
        let receiptSame = document.getElementById('receipt_same');
        let sameReceiptCheck = document.getElementById('same_receipt_check');

        function sameReceipt() {
            if (sameReceiptCheck.checked == true) {
                receiptUpload.setAttribute('hidden', '');
                receiptSame.removeAttribute('hidden');
            } else {
                receiptUpload.removeAttribute('hidden');
                receiptSame.setAttribute('hidden', '');
            }
        }
    </script>
</x-app-layout>
