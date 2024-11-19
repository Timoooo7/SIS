<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\FoodsExpense;
use App\Models\FoodsIncome;
use App\Models\MenuItem;
use App\Models\Stand;
use App\Models\StandExpense;
use App\Models\StandSales;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\View\View;

class StandController extends Controller
{
    /**
     * Display foods stand.
     */
    public function stand(Request $request, $id = 0, $default_tab = 1, $default_collapse = 2): View
    {
        // Retrieve or create session
        $stand_session = session('stand', ['category' => 'date', 'order' => 'desc', 'keyword' => null]);
        $menu_session = session('stand_menu', ['category' => 'name', 'order' => 'asc']);
        $income_session = session('stand_income', ['category' => 'created_at', 'order' => 'desc']);
        $expense_session = session('stand_expense', ['category' => 'created_at', 'order' => 'desc']);
        // Save session to database
        $request->session()->put('stand', $stand_session);
        $request->session()->put('stand_menu', $menu_session);
        $request->session()->put('stand_income', $income_session);
        $request->session()->put('stand_expense', $expense_session);
        // Balance filter
        $stand_category = $stand_session['category'];
        $stand_order = $stand_session['order'];
        $stand_keyword = $stand_session['keyword'];
        $stand_list = $stand_keyword !== null ?
            Stand::select('id', 'name', 'sale_validation', 'menu_lock')->orderByRaw("
                CASE
                WHEN name = ? THEN 1
                WHEN name LIKE ? THEN 2
                WHEN name LIKE ? THEN 3
                ELSE 4
                END 
            ", [$stand_keyword, "$stand_keyword%", "%$stand_keyword%"])->get()
            : Stand::select('id', 'name', 'sale_validation', 'menu_lock')->orderBy($stand_category, $stand_order)->get();
        $stand_id = $stand_list->first() ? $stand_list->first()->id : 0;
        $stand = Stand::find($id == 0 ? $stand_id : $id);
        $stand_id = $stand ? $stand->id : 0;
        $menu_category = $menu_session['category'];
        $menu_order = $menu_session['order'];
        $menu_list = MenuItem::where('stand_id', $stand_id)->orderBy($menu_category, $menu_order)->get();
        $income_category = $income_session['category'];
        $income_order = $income_session['order'];
        $income_list = StandSales::where('stand_id', $stand_id)->orderBy($income_category, $income_order)->with(['order' => ['menu'], 'cashier'])->get();
        $expense_category = $expense_session['category'];
        $expense_order = $expense_session['order'];
        $expense_list = StandExpense::where('stand_id', $stand_id)->orderBy($expense_category, $expense_order)->with(['operational'])->get();
        $data = [
            'sidebar' => 'blaterian',
            'default_tab' => $default_tab,
            'default_collapse' => $default_collapse,
            'users' => User::where('roles_id', '!=', null)->get(),
            'active_stand' => $stand,
            'stand_list' => $stand_list,
            'menu_list' => $menu_list,
            'income_list' => $income_list,
            'expense_list' => $expense_list,
            'filter' => [
                'stand' => [
                    'category' => $stand_category,
                    'order' => $stand_order,
                    'keyword' => $stand_keyword,
                ],
                'menu' => [
                    'category' => $menu_category,
                    'order' => $menu_order,
                ],
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
        return view('pages.staff.food.stand', $data);
    }

    /**
     * Filtering stand order.
     */
    function filterStand(Request $request, $id)
    {
        $keyword = $request->has('keyword') ? $request->input('keyword') : null;
        $category = !$request->has('keyword') && $request->has('category') ?  $request->input('category') : 'name';
        $order = !$request->has('keyword') && $request->has('order') ?  $request->input('order') : 'asc';
        session()->put('stand', ['category' => $category, 'order' => $order, 'keyword' => $keyword,]);
        return redirect()->route('food.stand', ['id' => $id]);
    }

    /**
     * Add new stand.
     */
    function insertStand(Request $request)
    {
        // Validating data
        $request->validate([
            'name' => ['required'],
            'pic_id' => ['required', 'numeric'],
            'place' => ['required', 'string'],
        ]);

        // Insert new stand
        $stand = Stand::create([
            'name' => $request->input('name'),
            'pic_id' => $request->input('pic_id'),
            'date' => $request->input('date'),
            'place' => $request->input('place'),
        ]);

        // Insert new foods expense
        $expense = FoodsExpense::create([
            'category' => 'stand expense',
            'category_id' => Stand::where('name', '=', $request->input('name'))->first()->id,
            'price' => 0,
        ]);

        // Insert new foods income
        $income = FoodsIncome::create([
            'category' => 'stand income',
            'category_id' => Stand::where('name', '=', $request->input('name'))->first()->id,
            'price' => 0,
        ]);

        // sucees insert
        if ($stand && $expense && $income) {
            return redirect()->route('food.stand', ['id' => $stand->id])->with('notif', ['type' => 'info', 'message' => 'Success create new stand.']);
        } else {
            return redirect()->route('food.stand')->with('notif', ['type' => 'warning', 'message' => 'Failed to create new stand. Please try again or contact admin.']);
        }
    }

    /**
     * update Stand.
     */
    public function updateStand(Request $request, $id)
    {
        // Validating data
        $integer1 = $request->input('pic_id') !== null ? 'integer' : '';
        $string1 = $request->input('name') !== null ? 'string' : '';
        $string2 = $request->input('place') !== null ? 'string' : '';
        $request->validate([
            'pic_id' => [$integer1],
            'name' => [$string1],
            'place' => [$string2],
        ]);

        $stand = Stand::find($id);
        $stand->pic_id = $request->input('pic_id') ? $request->input('pic_id') : $stand->pic_id;
        $stand->name = $request->input('name') ? $request->input('name') : $stand->name;
        $stand->place = $request->input('place') ? $request->input('place') : $stand->place;
        $stand->date = $request->input('date') ? $request->input('date') : $stand->date;
        // sucees update
        if ($stand->save()) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success update ' . $stand->name . '.']);
        } else {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Failed to update ' . $stand->name . '. Please try again later, or contact admin.']);
        };
    }

    /**
     * delete stand.
     */
    public function deleteStand(Request $request, $id)
    {
        // Authorization check
        if (!Hash::check($request->input('password'), $request->user()->password)) {
            return back()->withErrors([
                'password' => 'Your password is wrong.'
            ])->with('notif', ['type' => 'danger', 'message' => 'Your password is wrong.']);
        }

        // Delete stand expense, income, and menu
        StandExpense::where('stand_id', $id)->delete();
        StandSales::where('stand_id', $id)->delete();
        MenuItem::where('stand_id', $id)->delete();
        FoodsExpense::where('category', 'stand expense')->where('category_id', $id)->delete();
        FoodsIncome::where('category', 'stand income')->where('category_id', $id)->delete();

        // delete stand
        $stand = Stand::find($id);
        $name = $stand->name;
        $stand->delete();

        // update data
        BlaterianFoodBalanceController::refreshBalance();

        return redirect()->route('food.stand')->with('notif', ['type' => 'warning', 'message' => $name . ' has been deleted.']);
    }

    // STAND EXPENSE

    /**
     * Filtering stand expense order.
     */
    function filterStandExpense(Request $request, $id)
    {
        $category = $request->input('category');
        $order = $request->input('order');
        session()->put('stand_expense', ['category' => $category, 'order' => $order]);
        return redirect()->route('food.stand', ['id' => $id, 'default_tab' => 1, 'default_collapse' => 1]);
    }

    /**
     * add new Stand Expense.
     */
    public function insertStandExpense(Request $request, $id)
    {
        $request->flash();
        // Validating data
        $request->validate([
            'name' => ['required', 'string'],
            'price' => ['required', 'integer'],
            'qty' => ['required', 'integer'],
            'unit' => ['required', 'string'],
            'reciept' => [Rule::requiredIf($request->input('same_receipt_check') != 'on'), File::types(['jpg', 'jpeg', 'png', 'heic'])->max(5 * 1024)],
            'receipt_same' => [Rule::requiredIf($request->input('same_receipt_check') == 'on'), 'integer'],
        ]);
        $last = Stand::orderBy('id', 'desc')->first();
        $last_id = $last->id;
        if ($request->input('same_receipt_check') != 'on') {
            $reciept = $request->file('reciept');
            $reciept_name =  'SE' . $id . $last_id + 1 . '_receipt.' . $reciept->extension();
            // store reciept file
            $reciept->storePubliclyAs('images/receipt/stand/expense', $reciept_name, 'public');
        } else {
            $reciept_name = StandExpense::find($request->input('receipt_same'))->reciept;
        }
        $total_price =  $request->input('qty') *  $request->input('price');
        $data = [
            'stand_id' => $id,
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'qty' => $request->input('qty'),
            'unit' => $request->input('unit'),
            'total_price' => $total_price,
            'reciept' => $reciept_name,
            'updated_at' => now(),
            'created_at' => now()
        ];
        // sucees insert
        $standExpense = new StandExpense();
        if ($standExpense->insert($data) > 0) {
            return redirect()->route('food.stand', ['id' => $id, 'default_tab' => 1, 'default_collapse' => 1])->with('notif', ['type' => 'info', 'message' => 'New stand expense item has been added.']);
        };
        return redirect()->route('food.stand', ['id' => $id, 'default_tab' => 1, 'default_collapse' => 1])->with('notif', ['type' => 'warning', 'message' => 'Can not add new stand expense item. Please try again later, or contact admin.']);
    }

    /**
     * delete StandExpenseItem.
     */
    public function deleteStandExpenseItem($id)
    {
        $expenseItem = StandExpense::find($id);
        $name = $expenseItem->name;
        $stand = $expenseItem->stand;
        if ($expenseItem->financial_id > 0) {
            return redirect()->route('food.stand', ['id' => $stand->id, 'default_tab' => 1, 'default_collapse' => 1])->with('notif', ['type' => 'warning', 'message' => 'You can not delete Expense Item after validated by Financial Officer']);
        }
        // update necessary data
        if (StandExpense::where('reciept', '=', $expenseItem->reciept)->get()->count() == 0) {
            // delete stand expense receipt
            Storage::disk('public')->delete('images/receipt/stand/expense/' . $expenseItem->reciept);
        }
        if ($expenseItem->delete()) {
            return redirect()->route('food.stand', ['id' => $stand->id, 'default_tab' => 1, 'default_collapse' => 1])->with('notif', ['type' => 'warning', 'message' => 'Success delete ' . $name . ' from Stand ' . $stand->name . ' Expense Item']);
        } else {
            return redirect()->route('food.stand', ['id' => $stand->id, 'default_tab' => 1, 'default_collapse' => 1])->with('notif', ['type' => 'warning', 'message' => 'Success delete ' . $name . ' from Stand ' . $stand->name . ' Expense Item']);
        }
    }

    /**
     * validate receipt StandExpense.
     */
    public function validateExpenseReceipt(Request $request)
    {
        $id = $request->input('receipt_id');
        $valid = $request->input('validate');
        $auth_user = Auth::user();
        $sale_validation = $valid > 0  ? $auth_user->id : null;
        $stand_expense = StandExpense::find($id);
        $stand = $stand_expense->stand;
        if ($stand->sale_validation !== 0) {
            return redirect()->route('food.stand', ['id' => $stand->id, 'default_tab' => 1, 'default_collapse' => 1])->with('notif', ['type' => 'warning', 'message' => 'Stand ' . $stand->name . ' sale has been validated. This stand is inactive, You can not change anyting.']);
        }
        $stand_expense->operational_id = $sale_validation;
        $this->updateStandExpense($stand->id, $valid > 0, $stand_expense->total_price);
        $validation = $valid > 0 ? 'validate' : 'unvalidate';
        if ($stand_expense->save()) {
            return redirect()->route('food.stand', ['id' => $stand->id, 'default_tab' => 1, 'default_collapse' => 1])->with('notif', ['type' => 'info', 'message' => 'Succes ' . $validation . ' from Stand ' . $stand->name . ' Expense List.']);
        } else {
            return redirect()->route('food.stand', ['id' => $stand->id, 'default_tab' => 1, 'default_collapse' => 1])->with('notif', ['type' => 'warning', 'message' => 'Failed to  ' . $validation . ' from Stand ' . $stand->name . ' Expense List.']);
        }
    }

    /**
     * update new stand total expense.
     * 
     * 
     *  @var $id is stand id, @var $add to determine add or minus 
     */
    public function updateStandExpense(int $id, bool $add, int $new_expense)
    {
        // retrieve stand and foods expense model
        $stand = Stand::find($id);
        $foodsExpense = FoodsExpense::where('category_id', '=', $id)->first();

        // determine add/minus expense
        $new_expense = $add ? $new_expense : $new_expense * (-1);

        // update current expense with new expense
        $updated_expense = $stand->expense + $new_expense;

        // set new expense value to model
        $foodsExpense->price = $updated_expense;
        $stand->expense = $updated_expense;
        $stand->profit -= $new_expense;

        // save model
        $stand->updated_at = now();
        $stand->save();
        $foodsExpense->updated_at = now();
        $foodsExpense->save();
        return BlaterianFoodBalanceController::refreshBalance();
    }


    // MENU

    /**
     * Filtering stand menu order.
     */
    function filterStandMenu(Request $request, $id)
    {
        $category = $request->input('category');
        $order = $request->input('order');
        session()->put('stand_menu', ['category' => $category, 'order' => $order]);
        return redirect()->route('food.stand', ['id' => $id, 'default_tab' => 2, 'default_collapse' => 1]);
    }

    /**
     * add new Stand Menu.
     */
    public function insertMenu(Request $request, $id)
    {
        $request->flash();
        $stand = Stand::find($id);
        if ($stand->menu_lock > 0) {
            return redirect()->route('food.stand', ['id' => $id, 'default_tab' => 2, 'default_collapse' => 1])->with('notif', ['type' => 'warning', 'message' => 'You can not add new menu to Stand ' . $stand->name . ' after stand menu locked by Operational Officer.']);
        }
        if ($stand->sale_validation > 0) {
            return redirect()->route('food.stand', ['id' => $id, 'default_tab' => 2, 'default_collapse' => 1])->with('notif', ['type' => 'warning', 'message' => 'You can not add new menu to Stand ' . $stand->name . ' after stand income validated by Operational Officer.']);
        }
        // Validating data
        $integer1 = $request->input('menu_volume_unit') !== null ? 'integer' : '';
        $integer2 = $request->input('menu_mass_unit') !== null ? 'integer' : '';
        $string1 = $request->input('menu_volume') !== null ? 'string' : '';
        $string2 = $request->input('menu_mass') !== null ? 'string' : '';
        $request->validate([
            'name' => ['required', 'string'],
            'price' => ['required', 'integer'],
            'stock' => ['required', 'integer'],
            'volume' => [Rule::requiredIf($request->input('volume_unit') !== null), $integer1],
            'volume_unit' => [Rule::requiredIf($request->input('volume') !== null), $string1],
            'mass' => [Rule::requiredIf($request->input('mass_unit') !== null), $integer2],
            'mass_unit' => [Rule::requiredIf($request->input('mass') !== null), $string2],
        ]);
        $data = [
            'stand_id' => $id,
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'volume' => $request->input('volume'),
            'volume_unit' => $request->input('volume_unit'),
            'mass' => $request->input('mass'),
            'mass_unit' => $request->input('mass_unit'),
            'stock' => $request->input('stock'),
            'updated_at' => now(),
            'created_at' => now()
        ];
        // sucees insert
        $menuItem = new MenuItem();
        if ($menuItem->insert($data) > 0) {
            return redirect()->route('food.stand', ['id' => $id, 'default_tab' => 2, 'default_collapse' => 1])->with('notif', ['type' => 'info', 'message' => 'New menu item has been added.']);
        };
        return redirect()->route('food.stand', ['id' => $id, 'default_tab' => 2, 'default_collapse' => 1])->with('notif', ['type' => 'warning', 'message' => 'Can not add new menu item. Please try again later, or contact admin.']);
    }

    /**
     * lock Menu Item.
     */
    public function lockMenu($id, $valid)
    {
        $auth_user = Auth::user();
        $menu_lock = $valid > 0  ? $auth_user->id : 0;
        $stand = Stand::find($id);
        if ($stand->sale_validation !== 0) {
            return redirect()->route('food.stand', ['id' => $id, 'default_tab' => 2, 'default_collapse' => 1])->with('notif', ['type' => 'warning', 'message' => 'Stand ' . $stand->name . ' sale has been validated. This stand is inactive, You can not change anyting.']);
        }
        $stand->menu_lock = $menu_lock;
        if ($stand->save()) {
            return redirect()->route('food.stand', ['id' => $id, 'default_tab' => 2, 'default_collapse' => 1])->with('notif', ['type' => 'info', 'message' => 'Succes ' . ($valid > 0 ? 'lock ' : 'unlock ') . $stand->name .  ' Menu.']);
        } else {
            return redirect()->route('food.stand', ['id' => $id, 'default_tab' => 2, 'default_collapse' => 1])->with('notif', ['type' => 'warning', 'message' => 'Failed to ' . ($valid > 0 ? 'lock ' : 'unlock ') . $stand->name .  ' Menu. Please try again or contact admin.']);
        }
    }

    /**
     * delete MenuItem.
     */
    public function deleteMenu($id)
    {
        $menu_item = MenuItem::find($id);
        $name = $menu_item->name;
        $stand = $menu_item->stand;
        if ($stand->sale_validation > 0) {
            return redirect()->route('food.stand', ['id' => $id, 'default_tab' => 2, 'default_collapse' => 1])->with('notif', ['type' => 'warning', 'message' => 'You can not delete ' . $name . ' from Stand ' . $stand->name . ' Menu after stand income validated by Operational Officer.']);
        }
        if ($stand->menu_lock > 0) {
            return redirect()->route('food.stand', ['id' => $id, 'default_tab' => 2, 'default_collapse' => 1])->with('notif', ['type' => 'warning', 'message' => 'You can not add new menu to Stand ' . $stand->name . ' after stand menu locked by Operational Officer.']);
        }
        if ($menu_item->sale > 0) {
            return redirect()->route('food.stand', ['id' => $id, 'default_tab' => 2, 'default_collapse' => 1])->with('notif', ['type' => 'warning', 'message' => 'You can not delete ' . $name . ' from Stand ' . $stand->name . ' Menu. This menu have sales.']);
        }
        if ($menu_item->delete()) {
            return redirect()->route('food.stand', ['id' => $id, 'default_tab' => 2, 'default_collapse' => 1])->with('notif', ['type' => 'info', 'message' => 'Succes delete ' . $name . ' from Stand ' . $stand->name . ' Menu.']);
        } else {
            return redirect()->route('food.stand', ['id' => $id, 'default_tab' => 2, 'default_collapse' => 1])->with('notif', ['type' => 'warning', 'message' => 'Failed to delete ' . $name . ' from Stand ' . $stand->name . ' Menu. Please try again or contact admin.']);
        }
    }

    /**
     * refresh Profit.
     */
    public function refreshProfit($stand_id)
    {
        $stand = Stand::find($stand_id);
        $expense = $stand->expense()->where('operational_id', '!=', 0)->sum('total_price');
        $income = $stand->sale()->sum('transaction');
        $stand->profit = $income - $expense;
        $stand->save();
        return back()->with('notif', ['type' => 'info', 'message' => $stand->name . ' Balance is updated.']);
    }

    /**
     * update stock menu.
     */
    function updateStock(Request $request)
    {
        $id = $request->input('id');
        $stock = $request->input('stock');

        $menu = MenuItem::find($id);
        $menu->stock += $stock;
        $stand = $menu->stand;

        if ($menu->save() > 0) {
            return redirect()->route('food.stand', ['id' => $stand->id, 'default_tab' => 2, 'default_collapse' => 1])->with('notif', ['type' => 'info', 'message' => 'Success update stock ' . $menu->name . ' from Stand ' . $stand->name . ' Menu.']);
        } else {
            return redirect()->route('food.stand', ['id' => $stand->id, 'default_tab' => 2, 'default_collapse' => 1])->with('notif', ['type' => 'info', 'message' => 'Failed to update stock ' . $menu->name . ' from Stand ' . $stand->name . ' Menu. Please try again or contact admin.']);
        }
    }
}
