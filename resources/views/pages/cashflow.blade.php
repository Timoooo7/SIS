<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cash Flow') }}
        </h2>
    </x-slot>

    <div class="container py-3 px-10">
        <div class="row mx-auto sm:px-6 px-2">
            {{-- Dashboard --}}
            <div class="col-lg-4 mt-0 mb-4 px-2">
                <div class="bg-primary p-3 sm:p-6 shadow rounded-lg border border-info" style="--bs-bg-opacity: .02;">
                    <div class="h5 text-primary border-primary border-bottom pb-2 mx-2">
                        {{ __('SEEO Cash Flow Dashboard') }}
                        {{-- Financial Feature --}}
                        @if (Auth::user()->roles->id == 2)
                        @endif
                    </div>
                    <div class="row mx-auto sm:px-6 px-0 space-y-3 bg-white rounded-lg">
                        <h6 class="fw-bold">Date Time <span
                                class="text-center fw-light fst-italic float-end">{{ now()->format('d/M/y H:i:s') }}</span>
                        </h6>
                        <p class="text-secondary mb-0"> All data on the display are based on this date and time.
                            Reload
                            the
                            page to update the newest data.</p>
                    </div>
                    <div class="row mx-auto sm:px-6 px-0 space-y-3">
                        <div class="col-lg-12">
                            <div class="fs-6 my-1">Financial :
                                <ul class="mb-0 float-end" style="list-style-type: circle">
                                    @foreach ($financials as $financial)
                                        <li class="d-inline">{{ ' - ' . $financial->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="fs-6 my-1">Total Cash In: <span class="float-end">
                                    {{ Number::currency($totalCashIn, in: 'IDR') }}</span>
                            </div>
                            <div class="fs-6 my-1">Total Cash Out: <span
                                    class="float-end">{{ Number::currency($totalCashOut, in: 'IDR') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Cash In Items --}}
            <div class="col-lg-8 mb-4">
                <div class="bg-white p-3 sm:p-6 shadow rounded-lg">
                    <div class="h5 fw-bold d-inline-block">{{ __('Cash In Item') }}</div>
                    @if (Auth::user()->roles->id == 2)
                        <!-- Add Cash In Button Trigger Modal -->
                        <button class="btn text-dark float-end pt-0 pe-2" data-bs-toggle="modal"
                            data-bs-target="#addCashInItemModal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-plus d-inline text-primary" viewBox="0 0 16 16">
                                <path
                                    d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                            </svg>
                            Add Cash In Item
                        </button>

                        <!-- Add Cash In Item Modal -->
                        <div class="modal fade" id="addCashInItemModal" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-top border-warning">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Add
                                            Cash In Item</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form method="post" action="{{ route('cash_in_item.add') }}"
                                        enctype="multipart/form-data"
                                        onsubmit="return confirm('Are you sure ALL DATA is filled correctly to add Cash In Item?')">
                                        @csrf
                                        @method('put')
                                        <div class="modal-body">
                                            <div class="text-center mb-2"><span class="ms-3 border-bottom pb-1 d-block"
                                                    style="font-size: 12px">
                                                    <span class="text-danger ">*</span>
                                                    Don't use
                                                    comma
                                                    (,) or dot (.). Write the numbers only.</span></div>
                                            <div class="row g-3 align-items-center">
                                                <div class="col-3">
                                                    <label for="cash_in_name"
                                                        class="form-label d-inline-block float-end">Cash In
                                                        Label</label>
                                                </div>
                                                <div class="col-8 ms-2">
                                                    <input type="text" class="form-control d-inline-block"
                                                        name="cash_in_name" id="cash_in_name" required
                                                        value="{{ old('cash_in_name') }}">
                                                    <x-input-error :messages="$errors->get('cash_in_name')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="row g-3 align-items-center mt-1">
                                                <div class="col-3">
                                                    <label for="cash_in_price"
                                                        class="form-label d-inline-block float-end">Nominal<span
                                                            class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-8 ms-2">
                                                    <input type="number" class="form-control d-inline-block"
                                                        name="cash_in_price" id="cash_in_price" required
                                                        value="{{ old('cash_in_price') }}">
                                                    <x-input-error :messages="$errors->get('cash_in_price')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="row g-3 align-items-center mt-1">
                                                <div class="col-3">
                                                    <label for="cash_in_reciept"
                                                        class="form-label d-inline-block float-end">Reciept<span
                                                            class="text-danger ">**</span></label>
                                                </div>
                                                <div class="col-8 ms-2">
                                                    <input type="file"
                                                        class="form-control form-control-sm d-inline-block"
                                                        name="cash_in_reciept" id="cash_in_reciept" required
                                                        value="{{ old('cash_in_reciept') }}">
                                                    <x-input-error :messages="$errors->get('cash_in_reciept')" class="mt-2" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn shadow border-primary">Add Cash
                                                In</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- Cash In Item Table --}}
                    <table class="table">
                        <thead class="table-primary">
                            <th scope="col">#</th>
                            <th scope="col">Label</th>
                            <th scope="col">Nominal</th>
                            <th scope="col">Financial</th>
                            <th scope="col">Receipt</th>
                            <th scope="col">Last Update</th>
                        </thead>
                        <tbody class="table-group-divider">
                            @if (Auth::user()->roles->id == 2)
                                <?php $disabled = count($cash_in_items) > 0 ? '' : 'disabled'; ?>
                                {{-- Action form --}}
                                <form method="post" id="formUpdateCashInItem"
                                    action="{{ route('cash_in_item.update') }}">
                                    @csrf
                                    @method('put')
                                    <tr>
                                        <td colspan="6"><span class="fst-italic" style="font-size: 12px">This
                                                function only accessible
                                                by Financial only.</span>
                                            <span class="float-end"> Action:
                                                {{-- action button --}}
                                                @if (count($cash_in_items) > 0)
                                                    <button type="submit" form="formUpdateCashInItem"
                                                        class="px-1 d-inline" value="update" name="action">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor"
                                                            class="bi bi-pen-fill text-primary" viewBox="0 0 16 16">
                                                            <path
                                                                d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001" />
                                                        </svg>
                                                    </button>

                                                    <button type="submit" form="formUpdateCashInItem"
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
                                            <select {{ $disabled }} name="id_cash_in" style="max-width: 100%;"
                                                class="form-select py-0 mx-auto" aria-label="Default select example"
                                                required>
                                                <option selected value="null">select item</option>
                                                @foreach ($cash_in_items as $cash_in_item)
                                                    <option value="{{ $cash_in_item->id }}">
                                                        {{ $cash_in_item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get('id_cash_in')" class="mt-2" />
                                        </td>
                                        <td>
                                            <input {{ $disabled }} type="number"
                                                class="form-control py-0 d-inline-block" name="price_cash_in"
                                                id="price" placeholder="price">
                                            <x-input-error :messages="$errors->get('price_cash_in')" class="mt-2" />
                                        </td>
                                        <td colspan="3">
                                            <input {{ $disabled }} type="file"
                                                class="form-control py-0 d-inline-block" name="reciept_cash_in"
                                                style="max-width: 100%;" placeholder="reciept.png">
                                            <x-input-error :messages="$errors->get('reciept_cash_in')" class="mt-2" />
                                        </td>
                                    </tr>
                                </form>
                            @endif
                            <?php $i = $cash_in_items->perPage() * ($cash_in_items->currentPage() - 1) + 1; ?>
                            @foreach ($cash_in_items as $cash_in_item)
                                <tr>
                                    <th scope="row">{{ $i }}</th>
                                    <td>{{ $cash_in_item->name }}</td>
                                    <td>{{ Number::currency($cash_in_item->price, in: 'IDR') }}</td>
                                    <td>{{ $cash_in_item->financial->name }}</td>
                                    <td class="text-center">
                                        <button data-bs-toggle="modal" data-bs-target="#recieptModal"
                                            onclick="setInReceipt('{{ $cash_in_item->reciept }}', '{{ $cash_in_item->financial->name }}')">
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
                                    <td>{{ $cash_in_item->updated_at->format('d M y - H:i') }}</td>
                                </tr>
                                <?php $i++; ?>
                            @endforeach
                            {{ $cash_in_items->links() }}
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
                                            <span class="ms-auto mb-0 d-inline-block" id="receiptTitle"></span>
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
                                </div>
                            </div>
                        </div>
                    </table>
                </div>
            </div>
        </div>
        <div class="row mx-auto sm:px-6 px-2">
            <div class="col-lg-12 mb-4">
                <div class="bg-white p-3 sm:p-6 shadow rounded-lg">
                    <div class="h5 fw-bold d-inline-block">{{ __('Cash Out Item (Program Disbursement)') }}</div>
                    <div class="row">
                        <div class="col-lg-3">
                            @foreach ($cashOut as $item)
                                <?php $percent = $totalCashOut != 0 ? round(($item->disbursement * 100) / $totalCashOut) : 0; ?>
                                <p class="my-0 ">
                                    {{ $item->name . ' : ' }} <span
                                        class="text-secondary">{{ Number::currency($item->disbursement, in: 'IDR') }}</span>
                                </p>
                                <p class="my-0 text-secondary float-start me-2">
                                    <span class="text-danger">{{ $percent }}</span>%
                                </p>
                                <div class="progress mb-3 mt-1" role="progressbar"
                                    aria-label="Cash Out Item Percentage" aria-valuenow="{{ $percent }}"
                                    aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar progress-bar-striped bg-warning"
                                        style="width: {{ $percent }}%">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        {{-- Cash Out Item Table --}}
                        <div class="col-lg-9">
                            <table class="table">
                                <thead class="table-primary">
                                    <th scope="col">#</th>
                                    <th scope="col">Department</th>
                                    <th scope="col">Program</th>
                                    <th scope="col">Total Disbursement</th>
                                    <th scope="col">Last Update</th>
                                    <th scope="col">Detail</th>
                                </thead>
                                <tbody class="table-group-divider">
                                    <tr>
                                        <td colspan="6" class="fst-italic">These data are based on <b
                                                class="text-danger">disbursement item</b>
                                            in every program.</td>
                                    </tr>
                                    <?php $i = $cash_out_items->perPage() * ($cash_out_items->currentPage() - 1) + 1; ?>
                                    @foreach ($cash_out_items as $cash_out_item)
                                        <tr>
                                            <th scope="row">{{ $i }}</th>
                                            <td>{{ $cash_out_item->department->name }}</td>
                                            <td>{{ $cash_out_item->name }}</td>
                                            <td>{{ Number::currency($cash_out_item->disbursement, in: 'IDR') }}</td>
                                            <td>{{ $cash_out_item->updated_at->format('d M y - H:i') }}</td>
                                            <td>
                                                <a href="{{ route('program', ['id' => $cash_out_item->id]) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor"
                                                        class="bi bi-info-circle text-primary" viewBox="0 0 16 16">
                                                        <path
                                                            d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                                        <path
                                                            d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0" />
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php $i++; ?>
                                    @endforeach
                                    {{ $cash_out_items->links() }}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- script --}}
    <script>
        // Cash In Receipt
        let receiptImage = document.getElementById('receiptImage');
        let downloadReceipt = document.getElementById('downloadReceipt');
        let receiptName = document.getElementById('receiptTitle');
        let validationReceipt = document.getElementById('validationReceipt');

        function setInReceipt(receipt, financial_name) {
            validationReceipt.innerText = 'Financial In Charge is ' + financial_name;
            receiptImage.setAttribute('src', '/storage/images/receipt/cash_in/' + receipt);
            downloadReceipt.setAttribute('href', '/storage/images/receipt/cash_in/' + receipt);
            receiptName.innerText = receipt;
        };
    </script>
</x-app-layout>
