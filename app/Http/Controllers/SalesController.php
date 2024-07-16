<?php

namespace App\Http\Controllers;

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
    public function sales(Request $request): View
    {
        $stand = Stand::where('sale_validation', '==', 0)->where('menu_lock', '!=', 0)->orderBy('id', 'desc')->first();
        // dd($stand);
        $menu_items = $stand != null ? MenuItem::where('stand_id', $stand->id)->get() : null;
        $data = [
            'menu_items' => $menu_items,
            'stand' => $stand,
        ];
        return view('pages.food.sales', $data);
    }

    /**
     * Create token for cashier authorization.
     */
    public function generateToken($stand_id)
    {
        $token = random_int(100000, 999999);
        $stand = new Stand();
        $stand->change($stand_id, ['cashier_token' => $token]);
        return back()->with('notif', ['type' => 'success', 'message' => 'Cashier Token is : ' . $token]);
    }

    /**
     * add new Sale Item.
     */
    public function insertSale(Request $request, $stand_id)
    {
        $request->flash();
        // Validating data
        $integer1 = $request->input('sale_discount') !== null ? 'integer' : '';
        $string1 = $request->input('sale_customer') !== null ? 'string' : '';
        $request->validate([
            'cashier_id' => ['required', 'integer'],
            'cashier_token' => ['required', 'integer'],
            'sale_menu_id' => ['required', 'integer'],
            'sale_amount' => ['required', 'integer'],
            'sale_discount' => [$integer1],
            'sale_customer' => [$string1],
        ]);
        // Checking Cashier Token
        $stand = Stand::find($stand_id);
        if ($request->input('cashier_token') != $stand->cashier_token) {
            return back()->with('notif', ['type' => 'danger', 'message' => 'Your token is not valid. Please regenerate the token.']);
        }
        $menu = MenuItem::find($request->input('sale_menu_id'));
        $discount = $integer1 === '' ? 0 : $request->input('sale_discount');
        $transaction =  ($menu->price * $request->input('sale_amount')) - $discount;
        $data_sale = [
            'stand_id' => $stand_id,
            'cashier_id' => $request->input('cashier_id'),
            'menu_id' => $request->input('sale_menu_id'),
            'amount' => $request->input('sale_amount'),
            'discount' => $discount,
            'transaction' => $transaction,
            'customer' => $request->input('sale_customer'),
            'updated_at' => now(),
            'created_at' => now()
        ];
        // update menu sale
        $menu->sale = $menu->sale + $request->input('sale_amount');
        $menu->save();

        // sucees insert
        $sale_item = new StandSales();
        if ($sale_item->insert($data_sale) > 0) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'New sale item has been added.']);
        };
        return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Can not add new sale item. Please try again later, or contact admin.']);
    }

    /**
     * update selected Sale Item.
     */
    public function updateSale(Request $request, $stand_id)
    {
        $request->flash();
        $stand = Stand::find($stand_id);

        // Auth check
        if (Auth::user()->id !== $stand->pic_id) {
            return back()->with('notif', ['type' => 'danger', 'message' => 'Please contact the PIC to edit Sales Item.']);
        }

        // Validating data
        $integer1 = $request->input('sale_update_discount') !== null ? 'integer' : '';
        $integer2 = $request->input('sale_update_amount') !== null ? 'integer' : '';
        $string1 = $request->input('sale_update_customer') !== null ? 'string' : '';
        $request->validate([
            'sale_update_id' => ['required', 'integer'],
            'sale_update_amount' => [$integer2],
            'sale_update_discount' => [$integer1],
            'sale_update_customer' => [$string1],
        ]);

        // Validation check
        if ($stand->sale_validation !== 0) {
            return back()->with('notif', ['type' => 'warning', 'message' => 'Sale is validated, no changes permitted.']);
        }

        // check delete
        if ($request->input('action') == 'delete') {
            return $this->deleteSale($request->input('sale_update_id'));
        }

        // Passed all requirement
        $sale_item = StandSales::find($request->input('sale_update_id'));
        $discount = $integer1 === '' ? $sale_item->discount : $request->input('sale_update_discount');
        $amount = $integer2 === '' ? $sale_item->amount : $request->input('sale_update_amount');
        $transaction =  ($sale_item->menu->price * $amount) - $discount;
        $customer = $string1 === '' ? $sale_item->customer : $request->input('sale_update_customer');

        // update menu sale
        if ($integer2 !== '') {
            $menu = $sale_item->menu;
            $menu->sale =  $menu->sale + $amount - $sale_item->amount;
            $menu->save();
        }

        $name = $sale_item->menu->name;

        // sucees insert
        $sale_item->discount = $discount;
        $sale_item->amount = $amount;
        $sale_item->transaction = $transaction;
        $sale_item->customer = $customer;
        $sale_item->save();

        return redirect()->back()->with('notif', ['type' => 'info', 'message' => $name . ' is updated.']);
    }

    /**
     * delete selected Sale Item.
     */
    public function deleteSale($sale_id)
    {
        $sale_item = StandSales::find($sale_id);
        $name = $sale_item->menu->name;

        // update menu sale
        $menu = $sale_item->menu;
        $menu->sale = $menu->sale - $sale_item->amount;
        $menu->save();

        // delete sale item
        $sale_item->delete();

        return redirect()->back()->with('notif', ['type' => 'warning', 'message' => $name . ' has been deleted.']);
    }

    /**
     * validate sales item by Operational.
     */

    public function validateSales(Request $request, $stand_id)
    {
        $stand = Stand::find($stand_id);
        if ($stand->sale() == null) {
            return back()->with('notif', ['type' => 'warning', 'message' => 'Sale item is empty, please add some sales then validate.']);
        }
        $stand->sale_validation = $request->input('operational_id');
        $stand->save();
        $standController = new StandController();
        $standController->refreshStandCashFlow($stand_id);
        return back()->with('notif', ['type' => 'info', 'message' => $stand->name . ' sale items are validated.']);
    }
}
