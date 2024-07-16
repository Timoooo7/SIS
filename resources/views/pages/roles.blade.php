<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Roles Management') }}
        </h2>
    </x-slot>

    <div class="container py-3 px-10">
        <div class="row mx-auto sm:px-6 px-0 space-y-6">
            {{-- Dashboard --}}
            <div class="col-lg-6 mt-4 px-2">
                <div class="bg-primary p-3 sm:p-6 shadow rounded-lg border border-info" style="--bs-bg-opacity: .02;">
                    <div class="h5 text-primary border-primary border-bottom pb-2 mx-2">
                        {{ __('SEEO Roles Dashboard') }}
                        <span class="fw-light fs-6 fst-italic text-secondary border-start ps-2"> Only
                            CEO & Co-CEO can changes user roles.</span>
                    </div>
                    <div class="row mx-auto sm:px-6 px-0 space-y-3">
                        <div class="col-lg-5 pt-2 bg-white rounded-lg">
                            <h6 class="fw-bold">Date Time <span
                                    class="text-center fw-light fst-italic float-end">{{ now()->format('d/M/y H:i:s') }}</span>
                            </h6>
                            <p class="text-secondary mb-0"> All data on the display are based on this date and time.
                                Reload
                                the
                                page to update the newest data.</p>
                        </div>
                        <div class="col-lg-7">
                            <?php
                            function person($array)
                            {
                                return count($array) > 1 ? ' persons' : ' person';
                            }
                            ?>
                            <div class="fs-6 my-1">Chief Executive Officer & Co :
                                <span class="float-end">{{ count($chiefs) . person($chiefs) }}</span>
                            </div>
                            <div class="fs-6 my-1">Financial Officer :
                                <span class="float-end">{{ count($financials) . person($financials) }}</span>
                            </div>
                            <div class="fs-6 my-1">Operational Officer :
                                <span class="float-end">{{ count($operationals) . person($operationals) }}</span>
                            </div>
                            <div class="fs-6 my-1">Staff :
                                <span class="float-end">{{ count($staffs) . person($staffs) }}</span>
                            </div>
                            <div class="fs-6 my-1">Total :
                                <span class="float-end">{{ $total . ' persons' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- ROLES CHECK --}}
            <?php $disabled = Auth::user()->roles_id != 1 ? 'disabled' : ''; ?>
            {{-- CEO Edit Table --}}
            <div class="col-lg-6 mt-4 px-2">
                <div class="bg-white p-3 sm:p-6 shadow sm:rounded-lg">
                    <div class="h5 fw-bold">Chief Executive Officer </div>
                    <table class="table">
                        <thead class="table-primary">
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Registered at</th>
                            <th scope="col">Remove</th>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php $i = 1; ?>
                            @foreach ($chiefs as $chief)
                                <?php $route1 = $disabled == '' ? route('role.removeRole') : ''; ?>
                                <form method="post" action="{{ $route1 }}" class="p-6"
                                    onsubmit="return confirm('Confirm to remove user`s role.');">
                                    @csrf
                                    @method('put')
                                    <tr>
                                        <th scope="row">
                                            <input type="text" disabled style="border: 0; padding:0; width: 10px"
                                                value="{{ $i }}">
                                        </th>
                                        <input type="hidden" hidden name="id"
                                            style="border: 0; padding:0; width: 0px" value="{{ $chief['id'] }}">
                                        <td style="max-width: 120px;"><input type="text" disabled
                                                style="border: 0; padding:0; width: auto" value="{{ $chief['name'] }}">
                                        </td>
                                        <td>{{ $chief['created_at']->format('d/M/y - h:i:s') }}</td>
                                        <td><button {{ $disabled }} type="submit"
                                                class="btn border-0 py-0 text-danger">Remove</button>
                                        </td>
                                    </tr>
                                </form>
                                <?php $i++; ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Financial Table --}}
            <div class="col-lg-6 mt-4 px-2">
                <div class="p-3 sm:p-6 bg-white shadow sm:rounded-lg">
                    <div class="h5 fw-bold">Financial Officer</div>
                    <table class="table">
                        <thead class="table-primary">
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Registered at</th>
                            <th scope="col">Remove</th>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php $i = 1; ?>
                            @foreach ($financials as $financial)
                                <form method="post" action="{{ route('role.removeRole') }}" class="p-6"
                                    onsubmit="return confirm('Confirm to remove user`s role?');">
                                    @csrf
                                    @method('put')
                                    <tr>
                                        <th scope="row">
                                            <input type="text" disabled style="border: 0; padding:0; width: 10px"
                                                value="{{ $i }}">
                                        </th>
                                        <input type="hidden" hidden name="id"
                                            style="border: 0; padding:0; width: 0px" value="{{ $financial['id'] }}">
                                        <td style="max-width: 120px;"><input type="text" disabled
                                                style="border: 0; padding:0; width: auto"
                                                value="{{ $financial['name'] }}"></td>
                                        <td>{{ $financial['created_at']->format('d/M/y - h:i:s') }}</td>
                                        <td><button {{ $disabled }} type="submit"
                                                class="btn border-0 py-0 text-danger">Remove</button></td>
                                    </tr>
                                </form>
                                <?php $i++; ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Operational Table --}}
            <div class="col-lg-6 mt-4 px-2">
                <div class="p-3 sm:p-6 bg-white shadow sm:rounded-lg">
                    <div class="h5 fw-bold">Operational Officer</div>
                    <table class="table">
                        <thead class="table-primary">
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Registered at</th>
                            <th scope="col">Remove</th>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php $i = 1; ?>
                            @foreach ($operationals as $operational)
                                <form method="post" action="{{ route('role.removeRole') }}" class="p-6"
                                    onsubmit="return confirm('Confirm to remove user`s role.');">
                                    @csrf
                                    @method('put')
                                    <tr>
                                        <th scope="row">
                                            <input type="text" disabled style="border: 0; padding:0; width: 10px"
                                                value="{{ $i }}">
                                        </th>
                                        <input type="hidden" hidden name="id"
                                            style="border: 0; padding:0; width: 0px"
                                            value="{{ $operational['id'] }}">
                                        <td style="max-width: 120px;"><input type="text" disabled
                                                style="border: 0; padding:0; width: auto"
                                                value="{{ $operational['name'] }}"></td>
                                        <td>{{ $operational['created_at']->format('d/M/y - h:i:s') }}</td>
                                        <td><button {{ $disabled }} type="submit"
                                                class="btn border-0 py-0 text-danger">Remove</button></td>
                                    </tr>
                                </form>
                                <?php $i++; ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Staff Table --}}
            <div class="col-lg-12 mt-4 px-2">
                <div class="p-3 sm:p-6 bg-white shadow sm:rounded-lg">
                    <?php $i = 1; ?>
                    <form method="post" id="formUpdate" action="{{ route('role.update') }}"
                        onsubmit="return confirm('Are you sure want to promote selected user(s)?');">
                        @csrf
                        @method('put')
                        {{-- Staff Edit Table --}}
                        <div class="h5 fw-bold d-inline-block mt-2">Staff </div>
                        <table class="table mt-1">
                            <thead class="table-primary">
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Registered at</th>
                                <th scope="col">
                                    <button {{ $disabled }} form="formUpdate" type="submit"
                                        class="btn bg-light-subtle fw-bold float-start py-0 border-primary border-end-0 border-start-0"
                                        style="float: right;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-box-arrow-up d-inline text-primary mb-1"
                                            viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M3.5 6a.5.5 0 0 0-.5.5v8a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-8a.5.5 0 0 0-.5-.5h-2a.5.5 0 0 1 0-1h2A1.5 1.5 0 0 1 14 6.5v8a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-8A1.5 1.5 0 0 1 3.5 5h2a.5.5 0 0 1 0 1z" />
                                            <path fill-rule="evenodd"
                                                d="M7.646.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 1.707V10.5a.5.5 0 0 1-1 0V1.707L5.354 3.854a.5.5 0 1 1-.708-.708z" />
                                        </svg>
                                        Promote User</button>
                                </th>
                                <th scope="col">
                                    <button {{ $disabled }} data-bs-toggle="modal" data-bs-target="#delAccount"
                                        type="button"
                                        class="btn bg-light-subtle fw-bold float-start py-0 border-danger border-end-0 border-start-0 "
                                        style="float: right;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-person-x d-inline text-danger mb-1"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m.256 7a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z" />
                                            <path
                                                d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m-.646-4.854.646.647.646-.647a.5.5 0 0 1 .708.708l-.647.646.647.646a.5.5 0 0 1-.708.708l-.646-.647-.646.647a.5.5 0 0 1-.708-.708l.647-.646-.647-.646a.5.5 0 0 1 .708-.708" />
                                        </svg>
                                        Delete Account</button>

                                </th>
                            </thead>
                            <tbody class="table-group-divider">
                                <?php $i = $staffs->perPage() * ($staffs->currentPage() - 1) + 1; ?>
                                @foreach ($staffs as $staff)
                                    <tr>
                                        <input type="hidden" hidden name="id{{ $staff['id'] }}"
                                            style="border: 0; padding:0; width: 0px" value="{{ $staff['id'] }}">
                                        <th scope="row">{{ $i }}</th>
                                        <td>{{ $staff['name'] }}</td>
                                        <td>{{ $staff['created_at']->format('d/M/y - h:i:s') }}</td>
                                        <td>
                                            <select class="form-select form-select-sm" {{ $disabled }}
                                                aria-label="Small select example" name='role{{ $staff['id'] }}'>
                                                <option selected value="4" disabled>Select Position Here</option>
                                                <option value="1">Chief Executive Officer</option>
                                                <option value="2">Financial</option>
                                                <option value="3">Operational</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input type="hidden" form="formDelete" name="id{{ $staff['id'] }}"
                                                    value="{{ $staff['id'] }}">
                                                <input form="formDelete" class="form-check-input" type="checkbox"
                                                    role="switch" name="delete{{ $staff['id'] }}"
                                                    id="delete{{ $staff['id'] }}" {{ $disabled }}>
                                                <label class="form-check-label ms-2"
                                                    for="delete{{ $staff['id'] }}">Delete</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach
                                {{ $staffs->links() }}
                            </tbody>
                        </table>
                    </form>
                </div>
                <!-- Delete Account Modal -->
                <div class="modal fade" id="delAccount" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-top border-warning">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">
                                    Delete Account</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form method="post" id="formDelete" action="{{ route('user.delete') }}">
                                @csrf
                                @method('put')
                                <div class="modal-body">
                                    <p class="mx-5 fw-light">Selected user account will be deleted.
                                        Please
                                        make sure every duty of their role is fulfilled.
                                    </p>
                                    <div class="row g-3 align-items-center">
                                        <div class="col-auto mx-auto border-bottom">
                                            <label for="password" class="form-label d-inline-block fw-light">Password
                                                Needed to Authorize</label>
                                        </div>
                                    </div>
                                    <div class="row g-3 align-items-center mt-1">
                                        <div class="col-auto ms-auto">
                                            <label for="password"
                                                class="form-label d-inline-block fw-light">Password</label>
                                        </div>
                                        <div class="col-auto ms-2 me-auto">
                                            <input id="password" class="form-control" form="formDelete"
                                                type="password" name="password" required
                                                autocomplete="current-password" />
                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button form="formDelete" type="submit"
                                        class="btn btn-outline-danger shadow">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
