<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\GoodsOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoodOrderController extends Controller
{
    // Add new order to cart list
    function addOrder(Request $request, $id = 0)
    {
        if ($request->input('variant_id') == null) {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'You are not allowed to add order. Please contact product PIC.']);
        }

        if (!Auth::user()->product) {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'You are not authorize. Please contact product PIC.']);
        }

        $order = GoodsOrder::where('sales_id', '=', $id)->where('variant_id', '=', $request->input('variant_id'))->first();

        if ($order) {
            $order->amount += $request->input('amount');
            $result = $order->save();
        } else {
            $result = GoodsOrder::create([
                'sales_id' => $id,
                'variant_id' => $request->input('variant_id'),
                'amount' => $request->input('amount')
            ]);
        }

        if ($result) {
            return redirect()->back()->with('notif', ['type' => 'success', 'message' => 'Success add new order to cart.']);
        } else {
            return redirect()->back()->with('notif', ['type' => 'success', 'message' => 'Failed to add new order to cart. Please try again or contact admin.']);
        }
    }
}
