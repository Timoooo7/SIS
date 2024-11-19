<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\BlaterianBalance;
use App\Models\CashInItem;
use App\Models\FoodsExpense;
use App\Models\FoodsIncome;
use App\Models\Stand;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rules\File;

class BlaterianFoodBalanceController extends Controller
{
    //Food Balance

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
        $income_session = session('balance_income', ['category' => 'price', 'order' => 'desc']);
        $expense_session = session('balance_expense', ['category' => 'price', 'order' => 'desc']);
        // Save session to database
        $request->session()->put('balance_income', $income_session);
        $request->session()->put('balance_expense', $expense_session);
        // Balance filter
        $income_category = $income_session['category'];
        $income_order = $income_session['order'];
        $income_list = FoodsIncome::orderBy($income_category, $income_order)->with(['program', 'stand'])->get();
        $expense_category = $expense_session['category'];
        $expense_order = $expense_session['order'];
        $expense_list = FoodsExpense::orderBy($expense_category, $expense_order)->with(['withdraw', 'stand'])->get();

        // Chart Data
        $chart_raw = Stand::orderBy('updated_at', 'desc')->get(['date', 'profit', 'expense', 'income']);
        $chart_group = $chart_raw->groupBy(function ($chart_raw) {
            return strval(date_format(date_create($chart_raw->date), 'm'));
        });
        $profit_chart = $chart_group->map(function ($profit) {
            return $profit->sum('profit');
        });
        $expense_chart = $chart_group->map(function ($expense) {
            return $expense->sum('expense');
        });
        $income_chart = $chart_group->map(function ($income) {
            return $income->sum('income');
        });
        $chart = [
            'month' => $profit_chart->keys()->map(function ($month) {
                return Carbon::createFromFormat('m', strval($month))->format('F');
            }),
            'profit' => $profit_chart->values(),
            'expense' => $expense_chart->values(),
            'income' => $income_chart->values(),
        ];
        $stands = Stand::all();
        $data = [
            'title' => 'Blaterian Foods Balance',
            'balance' => BlaterianBalance::find(1),
            'total_income' => $stands->sum('income'),
            'total_expense' => $stands->sum('expense'),
            'income' => $income_list,
            'expense' => $expense_list,
            'chart' => $chart,
            'default_tab' => $default_tab,
            'filter' => [
                'income' => [
                    'category' => $income_category,
                    'order' => $income_order,
                ],
                'expense' => [
                    'category' => $expense_category,
                    'order' => $expense_order,
                ],
            ]
        ];
        return view('pages.staff.food.balance', $data);
    }

    /**
     * filter cash Income.
     */
    function filterIncome(Request $request)
    {
        $category = $request->input('category');
        $order = $request->input('order');
        session()->put('balance_income', ['category' => $category, 'order' => $order]);
        return redirect()->route('food.balance', ['default_tab' => 1]);
    }

    /**
     * filter cash Expense.
     */
    function filterExpense(Request $request)
    {
        $category = $request->input('category');
        $order = $request->input('order');
        session()->put('balance_expense', ['category' => $category, 'order' => $order]);
        return redirect()->route('food.balance', ['default_tab' => 2]);
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
        $foodsExpense = FoodsExpense::sum('price');
        $foodsIncome = FoodsIncome::sum('price');

        // update balance or create new balance if it doesn't exist
        $balance = BlaterianBalance::find(1);
        if ($balance) {
            $balance->expense = $foodsExpense;
            $balance->income = $foodsIncome;
            $balance->balance = $foodsIncome - $foodsExpense;
            $balance->save();
        } else {
            BlaterianBalance::create([
                'expense' => $foodsExpense,
                'income' => $foodsIncome,
                'balance' => $foodsIncome - $foodsExpense,
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

        $food_cash_out = FoodsExpense::create([
            'category_id' => $seeo_cash_in->id,
            'category' => 'withdraw',
            'price' => $request->input('price'),
        ]);

        $this->refreshBalance();

        if ($seeo_cash_in && $food_cash_out) {
            return redirect()->route('food.balance', ['default_tab' => 2])->with('notif', ['type' => 'info', 'message' => 'Success send money to SEEO Cash Flow. Please ask Financial Officer to validate.']);
        } else {
            return redirect()->route('food.balance', ['default_tab' => 2])->with('notif', ['type' => 'warning', 'message' => 'Failed send money to SEEO Cash Flow. Please try again or ask admin.']);
        }
    }
}
