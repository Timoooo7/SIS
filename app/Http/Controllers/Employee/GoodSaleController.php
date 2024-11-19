<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\BlaterianGoodBalance;
use App\Models\GoodsIncome;
use App\Models\GoodsOrder;
use App\Models\GoodsSales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoodSaleController extends Controller
{
    function addCart(Request $request)
    {
        $request->flash();
        $request->validate(['customer' => ['required', 'string']]);

        if (!Auth::user()->product) {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'You are not authorized to add Cart.']);
        }

        $cart = GoodsSales::create([
            'customer' => $request->input('customer'),
        ]);

        if ($cart) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success add new Cart.']);
        } else {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Failed to add new Cart. Please try again or contact admin.']);
        }
    }

    function addTransaction(Request $request, $id)
    {
        $auth_user = Auth::user();
        if ($id == 0) {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Cart is not existed. Please make a cart.']);
        }

        if ($auth_user->product->count() <= 0 && $auth_user->roles_id !== 3) {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'You are not authorize to add transaction. Please ask to Product PIC or Operational Officer.']);
        }

        $order_list = $request->input('order');
        foreach ($order_list as $order_id => $amount) {
            $order = GoodsOrder::find($order_id);
            if ($amount > 0) {
                // dd($order->variant);
                $order->amount = $amount;
                $order->save();

                $variant = $order->variant;
                $variant->sale += $amount;
                $variant->save();
            } else {
                $order->delete();
            }
        }
        $transaction = $request->input('transaction');
        $sale = GoodsSales::find($id);
        $sale->transaction = $transaction;
        $sale->cashier_id = $auth_user->id;

        if ($sale->save()) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success add new goods transaction.']);
        } else {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Failed to add new goods transaction. Please try again or contact admin.']);
        }
    }

    function cancelTransaction(Request $request, $id)
    {
        $transaction = GoodsSales::find($id);
        $order_list = $transaction->order;
        if ($order_list->count() > 0) {
            foreach ($order_list as $order) {
                $order->delete();
            }
        }
        $customer = $transaction->customer;
        if ($transaction->delete()) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success canceled ' . $customer . ' transaction.']);
        } else {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Failed to canceled ' . $customer . ' transaction. Please try again or contact admin.']);
        }
    }

    function validateSale(Request $request, $id, $valid)
    {
        $sale = GoodsSales::find($id);
        $sale->operational_id = $valid ? Auth::user()->id : null;
        $goods_income = GoodsIncome::where('category_id', '=', $id)->where('category', '=', 'goods income')->first();
        // update goods income
        $update = $valid ?
            GoodsIncome::create([
                'category' => 'goods income',
                'category_id' => $id,
                'price' => $sale->transaction,
            ]) :
            $goods_income->forceDelete();

        $balance = BlaterianGoodBalance::first();
        $balance->income = $valid ? $balance->income + $sale->transaction : $balance->income - $sale->transaction;
        $balance->balance = $balance->income - $balance->expense;

        if ($sale->save() && $update && $balance->save()) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success ' . ($valid ? 'validate' : 'unvalidate') . ' ' . $sale->customer . ' transaction.']);
        } else {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Failed to ' . ($valid ? 'validate' : 'unvalidate') . ' ' . $sale->customer . ' transaction. Please try again or contact admin.']);
        }
    }

    function deleteSale(Request $request, $id)
    {
        $sale = GoodsSales::find($id);
        if ($sale->operational_id > 0) {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'This sale has been validated. Please unvalidate it first.']);
        }
        if ($sale->delete()) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success delete ' . $sale->customer . ' transaction.']);
        } else {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Failed to delete ' . $sale->customer . ' transaction. Please try again or contact admin.']);
        }
    }
}
