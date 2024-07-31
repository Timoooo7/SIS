<?php

namespace App\Http\Controllers;

use App\Models\BlaterianBalance;
use App\Models\FoodsExpense;
use App\Models\FoodsIncome;
use Illuminate\Http\Request;

class BlaterianBalanceController extends Controller
{
    //Food Balance

    /**
     * 
     * display foods balance.
     * 
     */
    function balance()
    {
        $data = [
            'title' => 'Blaterian Foods Balance',
        ];
        return view('pages.food.balance', $data);
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
