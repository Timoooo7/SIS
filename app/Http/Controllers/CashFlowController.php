<?php

namespace App\Http\Controllers;

use App\Models\CashInItem;
use App\Models\Department;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Illuminate\View\View;

class CashFlowController extends Controller
{
    public function index(): View
    {
        $data = [
            'financials' => User::where('roles_id', 2)->get(),
            'totalCashOut' => Department::all()->sum('disbursement'),
            'totalCashIn' => CashInItem::all()->sum('price'),
            'cashOut' => Department::all(),
            'cash_in_items' => CashInItem::paginate(5, '*', 'cash_in_page'),
            'cash_out_items' => Program::orderBy('updated_at', 'desc')->paginate(5, '*', 'cash_out_page'),
        ];
        return view('pages.cashflow', $data);
    }

    /**
     * add new CashInItem.
     */
    public function insertCashInItem(Request $request)
    {
        $request->flash();
        // Validating data
        $request->validate([
            'cash_in_name' => ['required', 'string'],
            'cash_in_price' => ['required', 'integer'],
            'cash_in_reciept' => ['required', File::types(['jpg', 'jpeg', 'png', 'heic'])->max(5 * 1024)],
        ]);

        $reciept = $request->file('cash_in_reciept');
        $reciept_name =  'ci_' . str()->snake($request->input('cash_in_name')) . '_' . time() . '.' . $reciept->extension();
        // store reciept file
        $reciept->storePubliclyAs('images/receipt/cash_in', $reciept_name, 'public');
        $data = [
            'financial_id' => Auth::user()->id,
            'name' => $request->input('cash_in_name'),
            'price' => $request->input('cash_in_price'),
            'reciept' => $reciept_name,
            'updated_at' => now(),
            'created_at' => now()
        ];

        // sucees insert
        $cashInItem = new CashInItem();
        if ($cashInItem->insert($data) > 0) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'New cash in item has been added.']);
        };
        return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Can not add new cash in item. Please try again later, or contact admin.']);
    }

    /**
     * update CashIn name.
     */
    public function updateCashInItem(Request $request)
    {
        // check id
        if ($request->input('id_cash_in') == 'null') {
            return back()->with('notif', ['type' => 'warning', 'message' => 'Please select an item.']);
        }

        $cash_in_id = $request->input('id_cash_in');
        $cashInItem = CashInItem::find($cash_in_id);

        // check delete
        if ($request->input('action') == 'delete') {
            return $this->deleteCashInItem($request->input('id'));
        } else {
            // check if everything empty
            if (!$request->input()) {
                return back()->with('notif', ['type' => 'warning', 'message' => 'Give some inputs to update.']);
            }
            // Validating data
            $request->validate([
                'price_cash_in' => ['nullable', 'numeric'],
                'reciept_cash_in' => ['nullable', File::types(['jpg', 'jpeg', 'png', 'heic'])->max(5 * 1024)],
            ]);


            // set default value if input empty
            $price = $request->input('price_cash_in') != null ? $request->input('price_cash_in') : $cashInItem->price;
            // store reciept file
            if ($request->hasFile('reciept_cash_in')) {
                $reciept = $request->file('reciept_cash_in');
                Storage::disk('public')->delete('images/receipt/cash_in/' . $cashInItem->reciept);
                $reciept_name = 'ci_' . str()->snake($cashInItem->program->name) . '_' . time() . '.' . $reciept->extension();
                $reciept->storePubliclyAs('images/receipt/cash_in', $reciept_name, 'public');
            } else {
                $reciept_name = $cashInItem->reciept;
            }
            // set data
            $data = [
                'price' => $price,
                'reciept' => $reciept_name,
                'updated_at' => now(),
            ];
            $cashIn = new cashInItem();
            // sucees update
            if ($cashIn->change($cash_in_id, $data) > 0) {
                return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Cash in item ' . CashInItem::find($cash_in_id)->name . ' is updated.']);
            };
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Can not update cash in item at the moment. Please try again later, or contact admin.']);
        }
    }

    /**
     * delete ExpenseItem.
     */
    public function deleteCashInItem($ci_id)
    {
        $cashInItem = CashInItem::find($ci_id);
        $name = $cashInItem->name;
        // delete budget item
        Storage::disk('public')->delete('images/receipt/cash_in/' . $cashInItem->reciept);
        $cashInItem->delete();

        return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Cash in item ' . $name . ' has been deleted.']);
    }
}
