<?php

namespace App\Http\Controllers;

use App\Models\BudgetItem;
use App\Models\Program;
use App\Models\User;
use function PHPUnit\Framework\isNull;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetItemController extends Controller
{
    /**
     * add new Department.
     */
    public function insertBudgetItem(Request $request, $id)
    {
        $request->flash();
        // Validating data
        $request->validate([
            'name' => ['required', 'string'],
            'price' => ['required', 'integer'],
            'qty' => ['required', 'integer'],
            'unit' => ['required', 'string'],
        ]);
        $budget =  $request->input('qty') *  $request->input('price');
        $data = [
            'program_id' => $id,
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'qty' => $request->input('qty'),
            'unit' => $request->input('unit'),
            'total_price' => $budget,
            'updated_at' => now(),
            'created_at' => now()
        ];
        // update necessary data
        $this->updateProgramBudget($id, true, $budget);
        // sucees insert
        $budgetItem = new BudgetItem();
        if ($budgetItem->insert($data) > 0) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'New budget item has been added.']);
        };
        return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Can not add new budget item. Please try again later, or contact admin.']);
    }

    /**
     * update BudgetItem name.
     */
    public function updateBudgetItem(Request $request, $pic_id)
    {
        // Authorization check
        if (Auth::user()->id != $pic_id) {
            return back()->with('notif', ['type' => 'danger', 'message' => 'You are not authorized. Please contact the person in charge of this program.']);
        }

        // check id
        if ($request->input('id') == 'null') {
            return back()->with('notif', ['type' => 'warning', 'message' => 'Please select an item.']);
        }

        // check validation
        $bi_id = $request->input('id');
        $budgetItem = BudgetItem::find($bi_id);
        if ($budgetItem->program->financial_id != null) {
            return redirect()->route('program', ['id' => $budgetItem->program->id])->with('notif', ['type' => 'warning', 'message' => 'This item is already approved by ' . User::find($budgetItem->program->financial_id)->name . '. You can not edit or delete approved item.']);
        }

        // check delete
        if ($request->input('action') == 'delete') {
            return $this->deleteBudgetItem($request->input('id'), $pic_id);
        }
        // check if everything empty
        if (!$request->input()) {
            return back()->with('notif', ['type' => 'warning', 'message' => 'Give some inputs to update.']);
        }
        // Validating data
        $request->validate([
            'price_budget' => ['nullable', 'numeric'],
            'quantity_budget' => ['nullable', 'numeric'],
            'unit_budget' => ['nullable', 'string'],
        ]);

        $price = $request->input('price_budget') != null ? $request->input('price_budget') : $budgetItem->price;
        $quantity = $request->input('quantity_budget') != null ? $request->input('quantity_budget') : $budgetItem->qty;
        $unit = $request->input('unit_budget') != null ? $request->input('unit_budget') : $budgetItem->unit;
        $new_price =  $quantity * $price;
        $data = [
            'price' => $price,
            'qty' => $quantity,
            'unit' => $unit,
            'total_price' => $new_price,
            'updated_at' => now(),
        ];
        // update necessary data
        $current_price = $budgetItem->price * $budgetItem->qty;
        $this->updateProgramBudget($budgetItem->program_id, true, $new_price - $current_price);
        // sucees insert
        if ($budgetItem->change($bi_id, $data) > 0) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Budget item ' . BudgetItem::find($bi_id)->name . ' is updated.']);
        };
        return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Can not update budget item at the moment. Please try again later, or contact admin.']);
    }

    /**
     * delete BudgetItem.
     */
    public function deleteBudgetItem($bi_id, $pic_id)
    {
        // Authorization check
        if (Auth::user()->roles->id != $pic_id) {
            return back()->with('notif', ['type' => 'danger', 'message' => 'You are not authorized. Please contact the person in charge of this program.']);
        }

        $budgetItem = BudgetItem::find($bi_id);
        $name = $budgetItem->name;
        // delete budget item
        $budgetItem->delete();

        return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Budget item ' . $name . ' has been deleted.']);
    }

    /**
     * update new program total expense.
     * 
     * 
     *  @var $id is program id, @var $add to determine add or minus 
     */
    public function updateProgramBudget(int $id, bool $add, int $new_budget)
    {
        $program = Program::find($id);
        $current_budget = $program->budget;
        if ($add) {
            $data = ['budget' => $current_budget + $new_budget, 'updated_at' => now()];
        } else {
            $data = ['budget' => $current_budget - $new_budget, 'updated_at' => now()];
        }
        return $program->change($id, $data);
    }

    /**
     * validate BudgetItem by Financial.
     */

    public function approval(Request $request, $id)
    {
        $program = Program::find($id);
        $validate = $request->input('action') == 'validate' ? Auth::user()->id : null;
        // validate program budget
        $data = ['financial_id' => $validate];
        $program->change($id, $data);
        return back()->with('notif', ['type' => 'info', 'message' => $program->name . ' Program Budget is ' . $validate . 'd.']);
    }
}
