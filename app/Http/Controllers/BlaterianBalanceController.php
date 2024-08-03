<?php

namespace App\Http\Controllers;

use App\Models\BlaterianBalance;
use App\Models\FoodsExpense;
use App\Models\FoodsIncome;
use App\Models\Stand;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BlaterianBalanceController extends Controller
{
    //Food Balance

    /**
     * 
     * display foods balance.
     * 
     */
    function balance(Request $request)
    {
        $income = session('income', ['category' => 'updated_at', 'order' => 'desc']);
        $expense = session('expense', ['category' => 'updated_at', 'order' => 'desc']);
        // Save to session
        $request->session()->put('income', $income);
        $request->session()->put('expense', $expense);
        // Stand filter
        $income_list = FoodsIncome::with(['program'])->orderBy($income['category'], $income['order'])->get();
        $expense_list = FoodsExpense::with(['program'])->orderBy($expense['category'], $expense['order'])->get();
        // Chart Data
        $chart_raw = Stand::orderBy('date', 'asc')->get(['date', 'profit', 'expense', 'income']);
        $chart_group = $chart_raw->groupBy(function ($chart_raw) {
            return date_format(date_create($chart_raw->date), 'm');
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
                return Carbon::createFromFormat('m', $month)->format('F');
            }),
            'profit' => $profit_chart->values(),
            'expense' => $expense_chart->values(),
            'income' => $income_chart->values(),
        ];
        // dd($profit);
        $data = [
            'title' => 'Blaterian Foods Balance',
            'balance' => BlaterianBalance::find(1),
            'income' => $income_list,
            'expense' => $expense_list,
            'chart' => $chart,
            'filter' => [
                'income' => $income,
                'expense' => $expense,
            ]
        ];
        return view('pages.food.balance', $data);
    }

    /**
     * find blaterian balance.
     */
    public function findBalance($balance, $category, $order)
    {
        session([$balance => ['category' => $category, 'order' => $order]]);
        return back();
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
        $balance = BlaterianBalance::orderBy('updated_at', 'desc')->first();
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
}
