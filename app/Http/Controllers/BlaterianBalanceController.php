<?php

namespace App\Http\Controllers;

use App\Models\BlaterianBalance;
use App\Models\FoodsExpense;
use App\Models\FoodsIncome;
use Illuminate\Http\Request;
use NumberFormatter;

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
        // dd($income_list);
        $data = [
            'title' => 'Blaterian Foods Balance',
            'balance' => BlaterianBalance::find(1),
            'income' => $income_list,
            'expense' => $expense_list,
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
