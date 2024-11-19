<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\BlaterianBalance;
use App\Models\BlaterianGoodBalance;
use App\Models\CashInItem;
use App\Models\Department;
use App\Models\FoodsExpense;
use App\Models\GoodsExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Illuminate\View\View;

class CashFlowController extends Controller
{
    public function index(Request $request): View
    {
        // Retrieve or create session
        $cash_in_session = session('cashIn', ['category' => 'price', 'order' => 'desc']);
        $cash_out_session = session('cashOut', ['category' => 'disbursement', 'order' => 'desc']);
        // Save session to database
        $request->session()->put('cashIn', $cash_in_session);
        $request->session()->put('cashOut', $cash_out_session);
        // Cash Flow filter
        $cash_in_category = $cash_in_session['category'];
        $cash_in_order = $cash_in_session['order'];
        $cash_in_items = CashInItem::orderBy($cash_in_category, $cash_in_order)->get();
        $cash_out_category = $cash_out_session['category'];
        $cash_out_order = $cash_out_session['order'];
        $cash_out_items = Department::orderBy($cash_out_category, $cash_out_order)->get();
        $data = [
            'cash_in_items' => $cash_in_items,
            'cash_out_items' => $cash_out_items,
            'filter' => [
                'cash_in' => [
                    'category' => $cash_in_category,
                    'order' => $cash_in_order,
                ],
                'cash_out' => [
                    'category' => $cash_out_category,
                    'order' => $cash_out_order,
                ]
            ],
        ];
        return view('pages.staff.cashflow', $data);
    }

    /**
     * filter cash In.
     */
    function filterCashIn(Request $request)
    {
        $category = $request->input('category');
        $order = $request->input('order');
        session()->put('cashIn', ['category' => $category, 'order' => $order]);
        return redirect()->route('cashflow');
    }

    /**
     * filter cash Out.
     */
    function filterCashOut(Request $request)
    {
        $category = $request->input('category');
        $order = $request->input('order');
        session()->put('cashOut', ['category' => $category, 'order' => $order]);
        return redirect()->route('cashflow');
    }

    /**
     * add new Cash In Item.
     */
    public function insertCashInItem(Request $request)
    {
        $request->flash();
        // Validating data
        $request->validate([
            'cash_in_name' => ['required', 'string'],
            'cash_in_price' => ['required', 'integer'],
            'cash_in_receipt' => ['required', File::types(['jpg', 'jpeg', 'png', 'heic'])->max(5 * 1024)],
        ]);

        $receipt = $request->file('cash_in_receipt');
        $receipt_name =  'cash_in_' . time() . '.' . $receipt->extension();
        // store receipt file
        $receipt->storePubliclyAs('images/receipt/cash_in', $receipt_name, 'public');
        $data = [
            'financial_id' => Auth::user()->id,
            'name' => $request->input('cash_in_name'),
            'price' => $request->input('cash_in_price'),
            'reciept' => $receipt_name,
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
     * delete Cash In Item.
     */
    public function deleteCashInItem($ci_id)
    {
        $cashInItem = CashInItem::find($ci_id);
        $foods_balance = FoodsExpense::where('category', '=', 'withdraw')->where('category_id', '=', $ci_id)->first();
        $continue = true;
        if ($foods_balance) {
            $balance = BlaterianBalance::first();
            $balance->expense -= $foods_balance->price;
            $balance->balance = $balance->income - $balance->expense;
            $balance->save();
            $foods_balance->delete();
            $continue = false;
        }
        if ($continue) {
            $goods_balance = GoodsExpense::where('category', '=', 'withdraw')->where('category_id', '=', $ci_id)->first();
            if ($goods_balance) {
                $balance = BlaterianGoodBalance::first();
                $balance->expense -= $goods_balance->price;
                $balance->balance = $balance->income - $balance->expense;
                $balance->save();
                $goods_balance->delete();
            }
        }
        // delete budget item
        Storage::disk('public')->delete('images/receipt/cash_in/' . $cashInItem->reciept);
        if ($cashInItem->delete()) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Succes delete Cash In Item.']);
        } else {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Failed to delete Cash In Item. Please try again, or contact admin.']);
        }
    }

    /**
     * validate Cash In Item.
     */
    public function validateCashInItem($ci_id)
    {
        $cashInItem = CashInItem::find($ci_id);
        $cashInItem->financial_id = Auth::user()->id;

        if ($cashInItem->save()) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Succes validate Cash In Item.']);
        } else {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Failed to validate Cash In Item. Please try again, or contact admin.']);
        }
    }
}
