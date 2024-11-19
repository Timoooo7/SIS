<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\BlaterianBalance;
use App\Models\BlaterianGoodBalance;
use App\Models\CashInItem;
use App\Models\GoodsCapital;
use App\Models\GoodsExpense;
use App\Models\GoodsIncome;
use App\Models\GoodsSales;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;

class BlaterianGoodBalanceController extends Controller
{
    //Good Balance

    /**
     * 
     * display foods balance.
     * 
     */
    function balance(Request $request, $default_tab = 1, $refresh = false)
    {
        // Retrieve or create session
        if ($refresh) {
            $this->refreshBalance();
        }
        $income_session = session('balance_cash_in', ['category' => 'price', 'order' => 'desc', 'income' => null]);
        $expense_session = session('balance_cash_out', ['category' => 'price', 'order' => 'desc', 'expense' => null]);
        // Save session to database
        $request->session()->put('balance_cash_in', $income_session);
        $request->session()->put('balance_cash_out', $expense_session);
        // dd($expense_session);
        // Balance filter
        $income_category = $income_session['category'];
        $income_order = $income_session['order'];
        $cash_in_list = $income_session['income'] == 'income' ?
            GoodsIncome::orderBy($income_category, $income_order)->with(['program', 'sales'])->get() :
            GoodsIncome::where('category', '!=', 'goods income')->orderBy($income_category, $income_order)->with(['program', 'sales'])->get();
        $expense_category = $expense_session['category'];
        $expense_order = $expense_session['order'];
        $cash_out_list = $expense_session['expense'] == 'expense' ?
            GoodsExpense::orderBy($expense_category, $expense_order)->with(['withdraw', 'capital'])->get() :
            GoodsExpense::where('category', '!=', 'goods expense')->orderBy($expense_category, $expense_order)->with(['withdraw', 'capital'])->get();

        $balance = BlaterianGoodBalance::find(1);
        $balance = $balance ? $balance : BlaterianGoodBalance::create();

        $income = GoodsSales::where('operational_id', '>', 0)->sum('transaction');
        $expense = GoodsCapital::where('operational_id', '>', 0)->sum('total_price');

        $variant_list = ProductVariant::orderBy('sale', 'desc')->with('product')->take(5)->get();

        $labels = collect([]);
        $sales = collect([]);
        foreach ($variant_list as $variant) {
            $labels->push($variant->product->name . ' : ' . $variant->name);
            $sales->push($variant->sale);
        }
        $chartData = [
            'labels' => $labels->all(),
            'sales' => $sales->all(),
        ];
        // dd($cash_out_list->first()->category_detail());
        $data = [
            'balance' => $balance,
            'total_income' => $income,
            'total_expense' => $expense,
            'income' => $cash_in_list,
            'expense' => $cash_out_list,
            'chartData' => $chartData,
            'default_tab' => $default_tab,
            'filter' => [
                'cash_in' => [
                    'category' => $income_category,
                    'order' => $income_order,
                    'income' => $income_session['income'],
                ],
                'cash_out' => [
                    'category' => $expense_category,
                    'order' => $expense_order,
                    'expense' => $expense_session['expense'],
                ],
            ]
        ];

        return view('pages.staff.good.balance', $data);
    }

    /**
     * filter cash in balance.
     */
    function filterCashIn(Request $request)
    {
        $category = !$request->input('income') ? $request->input('category') : session('balance_cash_in')['category'];
        $order = !$request->input('income') ? $request->input('order') : session('balance_cash_in')['order'];
        $income = $request->input('income') ? $request->input('income') : session('balance_cash_in')['income'];
        session()->put('balance_cash_in', ['category' => $category, 'order' => $order, 'income' => $income]);
        return redirect()->route('good.balance', ['default_tab' => 1]);
    }

    /**
     * filter cash put balance.
     */
    function filterCashOut(Request $request)
    {
        $category = !$request->input('expense') ? $request->input('category') : session('balance_cash_out')['category'];
        $order = !$request->input('expense') ? $request->input('order') : session('balance_cash_out')['order'];
        $expense = $request->input('expense') ? $request->input('expense') : session('balance_cash_out')['expense'];
        session()->put('balance_cash_out', ['category' => $category, 'order' => $order, 'expense' => $expense]);
        return redirect()->route('good.balance', ['default_tab' => 2]);
    }

    /**
     * 
     * refresh stand cash flow to makesure it is accurate.
     * 
     *  @var $id is program id, @var $add to determine add or minus 
     */
    static function refreshBalance(): void
    {
        // retrieve models
        $goodsExpense = GoodsExpense::sum('price');
        $goodsIncome = GoodsIncome::sum('price');
        // update balance or create new balance if it doesn't exist
        $balance = BlaterianGoodBalance::find(1);
        if ($balance) {
            $balance->expense = $goodsExpense;
            $balance->income = $goodsIncome;
            $balance->balance = $goodsIncome - $goodsExpense;
            $balance->save();
        } else {
            BlaterianBalance::create([
                'expense' => $goodsExpense,
                'income' => $goodsIncome,
                'balance' => $goodsIncome - $goodsExpense,
            ]);
        }
    }

    function withdrawBalance(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'receipt' => File::types(['jpg', 'jpeg', 'png', 'heic'])->max(5 * 1024),
        ]);

        $receipt = $request->file('receipt');
        $receipt_name =  'cash_in_' . time() . '.' . $receipt->extension();
        // store receipt file
        $receipt->storePubliclyAs('images/receipt/cash_in', $receipt_name, 'public');

        $seeo_cash_in = CashInItem::create([
            'financial_id' => null,
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'reciept' => $receipt_name,
            'updated_at' => now(),
            'created_at' => now()
        ]);

        $good_cash_out = GoodsExpense::create([
            'category_id' => $seeo_cash_in->id,
            'category' => 'withdraw',
            'price' => $request->input('price'),
        ]);

        $this->refreshBalance();
        if ($seeo_cash_in && $good_cash_out) {
            return redirect()->route('good.balance', ['default_tab' => 2])->with('notif', ['type' => 'info', 'message' => 'Success send money to SEEO Cash Flow. Please ask Financial Officer to validate.']);
        } else {
            return redirect()->route('good.balance', ['default_tab' => 2])->with('notif', ['type' => 'warning', 'message' => 'Failed send money to SEEO Cash Flow. Please try again or ask admin.']);
        }
    }
}
