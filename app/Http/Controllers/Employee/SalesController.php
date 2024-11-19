<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\FoodOrder;
use App\Models\FoodsIncome;
use App\Models\MenuItem;
use App\Models\Stand;
use App\Models\StandSales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SalesController extends Controller
{
    /**
     * Display foods sales.
     */
    public function sales(Request $request, $id = 0): View
    {
        $stand = Stand::where('sale_validation', '==', 0)->where('menu_lock', '!=', 0)->orderBy('created_at', 'desc')->get();
        $stand_find = Stand::where('sale_validation', '==', 0)->where('menu_lock', '!=', 0)->find($id);
        $active_stand = $stand_find == null ? $stand->first() : $stand_find;
        $menu_list = $active_stand ? MenuItem::where('stand_id', $active_stand->id)->orderBy('name', 'asc')->get() : null;
        $data = [
            'menu_list' => $menu_list,
            'stand_list' => $stand,
            'active_stand' => $active_stand,
        ];
        return view('pages.staff.food.sales', $data);
    }

    /**
     * Create token for cashier authorization.
     */
    public function generateToken($id)
    {
        $token = random_int(100000, 999999);
        $stand = Stand::find($id);
        $stand->cashier_token = $token;

        if ($stand->save() > 0) {
            return redirect()->route('food.stand', ['id' => $id, 'default_tab' => 3, 'default_collapse' => 1])->with('notif', ['type' => 'success', 'message' => 'Stand ' . $stand->name . ' cashier token is : ' . $token]);
        } else {
            return redirect()->route('food.stand', ['id' => $id, 'default_tab' => 3, 'default_collapse' => 1])->with('notif', ['type' => 'success', 'message' => 'Failed to generate cashier token. Please try again or contact admin.']);
        }
    }

    /**
     * Filtering stand income order.
     */
    function filterStandIncome(Request $request, $id)
    {
        $category = $request->input('category');
        $order = $request->input('order');
        session()->put('stand_income', ['category' => $category, 'order' => $order]);
        return redirect()->route('food.stand', ['id' => $id, 'default_tab' => 3, 'default_collapse' => 1]);
    }

    /**
     * add new Sale Item.
     */
    public function insertSale(Request $request, $id)
    {
        $request->flash();
        // Validating data
        $request->validate([
            'token' => ['required', 'integer'],
            'discount' => ['required', 'integer'],
            'transaction' => ['required', 'integer'],
            'customer' => ['required', 'string'],
            'order' => 'required|array',
            'order.*.menu_id' => 'required|string|min:1',
            'order.*.amount' => 'required|integer|min:1',
        ]);
        // Checking Cashier Token
        $stand = Stand::find($id);
        if ($request->input('token') != $stand->cashier_token) {
            return back()->with('notif', ['type' => 'danger', 'message' => 'Your token is not valid. Please ask the stand PIC to regenerate the token.']);
        }
        $sale = StandSales::create([
            'cashier_id' => Auth::user()->id,
            'stand_id' => $id,
            'discount' => $request->input('discount'),
            'transaction' => $request->input('transaction'),
            'customer' => $request->input('customer'),
        ]);

        $order_list = $request->input('order');
        foreach ($order_list as $order) {
            $menu = MenuItem::find($order['menu_id']);
            $menu->sale += $order['amount'];
            $menu->save();

            FoodOrder::create([
                'sales_id' => $sale->id,
                'menu_id' => $order['menu_id'],
                'amount' => $order['amount'],
            ]);
        }
        return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success add new transaction to Stand ' . $stand->name]);
    }

    /**
     * delete selected Sale Item.
     */
    public function deleteSale($id)
    {
        $sale_item = StandSales::with(['stand', 'order', 'order.menu'])->find($id);
        $stand = $sale_item->stand;
        $name = $sale_item->customer;

        $order_list = $sale_item->order;
        foreach ($order_list as $order) {
            // update menu sale
            $menu = $order->menu;
            $menu->sale = $menu->sale - $order->amount;
            $menu->save();
            // delete order
            $order->delete();
        }

        // delete sale item
        if ($sale_item->delete()) {
            return redirect()->route('food.stand', ['id' => $stand->id, 'default_tab' => 3, 'default_collapse' => 1])->with('notif', ['type' => 'info', 'message' => 'Success delete ' . $name . ' transaction from Stand ' . $stand->name . ' Income.']);
        } else {
            return redirect()->route('food.stand', ['id' => $stand->id, 'default_tab' => 3, 'default_collapse' => 1])->with('notif', ['type' => 'info', 'message' => 'Failed to delete ' . $name . ' transaction from Stand ' . $stand->name . ' Income. Please try again or contact admin']);
        }
    }

    /**
     * validate sales item by Operational.
     */

    public function validateSales($id, $valid)
    {
        $auth_user = Auth::user();
        $sale_validation = $valid > 0  ? $auth_user->id : 0;
        $stand = Stand::find($id);
        $stand->sale_validation = $sale_validation;
        $validation = $valid ? 'validate' : 'unvalidate';
        $this->updateStandIncome($id);
        if ($stand->save()) {
            return redirect()->route('food.stand', ['id' => $id, 'default_tab' => 3, 'default_collapse' => 1])->with('notif', ['type' => 'info', 'message' => 'Succes ' . $validation . ' Stand ' . $stand->name . ' Income.']);
        } else {
            return redirect()->route('food.stand', ['id' => $id, 'default_tab' => 3, 'default_collapse' => 1])->with('notif', ['type' => 'info', 'message' => 'Failed to ' . $validation . ' Stand ' . $stand->name . ' Income. Please try again or contact admin.']);
        }
    }

    /**
     * update new stand total income.
     * 
     * 
     *  @var $id is stand id, @var $add to determine add or minus 
     */
    public function updateStandIncome(int $id)
    {
        // retrieve foods income model
        $foodsIncome = FoodsIncome::where('category', '=', 'stand income')->where('category_id', '=', $id)->first();
        // set new income
        $new_income = StandSales::where('stand_id', '=', $id)->sum('transaction');

        // update stand income
        $stand = Stand::find($id);
        $stand->income = $new_income;
        $stand->profit = $stand->income - $stand->expense;
        $stand->updated_at = now();
        $stand->save();

        // save foods income model
        $foodsIncome->price = $new_income;
        $foodsIncome->updated_at = now();
        $foodsIncome->save();

        return BlaterianFoodBalanceController::refreshBalance();
    }
}
