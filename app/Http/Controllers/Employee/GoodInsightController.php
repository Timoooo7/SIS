<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\GoodsCapital;
use App\Models\GoodsExpense;
use App\Models\GoodsSales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class GoodInsightController extends Controller
{
    /**
     * Show insight page of product.
     */
    function insight(Request $request)
    {
        // Retrieve or create session
        $sale_session = session('sale', ['category' => 'created_at', 'order' => 'desc', 'keyword' => null]);
        $capital_session = session('capital', ['category' => 'created_at', 'order' => 'desc']);
        // Save session to database
        $request->session()->put('sale', $sale_session);
        $request->session()->put('capital', $capital_session);
        // Sale filter
        $sale_category = $sale_session['category'];
        $sale_order = $sale_session['order'];
        $sale_keyword = $sale_session['keyword'];
        $sale_list = $sale_keyword !== null ? GoodsSales::where('transaction', '>', 0)->with(['operational', 'cashier', 'order' => ['variant' => ['product']]])->orderByRaw("
                CASE
                WHEN customer = ? THEN 1
                WHEN customer LIKE ? THEN 2
                WHEN customer LIKE ? THEN 3
                ELSE 4
                END 
            ", [$sale_keyword, "$sale_keyword%", "%$sale_keyword%"])->get()
            : GoodsSales::where('transaction', '>', 0)->orderBy($sale_category, $sale_order)->with(['operational', 'cashier', 'order' => ['variant' => ['product']]])->get();
        // Capital filter
        $capital_category = $capital_session['category'];
        $capital_order = $capital_session['order'];
        $capital_list = GoodsCapital::with(['operational'])->orderBy($capital_category, $capital_order)->get();
        $data = [
            'filter' => [
                'sale' => [
                    'category' => $sale_category,
                    'order' => $sale_order,
                    'keyword' => $sale_keyword,
                ],
                'capital' => [
                    'category' => $capital_category,
                    'order' => $capital_order,
                ]
            ],
            'sale_list' => $sale_list,
            'capital_list' => $capital_list,
        ];
        return view('pages.staff.good.insight', $data);
    }

    /**
     * Filtering product list order.
     */
    function filterInsight(Request $request, $filter_name = 'sale')
    {
        $keyword = $request->has('keyword') ? $request->input('keyword') : null;
        $category = !$request->has('keyword') && $request->has('category') ?  $request->input('category') : 'name';
        $order = !$request->has('keyword') && $request->has('order') ?  $request->input('order') : 'asc';
        session()->put($filter_name, ['category' => $category, 'order' => $order, 'keyword' => $keyword,]);
        return redirect()->route('good.insight');
    }

    /**
     * Filtering product list order.
     */
    function insertCapital(Request $request)
    {
        if (!Auth::user()->product) {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'You are not in charge of any product. Please contact the person in charge.']);
        }
        $request->flash();
        // Validating data
        $request->validate([
            'name' => ['required', 'string'],
            'price' => ['required', 'integer'],
            'qty' => ['required', 'integer'],
            'unit' => ['required', 'string'],
            'receipt' => [Rule::requiredIf($request->input('same_receipt_check') != 'on'), File::types(['jpg', 'jpeg', 'png', 'heic'])->max(5 * 1024)],
            'receipt_same' => [Rule::requiredIf($request->input('same_receipt_check') == 'on'), 'integer'],
        ]);
        $last = GoodsCapital::orderBy('id', 'desc')->first();
        $last_id = $last ? $last->id : 0;
        if ($request->input('same_receipt_check') != 'on') {
            $receipt = $request->file('receipt');
            $receipt_name =  'GE' . $last_id + 1 . '_receipt.' . $receipt->extension();
            // store reciept file
            $receipt->storePubliclyAs('images/receipt/goods/expense', $receipt_name, 'public');
        } else {
            $receipt_name = GoodsCapital::find($request->input('receipt_same'))->receipt;
        }
        $total_price =  $request->input('qty') *  $request->input('price');
        $goods_expense = GoodsCapital::create([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'qty' => $request->input('qty'),
            'unit' => $request->input('unit'),
            'total_price' => $total_price,
            'receipt' => $receipt_name,
            'updated_at' => now(),
            'created_at' => now()
        ]);

        // sucees insert
        if ($goods_expense) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success to add new Goods Expense.']);
        } else {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Failed to add new Goods Expense. Please try again or contact admin.']);
        };
    }

    /**
     * Change the validatiion status of the product receipt.
     */
    function validateCapital(Request $request)
    {
        $id = $request->input('receipt_id');
        $capital = GoodsCapital::find($id);
        if (!$capital) {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Can not find the goods expense. Please contact admin.']);
        }
        // Update data 
        $is_valid = $capital->operational_id == 0;
        $capital->operational_id = $is_valid ? Auth::user()->id : null;
        $goods_expense_balance = $is_valid ? GoodsExpense::create([
            'category' => 'goods expense',
            'category_id' => $id,
            'price' => $capital->total_price,
        ]) : GoodsExpense::where('category', '=', 'Goods Expense')->where('category_id', '=', $id)->first()->delete();

        if ($capital->save() && $goods_expense_balance) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success ' . ($capital->operational_id > 0 ? 'validate' : 'unvalidate') . ' goods expense item.']);
        } else {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Failed to ' . ($capital->operational_id > 0 ? 'validate' : 'unvalidate') . ' goods expense item. Please try again or contact admin.']);
        }
    }

    /**
     * Delete the product capital.
     */
    function deleteCapital(Request $request, $id)
    {
        if (!Auth::user()->product) {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'You are not in charge of any product. Please contact the person in charge.']);
        }
        $capital = GoodsCapital::find($id);
        if (!$capital) {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Can not find the goods expense. Please contact admin.']);
        }
        if ($capital->operational_id > 0) {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'This goods expense has been validated by Operational Officer.']);
        }
        // update necessary data
        if (GoodsCapital::where('receipt', '=', $capital->receipt)->get()->count() == 0) {
            // remove capital receipt
            Storage::disk('public')->move('images/receipt/goods/expense/' . $capital->receipt, 'trash/images/receipt/goods/expense/' . $capital->receipt);
        }
        if ($capital->delete()) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success delete goods expense item.']);
        } else {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Failed to delete goods expense item. Please try again or contact admin.']);
        }
    }
}
