<?php

namespace App\Http\Controllers;

use App\Models\BlaterianBalance;
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
    public function stand(Request $request, $array_id = 0): View
    {
        $category = session('category', 'date');
        $order = session('order', 'desc');
        // Save to session
        $request->session()->put('category', $category);
        $request->session()->put('order', $order);
        // Stand filter
        $stand = Stand::orderBy($category, $order)->get();
        if (count($stand) > 0) {
            $active_stand = $stand[$array_id];
            $stand_expenses = StandExpense::where('stand_id', '=', $active_stand->id);
            $stand_control = [
                'prev_url' => route('food.stand', ['array_id' => $array_id - 1]),
                'next_url' => route('food.stand', ['array_id' => $array_id + 1]),
                'on_first_page' => $array_id == 0 ? 1 : 0,
                'has_more_page' => $array_id < count($stand) - 1 ? 1 : 0,
            ];
            $menu_items = MenuItem::where('stand_id', $active_stand->id)->paginate(10, '*', 'menu_page');
            $sales = StandSales::where('stand_id', $active_stand->id)->orderBy('id', 'desc')->paginate(10, '*', 'sale_page');
            $data = [
                'balance' => BlaterianBalance::orderBy('updated_at', 'desc')->first(),
                'menu_items' => $menu_items,
                'sales' => $sales,
                'stand_control' => $stand_control,
                'stand' => $active_stand,
                'expense_items' => $stand_expenses->paginate(10, '*', 'stand_expense_page'),
                'inverted_expense_items' => $stand_expenses->orderBy('id', 'desc')->get(),
            ];
            // $this->refreshProfit($active_stand->id);
        } else {
            $data = [
                'stand' => '',
            ];
        }
        $data += [
            'users' => User::where('roles_id', '!=', null)->get(),
        ];
        return view('pages.food.stand', $data);
    }

    /**
     * find foods stand.
     */
    public function findStand(Request $request)
    {
        session(['category' => $request->input('category'), 'order' => $request->input('order')]);
        return back();
    }

    /**
     * Add new stand.
     */
    public function insertStand(Request $request)
    {
        // Validating data
        $request->validate([
            'name' => ['required'],
            'pic_id' => ['required', 'numeric'],
        ]);

        // Insert new stand
        Stand::create([
            'name' => $request->input('name'),
            'pic_id' => $request->input('pic_id'),
            'date' => $request->input('date'),
            'time' => $request->input('time'),
            'place' => $request->input('place'),
        ]);

        // Insert new foods expense
        FoodsExpense::create([
            'category' => 'stand expense',
            'category_id' => Stand::where('name', '=', $request->input('name'))->first()->id,
            'price' => 0,
        ]);

        // Insert new foods income
        FoodsIncome::create([
            'category' => 'stand income',
            'category_id' => Stand::where('name', '=', $request->input('name'))->first()->id,
            'price' => 0,
        ]);

        // sucees insert
        return redirect()->back()->with('notif', ['type' => 'info', 'message' => $request->input('name') . ' has been added.']);
    }

    /**
     * update Stand.
     */
    public function updateStand(Request $request, $stand_id)
    {
        // Validating data
        $integer1 = $request->input('pic_id') !== null ? 'integer' : '';
        $string1 = $request->input('name') !== null ? 'string' : '';
        $request->validate([
            'pic_id' => [$integer1],
            'name' => [$string1],
        ]);

        // Set data
        $data = [];
        if ($request->input('pic_id') !== null) {
            $data = ['pic_id' => $request->input('pic_id')];
        }
        if ($request->input('name') !== null) {
            $data = ['name' => $request->input('name')];
        }
        $stand = new Stand();
        // sucees update
        if ($stand->change($stand_id, $data) > 0) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Stand ' . $stand->name . ' is updated.']);
        } else {
            return back()->with('notif', ['type' => 'warning', 'message' => 'Give some inputs to update.']);
        };
        return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Can not update menu item at the moment. Please try again later, or contact admin.']);
    }

    /**
     * delete stand.
     */
    public function deleteStand(Request $request, $stand_id)
    {
        // Authorization check
        if (!Hash::check($request->input('password'), $request->user()->password)) {
            return back()->withErrors([
                'password' => 'Your password is wrong.'
            ])->with('notif', ['type' => 'danger', 'message' => 'Your password is wrong.']);
        }

        // Delete stand expense, income, and menu
        StandExpense::where('stand_id', $stand_id)->delete();
        StandSales::where('stand_id', $stand_id)->delete();
        MenuItem::where('stand_id', $stand_id)->delete();

        // delete stand
        $stand = Stand::find($stand_id);
        $name = $stand->name;
        $stand->delete();

        return redirect()->back()->with('notif', ['type' => 'warning', 'message' => $name . ' has been deleted.']);
    }

    // STAND EXPENSE

    /**
     * add new Stand Expense.
     */
    public function insertStandExpense(Request $request, $stand_expense_id)
    {
        $request->flash();
        // Validating data
        $request->validate([
            'expense_name' => ['required', 'string'],
            'expense_price' => ['required', 'integer'],
            'expense_qty' => ['required', 'integer'],
            'expense_unit' => ['required', 'string'],
            'expense_reciept' => [Rule::requiredIf($request->input('same_receipt_check') != 'on'), File::types(['jpg', 'jpeg', 'png', 'heic'])->max(5 * 1024)],
            'expense_receipt_same' => [Rule::requiredIf($request->input('same_receipt_check') == 'on'), 'integer'],
        ]);
        $last = Stand::orderBy('id', 'desc')->first()->id;
        if ($request->input('same_receipt_check') != 'on') {
            $reciept = $request->file('expense_reciept');
            $reciept_name =  'SE' . $stand_expense_id . $last + 1 . '_receipt.' . $reciept->extension();
            // store reciept file
            $reciept->storePubliclyAs('images/receipt/stand/expense', $reciept_name, 'public');
        } else {
            $reciept_name = StandExpense::find($request->input('expense_receipt_same'))->reciept;
        }
        $total_price =  $request->input('expense_qty') *  $request->input('expense_price');
        $data = [
            'stand_id' => $stand_expense_id,
            'name' => $request->input('expense_name'),
            'price' => $request->input('expense_price'),
            'qty' => $request->input('expense_qty'),
            'unit' => $request->input('expense_unit'),
            'total_price' => $total_price,
            'reciept' => $reciept_name,
            'updated_at' => now(),
            'created_at' => now()
        ];
        // sucees insert
        $standExpense = new StandExpense();
        if ($standExpense->insert($data) > 0) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'New stand expense item has been added.']);
        };
        return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Can not add new stand expense item. Please try again later, or contact admin.']);
    }

    /**
     * update StandExpenseItem name.
     */
    public function updateStandExpenseItem(Request $request, $pic_id)
    {
        // Authorization check
        if (Auth::user()->id != $pic_id) {
            return back()->with('notif', ['type' => 'danger', 'message' => 'You are not authorized. Please contact the person in charge of this stand.']);
        }

        // check id
        if ($request->input('id') == 'null') {
            return back()->with('notif', ['type' => 'warning', 'message' => 'Please select an item.']);
        }

        // check validation
        $sei_id = $request->input('id');
        $expenseItem = StandExpense::find($sei_id);
        if ($expenseItem->operational_id != null) {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'This item is already approved by ' . User::find($expenseItem->operational_id)->name . '. You can not edit or delete approved item.']);
        }

        // check delete
        if ($request->input('action') == 'delete') {
            return $this->deleteStandExpenseItem($request->input('id'));
        } else {
            // check if everything empty
            if (!$request->input()) {
                return back()->with('notif', ['type' => 'warning', 'message' => 'Give some inputs to update.']);
            }
            // Validating data
            $request->validate([
                'price_expense' => ['nullable', 'numeric'],
                'quantity_expense' => ['nullable', 'numeric'],
                'unit_expense' => ['nullable', 'string'],
                'reciept_expense' => ['nullable', File::types(['jpg', 'jpeg', 'png', 'heic'])->max(5 * 1024)],
            ]);


            // set default value if input empty
            $price = $request->input('price_expense') != null ? $request->input('price_expense') : $expenseItem->price;
            $quantity = $request->input('quantity_expense') != null ? $request->input('quantity_expense') : $expenseItem->qty;
            $unit = $request->input('unit_expense') != null ? $request->input('unit_expense') : $expenseItem->unit;
            $total_price =  $quantity *  $price;
            // store reciept file
            if ($request->hasFile('reciept_expense')) {
                $reciept = $request->file('reciept_expense');
                Storage::disk('public')->delete('images/receipt/stand/expense/' . $expenseItem->reciept);
                $reciept_name = 'SE' . $expenseItem->stand_id . $request->input('id') . '_receipt.'  . $reciept->extension();
                $reciept->storePubliclyAs('images/receipt/stand/expense', $reciept_name, 'public');
            } else {
                $reciept_name = $expenseItem->reciept;
            }
            // set data
            $data = [
                'price' => $price,
                'qty' => $quantity,
                'unit' => $unit,
                'reciept' => $reciept_name,
                'total_price' => $total_price,
                'updated_at' => now(),
            ];
            $expenseItem = new StandExpense();
            // sucees update
            if ($expenseItem->change($sei_id, $data) > 0) {
                return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Stand Expense Item ' . StandExpense::find($sei_id)->name . ' is updated.']);
            };
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Can not update expense item at the moment. Please try again later, or contact admin.']);
        }
    }

    /**
     * delete StandExpenseItem.
     */
    public function deleteStandExpenseItem($expense_id)
    {
        $expenseItem = StandExpense::find($expense_id);
        $name = $expenseItem->name;
        // update necessary data
        $this->updateStandExpense($expenseItem->stand_id, false, $expenseItem->total_price);
        if (count(Stand::find($expenseItem->stand_id)->expense()->where('reciept', $expenseItem->reciept)->get()) == 1) {
            dd();
            // delete budget item
            Storage::disk('public')->delete('images/receipt/stand/expense/' . $expenseItem->reciept);
        }
        $expenseItem->delete();
        return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Stand Expense Item ' . $name . ' has been deleted.']);
    }

    /**
     * validate receipt StandExpense.
     */
    public function validateExpenseReceipt(Request $request)
    {
        $id = $request->input('id');
        $standExpense = StandExpense::find($id);
        $stand = $standExpense->stand;
        if ($stand->sale_validation !== 0) {
            return back()->with('notif', ['type' => 'warning', 'message' => 'Stand ' . $stand->name . ' sale has been validated. You can not change any data, it may cause incorect report.']);
        }
        $standExpense->operational_id = $request->input('operational_id');
        $standExpense->save();
        if ($request->input('operational_id') != '') {
            // update necessary data
            $this->updateStandExpense($stand->id, true, $standExpense->total_price);
            return back()->with('notif', ['type' => 'success', 'message' => 'Success VALIDATE ' . StandExpense::find($id)->name . ' Stand Expense receipt by ' . Auth::user()->name . '.']);
        } else {
            // update necessary data
            $this->updateStandExpense($stand->id, false, $standExpense->total_price);
            return back()->with('notif', ['type' => 'warning', 'message' => 'Succes UNVALIDATE ' . StandExpense::find($id)->name . ' Stand Expense receipt by ' . Auth::user()->name . '.']);
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
        return BlaterianBalanceController::refreshBalance();
    }


    // SALES

    /**
     * add new Stand Menu.
     */
    public function insertMenu(Request $request, $stand_id)
    {
        $request->flash();
        // Validating data
        $integer1 = $request->input('menu_volume_unit') !== null ? 'integer' : '';
        $integer2 = $request->input('menu_mass_unit') !== null ? 'integer' : '';
        $string1 = $request->input('menu_volume') !== null ? 'string' : '';
        $string2 = $request->input('menu_mass') !== null ? 'string' : '';
        $request->validate([
            'menu_name' => ['required', 'string'],
            'menu_price' => ['required', 'integer'],
            'menu_volume' => [Rule::requiredIf($request->input('menu_volume_unit') !== null), $integer1],
            'menu_volume_unit' => [Rule::requiredIf($request->input('menu_volume') !== null), $string1],
            'menu_mass' => [Rule::requiredIf($request->input('menu_mass_unit') !== null), $integer2],
            'menu_mass_unit' => [Rule::requiredIf($request->input('menu_mass') !== null), $string2],
        ]);
        $data = [
            'stand_id' => $stand_id,
            'name' => $request->input('menu_name'),
            'price' => $request->input('menu_price'),
            'volume' => $request->input('menu_volume'),
            'volume_unit' => $request->input('menu_volume_unit'),
            'mass' => $request->input('menu_mass'),
            'mass_unit' => $request->input('menu_mass_unit'),
            'updated_at' => now(),
            'created_at' => now()
        ];
        // dd($data);
        // sucees insert
        $menuItem = new MenuItem();
        if ($menuItem->insert($data) > 0) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'New menu item has been added.']);
        };
        return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Can not add new menu item. Please try again later, or contact admin.']);
    }

    /**
     * update Menu Item.
     */
    public function updateMenu(Request $request)
    {
        // check id
        if ($request->input('menu_update_id') == 'null') {
            return back()->with('notif', ['type' => 'warning', 'message' => 'Please select an item.']);
        }

        // check lock
        $menu_id = $request->input('menu_update_id');
        $menu = MenuItem::find($menu_id);
        $stand_id = $menu->stand_id;
        $stand = Stand::find($stand_id);
        if ($stand->menu_lock == true) {
            return back()->with('notif', ['type' => 'warning', 'message' => 'This stand menu is locked. You can not edit or delete approved item.']);
        }

        // Authorization check
        if (Auth::user()->id != $stand->pic_id) {
            return back()->with('notif', ['type' => 'danger', 'message' => 'You are not authorized. Please contact the person in charge of this stand.']);
        }

        // check delete
        if ($request->input('action') == 'delete') {
            return $this->deleteMenu($request->input('menu_update_id'));
        } else {
            // check if everything empty
            if (!$request->input()) {
                return back()->with('notif', ['type' => 'warning', 'message' => 'Give some inputs to update.']);
            }
            // Validating data
            $integer1 = $request->input('menu_update_price') !== null ? 'integer' : '';
            $integer2 = $request->input('menu_update_volume') !== null ? 'integer' : '';
            $integer3 = $request->input('menu_update_mass') !== null ? 'integer' : '';
            $string1 = $request->input('menu_update_volume_unit') !== null ? 'string' : '';
            $string2 = $request->input('menu_update_string_unit') !== null ? 'string' : '';
            $request->validate([
                'menu_update_price' => [$integer1],
                'menu_update_volume' => [$integer2],
                'menu_update_volume_unit' => [$string1],
                'menu_update_mass' => [$integer3],
                'menu_update_mass_unit' => [$string2],
            ]);

            // set default value if input empty
            $price = $request->input('menu_update_price') != null ? $request->input('menu_update_price') : $menu->price;
            $volume = $request->input('menu_update_volume') != null ? $request->input('menu_update_volume') : $menu->volume;
            $volume_unit = $request->input('menu_update_volume_unit') != null ? $request->input('menu_update_volume_unit') : $menu->volume_unit;
            $mass = $request->input('menu_update_mass') != null ? $request->input('menu_update_mass') : $menu->mass;
            $mass_unit = $request->input('menu_update_mass_unit') != null ? $request->input('menu_update_mass_unit') : $menu->volume_unit;
            // set data
            $data = [
                'price' => $price,
                'volume' => $volume,
                'volume_unit' => $volume_unit,
                'mass' => $mass,
                'mass_unit' => $mass_unit,
                'updated_at' => now(),
            ];
            $menuItem = new MenuItem();
            // sucees update
            if ($menuItem->change($menu_id, $data) > 0) {
                return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Menu Item ' . $menu->name . ' is updated.']);
            };
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Can not update menu item at the moment. Please try again later, or contact admin.']);
        }
    }

    /**
     * lock Menu Item.
     */
    public function lockMenu(Request $request)
    {
        $id = $request->input('stand_id');
        $menu_lock = $request->input('operational_id');
        $data = ['menu_lock' => $menu_lock, 'updated_at' => now()];
        $stand = new Stand();
        $stand->change($id, $data);
        if ($menu_lock != 0) {
            return back()->with('notif', ['type' => 'warning', 'message' => 'Succes LOCK menu ' . Stand::find($id)->name . ' by ' . Auth::user()->name . '.']);
        } else {
            return back()->with('notif', ['type' => 'success', 'message' => 'Succes UNLOCK menu ' . Stand::find($id)->name . ' by ' . Auth::user()->name . '.']);
        }
    }

    /**
     * delete MenuItem.
     */
    public function deleteMenu($menu_id)
    {
        $menu_item = MenuItem::find($menu_id);
        $name = $menu_item->name;
        $menu_item->delete();

        return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Menu ' . $name . ' has been deleted.']);
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
}
