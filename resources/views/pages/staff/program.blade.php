<x-app-layout>
    <?php $auth_user = Auth::user(); ?>
    <x-slot name="header">
        <a href="{{ route('department') }}" class="text-decoration-none text-primary-emphasis"><span
                class="fw-light">{{ 'Department' }}</span></a>
        <i class="bi bi-chevron-compact-right me-2"></i>{{ __('Program') }}
    </x-slot>

    <div class="container px-4">
        <div class="row gx-3">
            <div class="col-lg-6 col-12">
                {{-- Details --}}
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card shadow-sm p-3">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex border-primary border-bottom pb-0">
                                        <span class="h4 text-primary-emphasis fw-normal">
                                            {{ $program->name }}
                                        </span>
                                        @if ($auth_user->id == $program->department->manager_id)
                                            <button
                                                onclick="confirmation('{{ route('program.delete', ['id' => $program->id]) }}','Are you sure want to delete {{ $program->name }} Program?')"
                                                class="text-decoration-none ms-auto mb-2 btn btn-sm btn-secondary">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-lg-4 col-5 px-0 text-end fw-light">
                                    {{ 'Total Budget :' }}
                                </div>
                                <div class="col-lg-8 col-7 ps-1 pe-0">
                                    {{ format_currency($program->budget) }}
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-4 col-5 px-0 text-end fw-light">
                                    {{ 'Total Disbursement :' }}
                                </div>
                                <div class="col-lg-8 col-7 ps-1 pe-0">
                                    {{ format_currency($program->disbursement) }}
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-4 col-5 px-0 text-end fw-light">
                                    {{ 'Total Expense :' }}
                                </div>
                                <div class="col-lg-8 col-7 ps-1 pe-0">
                                    {{ format_currency($program->expense) }}
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-4 col-5 px-0 text-end fw-light">
                                    {{ 'Total Staff :' }}
                                </div>
                                <div class="col-lg-8 col-7 ps-1 pe-0">
                                    {{ $program->staff->count() . ' staff' }}
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-4 col-5 px-0 text-end fw-light">
                                    {{ 'Budget Approval :' }}
                                </div>
                                <div class="col-lg-8 col-7 ps-1 pe-0 text-nowrap scroll-x-hidden">
                                    <span>
                                        {{ $program->financial_id > 0 ? 'Approved by ' . $program->financial->name : 'Not Approved' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Percentage --}}
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card shadow-sm p-3">
                            <?php
                            $d = $program->disbursement;
                            $e = $program->expense;
                            $b = $program->budget;
                            $disbursement_percentage = $b == 0 ? 0 : round(($d / $b) * 100);
                            $expense_percentage = $d == 0 ? 0 : round(($e / $d) * 100);
                            
                            function color($value)
                            {
                                if ($value < 85) {
                                    return 'primary';
                                } elseif ($value <= 100) {
                                    return 'success';
                                } else {
                                    return 'secondary';
                                }
                            }
                            ?>
                            <div class="row">
                                <div class="col-lg-3 col-5">
                                    {{ '% Disbursement' }}
                                </div>
                                <div class="col-lg-9 col-7 d-flex">
                                    <div class="progress my-auto" role="progressbar" aria-label="Disbursement"
                                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                                        <div class="progress-bar bg-{{ color($disbursement_percentage) }}"
                                            style="width: {{ $disbursement_percentage }}%">
                                            {{ $disbursement_percentage }}%</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-3 col-5">
                                    {{ '% Expense' }}
                                </div>
                                <div class="col-lg-9 col-7 d-flex">
                                    <div class="progress my-auto" role="progressbar" aria-label="Expense"
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
            <div class="col-lg-6 col-12">
                {{-- Content Tab Navigation --}}
                <div class="row mt-4">
                    <div class="col-12">
                        <ul class="nav nav-tabs mx-0 border-0 mt-2">
                            <li class="nav-item"><a id="tab_1" onclick="show_tab(1)"
                                    class="nav-link py-2 border {{ $default_tab == 1 ? 'active bg-secondary text-white' : 'bg-white' }}">
                                    <i class="bi bi-list-columns-reverse d-lg-none {{ $default_tab == 1 ? 'me-2' : '' }}"
                                        id="tab_icon_1"></i><span
                                        class="d-lg-inline {{ $default_tab == 1 ? '' : 'd-none' }}"
                                        id="tab_span_1">{{ 'Budget' }}</span>
                                </a>
                            </li>
                            <li class="nav-item"><a id="tab_2" onclick="show_tab(2)"
                                    class="nav-link py-2 border {{ $default_tab == 2 ? 'active bg-secondary text-white' : 'bg-white' }}">
                                    <i class="bi bi-box-arrow-in-down d-lg-none {{ $default_tab == 2 ? 'me-2' : '' }}"
                                        id="tab_icon_2"></i><span
                                        class="d-lg-inline {{ $default_tab == 2 ? '' : 'd-none' }}"
                                        id="tab_span_2">{{ 'Disbursement' }}</span>
                                </a>
                            </li>
                            <li class="nav-item"><a id="tab_3" onclick="show_tab(3)"
                                    class="nav-link py-2 border {{ $default_tab == 3 ? 'active bg-secondary text-white' : 'bg-white' }}">
                                    <i class="bi bi-box-arrow-up d-lg-none {{ $default_tab == 3 ? 'me-2' : '' }}"
                                        id="tab_icon_3"></i><span
                                        class="d-lg-inline {{ $default_tab == 3 ? '' : 'd-none' }}"
                                        id="tab_span_3">{{ 'Expense' }}</span>
                                </a>
                            </li>
                            <li class="nav-item"><a id="tab_4" onclick="show_tab(4)"
                                    class="nav-link py-2 border {{ $default_tab == 4 ? 'active bg-secondary text-white' : 'bg-white' }}">
                                    <i class="bi bi-people d-lg-none {{ $default_tab == 4 ? 'me-2' : '' }}"
                                        id="tab_icon_4"></i><span
                                        class="d-lg-inline {{ $default_tab == 4 ? '' : 'd-none' }}"
                                        id="tab_span_4">{{ 'Staff' }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                {{-- Budget list --}}
                <div id="content_1" {{ $default_tab == 1 ? '' : 'hidden' }}>
                    {{-- Budget Filter --}}
                    <div class="row justify-content-center">
                        <div class="col-12 ">
                            <nav class="navbar rounded bg-white shadow-sm p-2">
                                <form method="post" id="formBudgetFilter"
                                    action="{{ route('program.budget.filter', ['id' => $program->id]) }}">
                                    @csrf
                                    @method('put')
                                    <?php
                                    $is_budget_name = $filter['budget']['category'] == 'name';
                                    $budget_name_order = $filter['budget']['order'] == 'desc' ? 'desc' : 'asc';
                                    $budget_name_icon = $is_budget_name && $budget_name_order == 'asc' ? 'up' : 'down';
                                    $budget_name_value = $is_budget_name && $budget_name_order == 'asc' ? 'desc' : 'asc';
                                    ?>
                                    <input type="hidden" name="category" value="name">
                                </form>
                                <form method="post" id="formBudgetFilter2"
                                    action="{{ route('program.budget.filter', ['id' => $program->id]) }}">
                                    @csrf
                                    @method('put')
                                    <?php
                                    $is_budget_price = $filter['budget']['category'] == 'total_price';
                                    $budget_price_order = $filter['budget']['order'] == 'desc' ? 'desc' : 'asc';
                                    $budget_price_icon = $is_budget_price && $budget_price_order == 'asc' ? 'up' : 'down';
                                    $budget_price_value = $is_budget_price && $budget_price_order == 'asc' ? 'desc' : 'asc';
                                    ?>
                                    <input type="hidden" name="category" value="total_price">
                                </form>
                                <div class="container d-block px-0 ">
                                    <div class="row">
                                        <div class="col-12 d-flex">
                                            <div class="input-group bg-body-tertiary rounded">
                                                <button type="button" form="formBudgetFilter"
                                                    class="btn btn-sm rounded-0 rounded-start text-light bg-secondary">
                                                    <i class="bi bi-funnel-fill"></i>
                                                    <span
                                                        class="ms-1 ps-2 border-start border-light d-none d-md-inline">{{ 'Filter' }}</span>
                                                </button>
                                                <button type="submit" form="formBudgetFilter"
                                                    class="btn btn-sm btn-outline-secondary border-0 rounded-0 my-0"
                                                    name="order" value="{{ $budget_name_value }}">
                                                    <span class="">{{ 'Name' }}</span>
                                                    <i class="bi bi-sort-numeric-{{ $budget_name_icon }}-alt"></i>
                                                </button>
                                                <button type="submit" form="formBudgetFilter2"
                                                    class="btn btn-sm btn-outline-secondary border-0 rounded-0 my-0"
                                                    name="order" value="{{ $budget_price_value }}">
                                                    <span class="">{{ 'Price' }}</span>
                                                    <i class="bi bi-sort-numeric-{{ $budget_price_icon }}-alt"></i>
                                                </button>
                                            </div>
                                            @if ($auth_user->id == $program->pic_id)
                                                <!-- Button trigger Add Budget Modal -->
                                                <div
                                                    onclick="{{ $program->financial_id <= 0 ? '' : 'notif("You can not add Budget Item after Program Budget approved by Financial Officer.")' }}">
                                                    <button
                                                        class="ms-2 btn btn-sm btn-{{ $program->financial_id <= 0 ? 'primary' : 'secondary disabled' }}"
                                                        data-bs-toggle="modal" data-bs-target="#addProgramBudget">
                                                        <i class="bi bi-plus-lg"></i>
                                                    </button>
                                                </div>
                                                @if ($program->financial_id <= 0)
                                                    <!-- Add Budget Modal -->
                                                    <div class="modal fade" id="addProgramBudget" tabindex="-1"
                                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content shadow mx-3">
                                                                <div class="modal-header py-1 ps-3 pe-2">
                                                                    <span
                                                                        class="modal-title fs-5 text-primary-emphasis"
                                                                        id="exampleModalLabel"><i
                                                                            class="bi bi-journal-plus border-secondary border-end me-2 pe-2"></i>
                                                                        New Budget Item
                                                                    </span>
                                                                    <button type="button" class="btn btn-sm ms-auto"
                                                                        data-bs-dismiss="modal" aria-label="Close"><i
                                                                            class="bi bi-x-lg"></i></button>
                                                                </div>
                                                                <form method="post"
                                                                    action="{{ route('program.budget.add', ['id' => $program->id]) }}">
                                                                    @csrf
                                                                    @method('put')
                                                                    <div class="modal-body bg-light">
                                                                        <div class="row justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_budget_name"
                                                                                    class="form-label d-inline-block">Name</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="text"
                                                                                    class="form-control form-control-sm d-inline-block"
                                                                                    name="name"
                                                                                    id="add_budget_name"
                                                                                    value="{{ old('name') }}"
                                                                                    required>
                                                                                <x-input-error :messages="$errors->get('name')"
                                                                                    class="mt-2" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_budget_price"
                                                                                    class="form-label d-inline-block">Price</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="number"
                                                                                    class="form-control form-control-sm d-inline-block"
                                                                                    name="price"
                                                                                    id="add_budget_price"
                                                                                    value="{{ old('price') }}"
                                                                                    oninput="return setTotalPrice('budget')"
                                                                                    placeholder="Type numbers only"
                                                                                    required>
                                                                                <x-input-error :messages="$errors->get('price')"
                                                                                    class="mt-2" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_budget_unit"
                                                                                    class="form-label d-inline-block">Unit</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="text"
                                                                                    class="form-control form-control-sm d-inline-block"
                                                                                    name="unit"
                                                                                    placeholder="gram, ml, pcs, etc.."
                                                                                    id="add_budget_unit"
                                                                                    value="{{ old('unit') }}"
                                                                                    required>
                                                                                <x-input-error :messages="$errors->get('unit')"
                                                                                    class="mt-2" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_budget_qty"
                                                                                    class="form-label d-inline-block">Quantity</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="number"
                                                                                    class="form-control form-control-sm d-inline-block"
                                                                                    name="qty" id="add_budget_qty"
                                                                                    value="{{ old('qty') }}"
                                                                                    oninput="return setTotalPrice('budget')"
                                                                                    placeholder="Type numbers only"
                                                                                    required>
                                                                                <x-input-error :messages="$errors->get('qty')"
                                                                                    class="mt-2" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <span>{{ 'Total Price' }}</span>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <span id="add_budget_total"></span>
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
                                            @endif
                                            @if ($auth_user->roles_id == 2)
                                                {{-- Validation Button --}}
                                                <button
                                                    class="ms-2 btn btn-sm btn-{{ $program->financial_id > 0 ? 'success' : 'secondary' }}"
                                                    onclick="confirmation('{{ route('program.budget.validate', ['id' => $program->id, 'valid' => $program->financial_id > 0 ? 0 : 1]) }}', '{{ $program->financial_id > 0 ? 'Are you sure want to UNPROVE ' . $program->name . ' Program Budget?' : 'Are you sure want to APPROVE ' . $program->name . ' Program Budget?' }}')">
                                                    <i
                                                        class="bi bi-{{ $program->financial_id > 0 ? 'check2' : 'ban' }}"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </nav>
                        </div>
                    </div>
                    {{-- Budget list item --}}
                    <div class="row">
                        <div class="col-12">
                            <div
                                class="scroll-container-3 scroll-container-lg-2 bg-secondary bg-opacity-25 px-2 pt-2 rounded mt-2">
                                <?php
                                $i = 1;
                                ?>
                                @foreach ($budget_list as $budget)
                                    <div class="card card-bg-hover shadow mb-2 py-1">
                                        <div class="row">
                                            <div class="col-12 d-flex">
                                                <span
                                                    class="text-primary-emphasis ms-2 my-auto">{{ $budget->name }}</span>
                                                <span
                                                    class="fw-light ms-2 d-none d-lg-flex my-auto">{{ '- ' . format_currency($budget->price) . ' /' . $budget->unit }}</span>
                                                <span
                                                    class="fw-light ms-auto d-none d-lg-flex my-auto">{{ 'total (' . $budget->qty . ') : ' }}</span>
                                                <span
                                                    class="fw-normal mx-2 d-none d-lg-flex my-auto">{{ format_currency($budget->total_price) }}</span>
                                                @if ($auth_user->id == $program->pic_id && $program->financial_id <= 0)
                                                    {{-- Delete Button --}}
                                                    <button class="ms-auto ms-lg-1 me-2 btn btn-sm btn-danger"
                                                        onclick="confirmation('{{ route('program.budget.delete', ['id' => $budget->id]) }}', '{{ 'Are you sure want to delete ' . $budget->name . ' from ' . $program->name . ' Program Budget?' }}')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row d-lg-none">
                                            <div class="col-12 d-flex">
                                                <span
                                                    class="fw-light ms-2 ">{{ format_currency($budget->price) . ' /' . $budget->unit }}</span>
                                                <span
                                                    class="fw-light ms-auto">{{ 'total (' . $budget->qty . ') : ' }}</span>
                                                <span
                                                    class="fw-normal mx-2">{{ format_currency($budget->total_price) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $i++; ?>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Disbursement list --}}
                <div id="content_2" {{ $default_tab == 2 ? '' : 'hidden' }}>
                    {{-- Disbursement Filter --}}
                    <div class="row justify-content-center">
                        <div class="col-12 ">
                            <nav class="navbar rounded bg-white shadow-sm p-2">
                                <form method="post" id="formDisbursementFilter"
                                    action="{{ route('program.disbursement.filter', ['id' => $program->id]) }}">
                                    @csrf
                                    @method('put')
                                    <?php
                                    $is_disbursement_price = $filter['disbursement']['category'] == 'price';
                                    $disbursement_price_order = $filter['disbursement']['order'] == 'desc' ? 'desc' : 'asc';
                                    $disbursement_price_icon = $is_disbursement_price && $disbursement_price_order == 'asc' ? 'up' : 'down';
                                    $disbursement_price_value = $is_disbursement_price && $disbursement_price_order == 'asc' ? 'desc' : 'asc';
                                    ?>
                                    <input type="hidden" name="category" value="price">
                                </form>
                                <form method="post" id="formDisbursementFilter2"
                                    action="{{ route('program.disbursement.filter', ['id' => $program->id]) }}">
                                    @csrf
                                    @method('put')
                                    <?php
                                    $is_disbursement_trashed = $filter['disbursement']['trashed'] == 'trashed';
                                    $disbursement_trashed_class = $is_disbursement_trashed ? 'active' : '';
                                    $disbursement_trashed_value = $is_disbursement_trashed ? 'active' : 'trashed';
                                    ?>
                                    <input type="hidden" name="trashed" value="{{ $disbursement_trashed_value }}">
                                </form>
                                <div class="container d-block px-0">
                                    <div class="row">
                                        <div class="col-12 d-flex">
                                            <div class="input-group bg-body-tertiary rounded ">
                                                <button type="button"
                                                    class="btn btn-sm rounded-0 rounded-start text-light bg-secondary">
                                                    <i class="bi bi-funnel-fill"></i>
                                                    <span
                                                        class="ms-1 ps-2 border-start border-light d-none d-md-inline">{{ 'Filter' }}</span>
                                                </button>
                                                <button type="submit" form="formDisbursementFilter"
                                                    class="btn btn-sm btn-outline-secondary border-0 rounded-0 my-0"
                                                    name="order" value="{{ $disbursement_price_value }}">
                                                    <span class="">{{ 'Price' }}</span>
                                                    <i
                                                        class="bi bi-sort-numeric-{{ $disbursement_price_icon }}-alt"></i>
                                                </button>
                                                <button type="submit" form="formDisbursementFilter2"
                                                    class="btn btn-sm btn-outline-secondary {{ $disbursement_trashed_class }} border-0 rounded-0 my-0">
                                                    <span class="">{{ 'Trashed' }}</span>
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </div>
                                            <!-- Button trigger Add Disbursement Modal -->
                                            @if ($auth_user->roles_id == 2)
                                                <div
                                                    onclick="{{ $program->financial_id > 0 ? '' : 'notif("You can add Disbursement Item after Program Budget approved by Financial Officer.")' }}">
                                                    <button
                                                        class="ms-2 btn btn-sm btn-{{ $program->financial_id > 0 ? 'primary' : 'secondary disabled' }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#addProgramDisbursement">
                                                        <i class="bi bi-plus-lg"></i>
                                                    </button>
                                                </div>
                                                <!-- Add Disbursement Modal -->
                                                @if ($program->financial_id > 0)
                                                    <div class="modal fade" id="addProgramDisbursement"
                                                        tabindex="-1" aria-labelledby="exampleModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content shadow mx-3">
                                                                <div class="modal-header py-1 ps-3 pe-2">
                                                                    <span
                                                                        class="modal-title fs-5 text-primary-emphasis"
                                                                        id="exampleModalLabel"><i
                                                                            class="bi bi-wallet2 border-secondary border-end me-2 pe-2"></i>
                                                                        New Disbursement Item
                                                                    </span>
                                                                    <button type="button" class="btn btn-sm ms-auto"
                                                                        data-bs-dismiss="modal" aria-label="Close"><i
                                                                            class="bi bi-x-lg"></i></button>
                                                                </div>
                                                                <form method="post" enctype="multipart/form-data"
                                                                    action="{{ route('program.disbursement.add', ['id' => $program->id]) }}">
                                                                    @csrf
                                                                    @method('put')
                                                                    <div class="modal-body bg-light">
                                                                        <div class="row justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_disbursement_name"
                                                                                    class="form-label d-inline-block">Name</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="text"
                                                                                    class="form-control form-control-sm d-inline-block"
                                                                                    name="name"
                                                                                    id="add_disbursement_name"
                                                                                    value="{{ old('name') }}"
                                                                                    required>
                                                                                <x-input-error :messages="$errors->get('name')"
                                                                                    class="mt-2" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_disbursement_price"
                                                                                    class="form-label d-inline-block">Price</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="number"
                                                                                    class="form-control form-control-sm d-inline-block"
                                                                                    name="price"
                                                                                    id="add_disbursement_price"
                                                                                    value="{{ old('price') }}"
                                                                                    placeholder="Type numbers only"
                                                                                    required>
                                                                                <x-input-error :messages="$errors->get('price')"
                                                                                    class="mt-2" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_disbursement_letter"
                                                                                    class="form-label d-inline-block">Letter</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <select name="letter_id"
                                                                                    class="form-select py-0 d-inline"
                                                                                    id="add_disbursement_letter">
                                                                                    <?php $i = 1; ?>
                                                                                    @foreach ($disbursement_letter_list as $disbursement_letter)
                                                                                        <?php $default = $i === 1 ? 'selected' : ''; ?>
                                                                                        @if (!$disbursement_letter->disbursement)
                                                                                            <option
                                                                                                value="{{ $disbursement_letter->id }}"
                                                                                                {{ $default }}>
                                                                                                {{ 'disbursement_letter... ' . $i++ }}
                                                                                            </option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_disbursement_receipt"
                                                                                    class="form-label d-inline-block">{{ 'Receipt' }}</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="file"
                                                                                    class="form-control form-control-sm"
                                                                                    name="receipt"
                                                                                    id="add_disbursement_receipt"
                                                                                    value="{{ old('receipt') }}">
                                                                                <x-input-error :messages="$errors->get('receipt')"
                                                                                    class="mt-2" />
                                                                                <label for="blaterian_balance"
                                                                                    class="inline-flex items-center mt-1"
                                                                                    onclick="disbursementBlaterianBalance()">
                                                                                    <input id="blaterian_balance"
                                                                                        type="checkbox"
                                                                                        name="blaterian_balance"
                                                                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                                                                    <span
                                                                                        class="ms-2 text-sm text-gray-600">{{ __('For Blaterian Balance') }}</span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2 justify-content-center"
                                                                            id="add_blaterian_disbursement_container"
                                                                            hidden>
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_disbursement_price"
                                                                                    class="form-label d-inline-block">{{ 'Blaterian' }}</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <div
                                                                                    class="input-group input-group-sm">
                                                                                    <select
                                                                                        name="blaterian_disbursement"
                                                                                        class="form-select py-0 d-inline"
                                                                                        id="add_blaterian_disbursement">
                                                                                        <?php $i = 1;
                                                                                        $balances = ['foods', 'goods'];
                                                                                        ?>
                                                                                        @foreach ($balances as $balance)
                                                                                            <?php $default = $i === 1 ? 'selected' : ''; ?>
                                                                                            <option
                                                                                                value="{{ $balance }}"
                                                                                                {{ $default }}>
                                                                                                {{ ucfirst($balance) }}
                                                                                            </option>
                                                                                            <?php $i++; ?>
                                                                                        @endforeach
                                                                                    </select>
                                                                                    <label
                                                                                        for="add_blaterian_disbursement"
                                                                                        class="input-group-text">{{ 'Balance' }}</label>
                                                                                </div>
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
                                            @endif
                                        </div>
                                    </div>
                                    {{-- Disbursement Letter List --}}
                                    <div class="row mt-2">
                                        <div class="col-12 d-flex">
                                            <span
                                                class="fw-light d-none d-lg-inline me-1">{{ 'Disbursement ' }}</span>
                                            <span class="fw-light">{{ 'Letter :' }}</span>
                                            <?php $i = 1; ?>
                                            @foreach ($disbursement_letter_list as $disbursement_letter)
                                                @if (!$disbursement_letter->disbursement)
                                                    <button class="ms-1 btn btn-sm btn-light position-relative"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#disbursementLetterModal"
                                                        onclick="setLetter({{ $disbursement_letter }}, true)">
                                                        <span
                                                            class="fw-normal border-end border-secondary text-primary me-1">
                                                            {{ $i++ }}
                                                        </span>
                                                        <i class="bi bi-envelope-exclamation"> </i>
                                                    </button>
                                                @endif
                                            @endforeach
                                            <!-- Disbursement Letter Modal -->
                                            <div class="modal fade" id="disbursementLetterModal" tabindex="-1"
                                                aria-labelledby="disbursementLetterModal" aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content shadow mx-3 mt-5">
                                                        <div class="modal-header py-1 ps-3 pe-2">
                                                            <span class="modal-title fs-5 text-primary-emphasis">
                                                                <i
                                                                    class="bi bi-envelope border-secondary border-end me-2 pe-2"></i>
                                                                {{ 'Disbursement Letter' }}
                                                            </span>
                                                            <button type="button" class="btn btn-sm ms-auto"
                                                                data-bs-dismiss="modal" aria-label="Close">
                                                                <i class="bi bi-x-lg"></i>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body bg-light p-1 px-3">
                                                            <div class="row justify-content-center mt-2">
                                                                <div class="col-12 d-flex ratio ratio-16x9">
                                                                    <iframe id="disbursement_letter" src=""
                                                                        frameborder="0"></iframe>
                                                                </div>
                                                            </div>
                                                            <div class="row justify-content-center">
                                                                <div class="col-12 d-flex">
                                                                    <a href="" target="blank"
                                                                        style="text-decoration-none" class="mx-auto"
                                                                        id="disbursement_letter_download" download>
                                                                        <button class="btn btn-sm btn-light">
                                                                            <span class="fw-light d-none d-lg-inline"
                                                                                id="disbursement_letter_name"></span>
                                                                            <span
                                                                                class="fw-light">{{ 'download' }}</span>
                                                                            <i class="bi bi-download text-primary"></i>
                                                                        </button>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer p-1 px-2">
                                                            <div class="row">
                                                                <div class="col-12 d-flex px-0">
                                                                    <div class="input-group input-group-sm ms-auto">
                                                                        @if ($auth_user->id == $program->pic_id)
                                                                            <a href=""
                                                                                id="disbursement_letter_delete"
                                                                                class="btn btn-sm btn-danger text-decoration-none">
                                                                                {{ 'Delete' }}
                                                                            </a>
                                                                        @endif
                                                                        <button data-bs-dismiss="modal"
                                                                            aria-label="Close"
                                                                            class="btn btn-sm btn-secondary ">{{ 'Close' }}</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Button trigger Add Disbursement Letter Modal -->
                                            @if ($auth_user->id == $program->pic_id)
                                                <div class="ms-auto"
                                                    onclick="{{ $program->financial_id > 0 ? '' : 'notif("You can add Disbursement Letter after Program Budget approved by Financial Officer.")' }}">
                                                    <button
                                                        class="btn btn-sm btn-{{ $program->financial_id > 0 ? 'primary' : 'secondary disabled' }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#addProgramDisbursementLetter">
                                                        <i class="bi bi-envelope-plus"></i>
                                                    </button>
                                                </div>
                                                @if ($program->financial_id > 0)
                                                    <!-- Add Disbursement Letter Modal -->
                                                    <div class="modal fade" id="addProgramDisbursementLetter"
                                                        tabindex="-1" aria-labelledby="exampleModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content shadow mx-3">
                                                                <div class="modal-header py-1 ps-3 pe-2">
                                                                    <span
                                                                        class="modal-title fs-5 text-primary-emphasis"
                                                                        id="exampleModalLabel"><i
                                                                            class="bi bi-wallet2 border-secondary border-end me-2 pe-2"></i>
                                                                        New Disbursement Letter
                                                                    </span>
                                                                    <button type="button" class="btn btn-sm ms-auto"
                                                                        data-bs-dismiss="modal" aria-label="Close"><i
                                                                            class="bi bi-x-lg"></i></button>
                                                                </div>
                                                                <form method="post" enctype="multipart/form-data"
                                                                    action="{{ route('program.disbursement.letter.add', ['id' => $program->id]) }}">
                                                                    @csrf
                                                                    @method('put')
                                                                    <div class="modal-body bg-light">
                                                                        <div class="row justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_disbursement_letter"
                                                                                    class="form-label d-inline-block">{{ 'Letter' }}</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="file"
                                                                                    class="form-control form-control-sm"
                                                                                    name="letter"
                                                                                    id="add_disbursement_letter"
                                                                                    value="{{ old('letter') }}">
                                                                                <x-input-error :messages="$errors->get('letter')"
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
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </nav>
                        </div>
                    </div>
                    {{-- Disbursement list item --}}
                    <div class="row">
                        <div class="col-12">
                            <div
                                class="scroll-container-3 scroll-container-lg-2 bg-secondary bg-opacity-25 px-2 pt-2 rounded mt-2">
                                <?php
                                $i = 1;
                                ?>
                                @foreach ($disbursement_list as $disbursement)
                                    <div class="card card-bg-hover shadow mb-2 py-1">
                                        <div class="row">
                                            <div class="col-12 d-flex">
                                                <span
                                                    class="text-primary-emphasis ms-2 my-auto scroll-x-hidden text-nowrap me-2">{{ $disbursement->name }}</span>
                                                <span
                                                    class="fw-light ms-2 d-none d-lg-flex my-auto scroll-x-hidden text-nowrap me-3">{{ 'by ' . $disbursement->financial->name }}</span>
                                                <span
                                                    class="fw-light ms-auto d-none d-lg-flex my-auto text-nowrap">{{ 'price : ' }}</span>
                                                <span
                                                    class="fw-normal mx-2 d-none d-lg-flex my-auto">{{ format_currency($disbursement->price) }}</span>
                                                {{-- Letter Trigger Button --}}
                                                <button class="ms-auto ms-lg-1 btn btn-sm btn-light position-relative"
                                                    data-bs-toggle="modal" data-bs-target="#disbursementLetterModal"
                                                    onclick="setLetter({{ $disbursement->letter }})">
                                                    <i class="bi bi-envelope-paper"> </i>
                                                </button>
                                                {{-- Receipt Trigger Button --}}
                                                <button class="mx-1 btn btn-sm btn-light" data-bs-toggle="modal"
                                                    data-bs-target="#receiptDisbursementModal"
                                                    onclick="setDisbursementReceipt('{{ $disbursement->reciept }}')">
                                                    <i class="bi bi-receipt"> </i>
                                                </button>
                                                @if ($auth_user->roles_id == 2)
                                                    @if (!$is_disbursement_trashed)
                                                        {{-- Delete Button --}}
                                                        <button class="me-2 btn btn-sm btn-danger"
                                                            onclick="confirmation('{{ route('program.disbursement.delete', ['id' => $disbursement->id]) }}', '{{ 'Are you sure want to delete ' . $disbursement->name . ' from ' . $program->name . ' Program Disbursement?' }}')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row d-lg-none">
                                            <div class="col-12 d-flex">
                                                <span
                                                    class="fw-light ms-2 ">{{ 'by ' . $disbursement->financial->name }}</span>
                                                <span class="fw-light ms-auto">{{ 'price : ' }}</span>
                                                <span
                                                    class="fw-normal mx-2">{{ format_currency($disbursement->price) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $i++; ?>
                                @endforeach
                            </div>
                        </div>
                        <!-- Disbursement Receipt Modal -->
                        <div class="modal fade" id="receiptDisbursementModal" tabindex="-1"
                            aria-labelledby="receiptDisbursementModal" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content shadow mx-3 mt-5">
                                    <div class="modal-header py-1 ps-3 pe-2">
                                        <span class="modal-title fs-5 text-primary-emphasis">
                                            <i class="bi bi-receipt border-secondary border-end me-2 pe-2"></i>
                                            {{ 'Disbursement Receipt' }}
                                        </span>
                                        <button type="button" class="btn btn-sm ms-auto" data-bs-dismiss="modal"
                                            aria-label="Close"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                    <div class="modal-body bg-light p-1 px-3">
                                        <div class="row justify-content-center mt-2">
                                            <div class="col-12 d-flex">
                                                <img src="" alt="image" class="rounded mx-auto"
                                                    style="width: 100%; height 100%;  object-fit: contain; max-height:320px;"
                                                    id="disbursement_receipt_image">
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-12 d-flex">
                                                <a href="" target="blank" style="text-decoration-none"
                                                    class="mx-auto" id="disbursement_receipt_download" download>
                                                    <button class="btn btn-sm btn-light">
                                                        <span class="fw-light" id="disbursement_receipt_name"></span>
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
                {{-- Expense list --}}
                <div id="content_3" {{ $default_tab == 3 ? '' : 'hidden' }}>
                    {{-- Expense Filter --}}
                    <div class="row justify-content-center">
                        <div class="col-12 ">
                            <nav class="navbar rounded bg-white shadow-sm p-2">
                                <form method="post" id="formExpenseFilter"
                                    action="{{ route('program.expense.filter', ['id' => $program->id]) }}">
                                    @csrf
                                    @method('put')
                                    <?php
                                    $is_expense_name = $filter['expense']['category'] == 'name';
                                    $expense_name_order = $filter['expense']['order'] == 'desc' ? 'desc' : 'asc';
                                    $expense_name_icon = $is_expense_name && $expense_name_order == 'asc' ? 'up' : 'down';
                                    $expense_name_value = $is_expense_name && $expense_name_order == 'asc' ? 'desc' : 'asc';
                                    ?>
                                    <input type="hidden" name="category" value="name">
                                </form>
                                <form method="post" id="formExpenseFilter2"
                                    action="{{ route('program.expense.filter', ['id' => $program->id]) }}">
                                    @csrf
                                    @method('put')
                                    <?php
                                    $is_expense_price = $filter['expense']['category'] == 'total_price';
                                    $expense_price_order = $filter['expense']['order'] == 'desc' ? 'desc' : 'asc';
                                    $expense_price_icon = $is_expense_price && $expense_price_order == 'asc' ? 'up' : 'down';
                                    $expense_price_value = $is_expense_price && $expense_price_order == 'asc' ? 'desc' : 'asc';
                                    ?>
                                    <input type="hidden" name="category" value="total_price">
                                </form>
                                <form method="post" id="formExpenseFilter3"
                                    action="{{ route('program.expense.filter', ['id' => $program->id]) }}">
                                    @csrf
                                    @method('put')
                                    <?php
                                    $is_expense_valid = $filter['expense']['category'] == 'financial_id';
                                    $expense_valid_order = $filter['expense']['order'] == 'desc' ? 'desc' : 'asc';
                                    $expense_valid_icon = $is_expense_valid && $expense_valid_order == 'asc' ? 'up' : 'down';
                                    $expense_valid_value = $is_expense_valid && $expense_valid_order == 'asc' ? 'desc' : 'asc';
                                    ?>
                                    <input type="hidden" name="category" value="financial_id">
                                </form>
                                <div class="container d-block px-0 ">
                                    <div class="row">
                                        <div class="col-12 d-flex">
                                            <div class="input-group input-group-sm bg-body-tertiary rounded">
                                                <button type="button" form="formExpenseFilter"
                                                    class="btn btn-sm rounded-0 rounded-start text-light bg-secondary">
                                                    <i class="bi bi-funnel-fill"></i>
                                                    <span
                                                        class="ms-1 ps-2 border-start border-light d-none d-md-inline">{{ 'Filter' }}</span>
                                                </button>
                                                <button type="submit" form="formExpenseFilter"
                                                    class="btn btn-sm btn-outline-secondary border-0 rounded-0 my-0"
                                                    name="order" value="{{ $expense_name_value }}">
                                                    <span class="">{{ 'Name' }}</span>
                                                    <i class="bi bi-sort-numeric-{{ $expense_name_icon }}-alt"></i>
                                                </button>
                                                <button type="submit" form="formExpenseFilter2"
                                                    class="btn btn-sm btn-outline-secondary border-0 rounded-0 my-0"
                                                    name="order" value="{{ $expense_price_value }}">
                                                    <span class="">{{ 'Price' }}</span>
                                                    <i class="bi bi-sort-numeric-{{ $expense_price_icon }}-alt"></i>
                                                </button>
                                                <button type="submit" form="formExpenseFilter3"
                                                    class="btn btn-sm btn-outline-secondary border-0 rounded-0 my-0"
                                                    name="order" value="{{ $expense_valid_value }}">
                                                    <span class="">{{ 'Valid' }}</span>
                                                    <i class="bi bi-sort-numeric-{{ $expense_valid_icon }}-alt"></i>
                                                </button>
                                            </div>
                                            @if ($auth_user->id == $program->pic_id)
                                                <!-- Button trigger Add Expense Modal -->
                                                <div
                                                    onclick="{{ $program->financial_id > 0 ? '' : 'notif("You can add Expense Item after Program Budget approved by Financial Officer.")' }}">
                                                    <button
                                                        class="ms-2 btn btn-sm btn-{{ $program->financial_id > 0 ? 'primary' : 'secondary disabled' }}"
                                                        data-bs-toggle="modal" data-bs-target="#addProgramExpense">
                                                        <i class="bi bi-plus-lg"></i>
                                                    </button>
                                                </div>
                                                <!-- Add Expense Modal -->
                                                @if ($program->financial_id > 0)
                                                    <div class="modal fade" id="addProgramExpense" tabindex="-1"
                                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content shadow mx-3">
                                                                <div class="modal-header py-1 ps-3 pe-2">
                                                                    <span
                                                                        class="modal-title fs-5 text-primary-emphasis"
                                                                        id="exampleModalLabel"><i
                                                                            class="bi bi-cart-plus border-secondary border-end me-2 pe-2"></i>
                                                                        New Expense Item
                                                                    </span>
                                                                    <button type="button" class="btn btn-sm ms-auto"
                                                                        data-bs-dismiss="modal" aria-label="Close"><i
                                                                            class="bi bi-x-lg"></i></button>
                                                                </div>
                                                                <form method="post" enctype="multipart/form-data"
                                                                    action="{{ route('program.expense.add', ['id' => $program->id]) }}">
                                                                    @csrf
                                                                    @method('put')
                                                                    <div class="modal-body bg-light">
                                                                        <div class="row justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_expense_name"
                                                                                    class="form-label d-inline-block">Name</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="text"
                                                                                    class="form-control form-control-sm d-inline-block"
                                                                                    name="name"
                                                                                    id="add_expense_name"
                                                                                    value="{{ old('name') }}"
                                                                                    required>
                                                                                <x-input-error :messages="$errors->get('name')"
                                                                                    class="mt-2" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_expense_price"
                                                                                    class="form-label d-inline-block">Price</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="number"
                                                                                    class="form-control form-control-sm d-inline-block"
                                                                                    name="price"
                                                                                    id="add_expense_price"
                                                                                    value="{{ old('price') }}"
                                                                                    oninput="return setTotalPrice('expense')"
                                                                                    placeholder="Type numbers only"
                                                                                    required>
                                                                                <x-input-error :messages="$errors->get('price')"
                                                                                    class="mt-2" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_expense_unit"
                                                                                    class="form-label d-inline-block">Unit</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="text"
                                                                                    class="form-control form-control-sm d-inline-block"
                                                                                    name="unit"
                                                                                    placeholder="gram, ml, pcs, etc.."
                                                                                    id="add_expense_unit"
                                                                                    value="{{ old('unit') }}"
                                                                                    required>
                                                                                <x-input-error :messages="$errors->get('unit')"
                                                                                    class="mt-2" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_expense_qty"
                                                                                    class="form-label d-inline-block">Quantity</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="number"
                                                                                    class="form-control form-control-sm d-inline-block"
                                                                                    name="qty"
                                                                                    id="add_expense_qty"
                                                                                    value="{{ old('qty') }}"
                                                                                    oninput="return setTotalPrice('expense')"
                                                                                    placeholder="Type numbers only"
                                                                                    required>
                                                                                <x-input-error :messages="$errors->get('qty')"
                                                                                    class="mt-2" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <span>{{ 'Total Price' }}</span>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <span id="add_expense_total"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_expense_receipt"
                                                                                    class="form-label d-inline-block">{{ 'Receipt' }}</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="file"
                                                                                    class="form-control form-control-sm"
                                                                                    name="reciept"
                                                                                    id="add_expense_receipt"
                                                                                    value="{{ old('reciept') }}">
                                                                                <x-input-error :messages="$errors->get('reciept')"
                                                                                    class="mt-2" />
                                                                                <div class="input-group input-group-sm"
                                                                                    id="add_expense_receipt_same_container"
                                                                                    hidden>
                                                                                    <label
                                                                                        for="add_expense_receipt_same"
                                                                                        class="input-group-text">Same
                                                                                        as
                                                                                        item</label>
                                                                                    <select name="receipt_same"
                                                                                        class="form-select py-0 d-inline"
                                                                                        id="add_expense_receipt_same">
                                                                                        <?php $i = 1; ?>
                                                                                        @foreach ($expense_list as $expense)
                                                                                            <?php $default = $i === 1 ? 'selected' : ''; ?>
                                                                                            <option
                                                                                                value="{{ $expense->id }}"
                                                                                                {{ $default }}>
                                                                                                {{ $expense->name }}
                                                                                            </option>
                                                                                            <?php $i++; ?>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                @if ($program->expense > 0)
                                                                                    <label for="same_receipt_check"
                                                                                        class="inline-flex items-center mt-1"
                                                                                        onclick="sameReceipt()">
                                                                                        <input id="same_receipt_check"
                                                                                            type="checkbox"
                                                                                            name="same_receipt_check"
                                                                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                                                                        <span
                                                                                            class="ms-2 text-sm text-gray-600">{{ __('same as exist item') }}</span>
                                                                                    </label>
                                                                                @endif
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
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </nav>
                        </div>
                    </div>

                    {{-- Expense list item --}}
                    <div class="row">
                        <div class="col-12">
                            <div
                                class="scroll-container-3 scroll-container-lg-2 bg-secondary bg-opacity-25 px-2 pt-2 rounded mt-2">
                                <?php
                                $i = 1;
                                ?>
                                @foreach ($expense_list as $expense)
                                    <div class="card card-bg-hover shadow mb-2 py-1">
                                        <div class="row">
                                            <div class="col-12 d-flex">
                                                <span
                                                    class="text-primary-emphasis ms-2 my-auto">{{ $expense->name }}</span>
                                                <span
                                                    class="fw-light ms-2 d-none d-lg-flex my-auto">{{ '- ' . format_currency($expense->price) . ' /' . $expense->unit }}</span>
                                                <span
                                                    class="fw-light ms-auto d-none d-lg-flex my-auto">{{ 'total (' . $expense->qty . ') : ' }}</span>
                                                <span
                                                    class="fw-normal mx-2 d-none d-lg-flex my-auto">{{ format_currency($expense->total_price) }}</span>
                                                {{-- Receipt Trigger Button --}}
                                                <button
                                                    class="ms-auto ms-lg-1 me-1 btn btn-sm btn-light position-relative my-auto"
                                                    data-bs-toggle="modal" data-bs-target="#receiptExpenseModal"
                                                    onclick="setExpenseReceipt({{ $expense }})">

                                                    @if ($expense->financial_id == null)
                                                        <span
                                                            class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                                                    @endif
                                                    <i class="bi bi-receipt"> </i>
                                                </button>
                                                @if ($auth_user->id == $program->pic_id)
                                                    {{-- Delete Button --}}
                                                    <div class="me-1 my-auto"
                                                        onclick="{{ $expense->financial_id <= 0 ? '' : 'notif("This expense has been validated by Financial Officer. You can not delete it, please contact Financial Officer.")' }}">
                                                        <button
                                                            class="btn btn-sm btn-danger {{ $expense->financial_id > 0 ? 'disabled' : '' }}"
                                                            onclick="confirmation('{{ route('program.expense.delete', ['id' => $expense->id]) }}', '{{ 'Are you sure want to delete ' . $expense->name . ' from ' . $program->name . ' Program Expense?' }}')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row d-lg-none">
                                            <div class="col-12 d-flex">
                                                <span
                                                    class="fw-light ms-2 ">{{ format_currency($expense->price) . ' /' . $expense->unit }}</span>
                                                <span
                                                    class="fw-light ms-auto">{{ 'total (' . $expense->qty . ') : ' }}</span>
                                                <span
                                                    class="fw-normal mx-2">{{ format_currency($expense->total_price) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $i++; ?>
                                @endforeach
                            </div>
                        </div>
                        <!-- Expense Receipt Modal -->
                        <div class="modal fade" id="receiptExpenseModal" tabindex="-1"
                            aria-labelledby="receiptExpenseModal" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content shadow mx-3 mt-5">
                                    <div class="modal-header py-1 ps-3 pe-2">
                                        <span class="modal-title fs-5 text-primary-emphasis">
                                            <i class="bi bi-receipt border-secondary border-end me-2 pe-2"></i>
                                            {{ 'Expense Receipt' }}
                                        </span>
                                        <button type="button" class="btn btn-sm ms-auto" data-bs-dismiss="modal"
                                            aria-label="Close"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                    <div class="modal-body bg-light p-1 px-3">
                                        <div class="row justify-content-center mt-2">
                                            <div class="col-12 d-flex">
                                                <img src="" alt="image" class="rounded mx-auto"
                                                    style="width: 100%; height 100%;  object-fit: contain; max-height:320px;"
                                                    id="expense_receipt_image">
                                            </div>
                                        </div>
                                        <div class="row mt-2 justify-content-center">
                                            <div class="col-12 d-flex">
                                                <span class="fw-light ms-auto">{{ 'Status : ' }}</span>
                                                <span class="fw-normal text-primary-emphasis me-auto ms-1"
                                                    id="expense_receipt_status"></span>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-12 d-flex">
                                                <a href="" target="blank" style="text-decoration-none"
                                                    class="mx-auto" id="expense_receipt_download" download>
                                                    <button class="btn btn-sm btn-light">
                                                        <span class="fw-light" id="expense_receipt_name"></span>
                                                        <i class="bi bi-download text-primary"></i>
                                                    </button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer p-1 px-2">
                                        @if ($auth_user->roles_id == 2)
                                            <form method="post" action="{{ route('program.expense.validate') }}"
                                                enctype="multipart/form-data" id="formExpenseReceiptValidation">
                                                @csrf
                                                @method('put')
                                                <input type="hidden" name="receipt_id" id="expense_receipt_id">
                                                <input type="hidden" name="validate" id="expense_receipt_validate">
                                            </form>
                                            <div class="btn-group btn-group-sm">
                                                <button type="submit" form="formExpenseReceiptValidation"
                                                    class="btn btn-sm btn-primary"
                                                    id="expense_receipt_button"></button>
                                            </div>
                                        @else
                                            <button data-bs-dismiss="modal" aria-label="Close"
                                                class="btn btn-sm btn-secondary ">{{ 'Close' }}</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Staff list --}}
                <div id="content_4" {{ $default_tab == 4 ? '' : 'hidden' }}>
                    {{-- Staff Filter --}}
                    <div class="row justify-content-center">
                        <div class="col-12 ">
                            <nav class="navbar rounded bg-white shadow-sm p-2">
                                <form method="post" id="formStaffFilter"
                                    action="{{ route('program.staff.filter', ['id' => $program->id]) }}">
                                    @csrf
                                    @method('put')
                                    <?php
                                    $is_staff_name = $filter['staff']['category'] == 'name';
                                    $staff_name_order = $filter['staff']['order'] == 'desc' ? 'desc' : 'asc';
                                    $staff_name_icon = $is_staff_name && $staff_name_order == 'asc' ? 'up' : 'down';
                                    $staff_name_value = $is_staff_name && $staff_name_order == 'asc' ? 'desc' : 'asc';
                                    ?>
                                    <input type="hidden" name="category" value="name">
                                </form>
                                <form method="post" id="formStaffFilter2"
                                    action="{{ route('program.staff.filter', ['id' => $program->id]) }}">
                                    @csrf
                                    @method('put')
                                    <?php
                                    $is_staff_date = $filter['staff']['category'] == 'created_at';
                                    $staff_date_order = $filter['staff']['order'] == 'desc' ? 'desc' : 'asc';
                                    $staff_date_icon = $is_staff_date && $staff_date_order == 'asc' ? 'up' : 'down';
                                    $staff_date_value = $is_staff_date && $staff_date_order == 'asc' ? 'desc' : 'asc';
                                    ?>
                                    <input type="hidden" name="category" value="created_at">
                                </form>
                                <div class="container d-block px-0 ">
                                    <div class="row">
                                        <div class="col-12 d-flex">
                                            <div class="input-group input-group-sm bg-body-tertiary rounded">
                                                <button type="button"
                                                    class="btn btn-sm rounded-0 rounded-start text-light bg-secondary">
                                                    <i class="bi bi-funnel-fill"></i>
                                                    <span
                                                        class="ms-1 ps-2 border-start border-light d-none d-md-inline">{{ 'Filter' }}</span>
                                                </button>
                                                <button type="submit" form="formStaffFilter"
                                                    class="btn btn-sm btn-outline-secondary border-0 rounded-0 my-0"
                                                    name="order" value="{{ $staff_name_value }}">
                                                    <span class="">{{ 'Name' }}</span>
                                                    <i class="bi bi-sort-numeric-{{ $staff_name_icon }}-alt"></i>
                                                </button>
                                                <button type="submit" form="formStaffFilter2"
                                                    class="btn btn-sm btn-outline-secondary border-0 rounded-0 my-0"
                                                    name="order" value="{{ $staff_date_value }}">
                                                    <span class="">{{ 'Date' }}</span>
                                                    <i class="bi bi-sort-numeric-{{ $staff_date_icon }}-alt"></i>
                                                </button>
                                            </div>
                                            @if ($auth_user->id == $program->pic_id)
                                                <!-- Button trigger Add Expense Modal -->
                                                <div
                                                    onclick="{{ $program->financial_id > 0 ? '' : 'notif("You can add Expense Item after Program Budget approved by Financial Officer.")' }}">
                                                    <button
                                                        class="ms-2 btn btn-sm btn-{{ $program->financial_id > 0 ? 'primary' : 'secondary disabled' }}"
                                                        data-bs-toggle="modal" data-bs-target="#addProgramStaff">
                                                        <i class="bi bi-plus-lg"></i>
                                                    </button>
                                                </div>
                                                <!-- Add Expense Modal -->
                                                @if ($program->financial_id > 0)
                                                    <div class="modal fade" id="addProgramStaff" tabindex="-1"
                                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content shadow mx-3">
                                                                <div class="modal-header py-1 ps-3 pe-2">
                                                                    <span
                                                                        class="modal-title fs-5 text-primary-emphasis"
                                                                        id="exampleModalLabel"><i
                                                                            class="bi bi-person-fill-add border-secondary border-end me-2 pe-2"></i>
                                                                        {{ 'Add New Staff' }}
                                                                    </span>
                                                                    <button type="button" class="btn btn-sm ms-auto"
                                                                        data-bs-dismiss="modal" aria-label="Close"><i
                                                                            class="bi bi-x-lg"></i></button>
                                                                </div>
                                                                <form method="post"
                                                                    action="{{ route('program.staff.add', ['id' => $program->id]) }}">
                                                                    @csrf
                                                                    @method('put')
                                                                    <div class="modal-body bg-light">
                                                                        <div class="row mt-0 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_staff_title"
                                                                                    class="form-label d-inline-block">{{ 'Title' }}</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <input type="text"
                                                                                    class="form-control form-control-sm d-inline-block"
                                                                                    name="staff_title"
                                                                                    id="add_staff_title"
                                                                                    value="{{ old('staff_title') }}"
                                                                                    placeholder="PDD, ATP, etc.."
                                                                                    required>
                                                                                <x-input-error :messages="$errors->get('staff_title')"
                                                                                    class="mt-2" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2 justify-content-center">
                                                                            <div class="col-4 col-lg-3">
                                                                                <label for="add_staff_id"
                                                                                    class="form-label d-inline-block">{{ 'Staff' }}</label>
                                                                            </div>
                                                                            <div class="col-8 col-lg-7">
                                                                                <select name="staff_id"
                                                                                    class="form-select py-0 d-inline"
                                                                                    id="add_staff_id">
                                                                                    <?php $i = 1; ?>
                                                                                    @foreach ($employee_list as $employee)
                                                                                        <?php $default = $i === 1 ? 'selected' : ''; ?>
                                                                                        <option
                                                                                            value="{{ $employee->id }}"
                                                                                            {{ $default }}>
                                                                                            {{ $employee->name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
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
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </nav>
                        </div>
                    </div>

                    {{-- Staff list item --}}
                    <div class="row">
                        <div class="col-12">
                            <div
                                class="scroll-container-3 scroll-container-lg-2 bg-secondary bg-opacity-25 px-2 pt-2 rounded mt-2">
                                <?php
                                $i = 1;
                                ?>
                                @foreach ($staff_list as $staff)
                                    <a href="{{ route('profile.edit', ['id' => $staff->employee->id]) }}"
                                        target="_blank" rel="noopener noreferrer" class="text-decoration-none">
                                        <div class="card card-bg-hover shadow mb-2">
                                            <div class="row gx-0 px-1" style="height:100%;">
                                                <div class="col-auto d-flex">
                                                    <div class="card position-relative shadow-sm rounded-circle border-primary border-2 my-auto"
                                                        style="padding-bottom:25px; width: 25px;">
                                                        <img src="/storage/images/profile/{{ $staff->employee->profile_image !== null ? $staff->employee->profile_image : 'example.png' }}"
                                                            alt="image"
                                                            class="rounded-circle position-absolute top-0 start-0 w-100 h-100"
                                                            style="object-fit: cover;">
                                                    </div>
                                                </div>
                                                <div class="col d-flex my-1">
                                                    <div class="d-flex scroll-x-hidden text-nowrap ms-2 me-auto">
                                                        <span
                                                            class="text-primary-emphasis my-auto">{{ $staff->employee->name }}</span>
                                                        <span
                                                            class="fw-light ms-1 my-auto {{ $staff->employee->id == $program->pic_id ? 'text-primary' : '' }}">{{ '- ' . $staff->title }}</span>
                                                    </div>
                                                    @if ($auth_user->id == $program->pic_id && $program->staff_lock <= 0 && $staff->user_id !== $program->pic_id)
                                                        {{-- Delete Button --}}
                                                        <button class="ms-auto btn btn-sm btn-danger"
                                                            onclick="confirmation('{{ route('program.staff.delete', ['id' => $staff->id]) }}', '{{ 'Are you sure want to remove ' . $staff->employee->name . ' from ' . $program->name . ' Staff Program ?' }}')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php $i++; ?>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- script --}}
    <script>
        const auth_user = <?php echo $auth_user; ?>;
        const delete_letter_route = '{{ route('program.disbursement.letter.delete') }}';

        function show_tab(target) {
            const tabs = 4;
            // deactive all tabs
            for (let number = 1; number <= tabs; number++) {
                let tab = document.getElementById('tab_' + number);
                let icon = document.getElementById('tab_icon_' + number);
                let span = document.getElementById('tab_span_' + number);
                let content = document.getElementById('content_' + number);
                // set tab to deactive
                tab.setAttribute('class', 'nav-link bg-white border');
                if (icon.classList.contains('me-2')) {
                    icon.classList.remove('me-2');
                }
                if (!span.classList.contains('d-none')) {
                    span.classList.add('d-none');
                }
                // set content to hide
                content.setAttribute('hidden', '');
            }
            let tab = document.getElementById('tab_' + target);
            let icon = document.getElementById('tab_icon_' + target);
            let span = document.getElementById('tab_span_' + target);
            let content = document.getElementById('content_' + target);
            // set tab to active
            icon.classList.add('me-2');
            span.classList.remove('d-none');
            tab.setAttribute('class', 'nav-link active bg-secondary text-white');
            // set content to show
            content.removeAttribute('hidden');
        }

        function collapse_toggle(trigger_id) {
            if (trigger_id == 'collapseDetailTrigger') {
                var container1 = document.getElementById('collapseTab1');
                var container2 = document.getElementById('collapseTab2');
                var container3 = document.getElementById('collapseTab3');
                var container4 = document.getElementById('collapseTab4');
                var trigger = document.getElementById('collapseTabTrigger');
                if (container1.classList.contains('show') || container2.classList.contains('show') || container3.classList
                    .contains('show') || container4.classList.contains('show')) {
                    container1.classList.remove('show');
                    container2.classList.remove('show');
                    container3.classList.remove('show');
                    container4.classList.remove('show');
                    trigger.classList.remove('bi-chevron-up');
                    trigger.classList.add('bi-chevron-down');
                }
            } else {
                var container = document.getElementById('collapseDetail');
                var container2 = document.getElementById('collapseDetail2');
                var trigger = document.getElementById('collapseDetailTrigger');
                if (container.classList.contains('show')) {
                    container.classList.remove('show');
                    container2.classList.remove('show');
                    trigger.classList.remove('bi-chevron-up');
                    trigger.classList.add('bi-chevron-down');
                }
            }

            var trigger = document.getElementById(trigger_id);
            if (trigger.classList.contains('bi-chevron-up')) {
                trigger.classList.remove('bi-chevron-up');
                trigger.classList.add('bi-chevron-down');
                if (trigger_id == 'collapseDetailTrigger') {
                    var container2 = document.getElementById('collapseDetail2');
                    container2.classList.remove('show');
                } else {
                    var container2 = document.getElementById('collapseTab2');
                    var container3 = document.getElementById('collapseTab3');
                    var container4 = document.getElementById('collapseTab4');
                    container2.classList.remove('show');
                    container3.classList.remove('show');
                    container4.classList.remove('show');
                }
            } else {
                trigger.classList.remove('bi-chevron-down');
                trigger.classList.add('bi-chevron-up');
                if (trigger_id == 'collapseDetailTrigger') {
                    var container2 = document.getElementById('collapseDetail2');
                    container2.classList.add('show');
                } else {
                    var container2 = document.getElementById('collapseTab2');
                    var container3 = document.getElementById('collapseTab3');
                    var container4 = document.getElementById('collapseTab4');
                    container2.classList.add('show');
                    container3.classList.add('show');
                    container4.classList.add('show');
                }
            }
        }

        function setTotalPrice(type) {
            const price = document.getElementById('add_' + type + '_price');
            const qty = document.getElementById('add_' + type + '_qty');
            const total = document.getElementById('add_' + type + '_total');

            total.innerHTML = formatRupiah(price.value * qty.value);
        }

        function setDisbursementReceipt(receipt) {
            const image = document.getElementById('disbursement_receipt_image');
            const download = document.getElementById('disbursement_receipt_download');
            const name = document.getElementById('disbursement_receipt_name');

            image.setAttribute('src', '/storage/images/receipt/disbursement/' + receipt);
            download.setAttribute('href', '/storage/images/receipt/disbursement/' + receipt);
            name.innerHTML = receipt;
        }

        function setLetter(disbursement_letter, is_valid = false) {
            const letter = document.getElementById('disbursement_letter');
            const download = document.getElementById('disbursement_letter_download');
            const name = document.getElementById('disbursement_letter_name');
            const delete_button = document.getElementById('disbursement_letter_delete');

            letter.setAttribute('src', '/storage/document/letter/disbursement/' + disbursement_letter.letter);
            download.setAttribute('href', '/storage/document/letter/disbursement/' + disbursement_letter.letter);
            name.innerHTML = disbursement_letter.letter;
            if (is_valid) {
                delete_button.setAttribute('href', delete_letter_route + '/' + disbursement_letter.id);
                delete_button.classList.remove('d-none');
            } else {
                delete_button.setAttribute('href', '');
                delete_button.classList.add('d-none');
            }
        }

        function setExpenseReceipt(expense) {
            const image = document.getElementById('expense_receipt_image');
            const download = document.getElementById('expense_receipt_download');
            const name = document.getElementById('expense_receipt_name');
            const status = document.getElementById('expense_receipt_status');

            status.innerHTML = expense.financial_id == null ? 'Unvalidated' : 'Validated by ' + expense.financial.name;
            image.setAttribute('src', '/storage/images/receipt/expense/' + expense.reciept);
            download.setAttribute('href', '/storage/images/receipt/expense/' + expense.reciept);
            name.innerHTML = expense.reciept;

            if (auth_user.roles_id == 2) {
                const id = document.getElementById('expense_receipt_id');
                const validate = document.getElementById('expense_receipt_validate');
                const button = document.getElementById('expense_receipt_button');

                id.value = expense.id;
                validate.value = expense.financial_id == null ? 'validate' : null;
                button.innerHTML = expense.financial_id == null ? 'Validate' : 'Unvalidate';
            }
        }

        function disbursementBlaterianBalance() {
            let blaterianBalanceContainer = document.getElementById('add_blaterian_disbursement_container');
            let blaterianBalanceCheckBox = document.getElementById('blaterian_balance');
            if (blaterianBalanceCheckBox.checked == true) {
                blaterianBalanceContainer.removeAttribute('hidden');
            } else {
                blaterianBalanceContainer.setAttribute('hidden', '');
            }
        }

        function sameReceipt() {
            let receiptUpload = document.getElementById('add_expense_receipt');
            let receiptSame = document.getElementById('add_expense_receipt_same_container');
            let sameReceiptCheck = document.getElementById('same_receipt_check');
            if (sameReceiptCheck.checked == true) {
                receiptUpload.setAttribute('hidden', '');
                receiptSame.removeAttribute('hidden');
            } else {
                receiptUpload.removeAttribute('hidden');
                receiptSame.setAttribute('hidden', '');
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
    </script>
</x-app-layout>
