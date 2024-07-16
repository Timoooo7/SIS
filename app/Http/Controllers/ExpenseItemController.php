<?php

namespace App\Http\Controllers;

use App\Models\ExpenseItem;
use App\Models\Program;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class ExpenseItemController extends Controller
{
    /**
     * add new ExpenseItem.
     */
    public function insertExpenseItem(Request $request, $id)
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
        $program = Program::find($id);
        if ($request->input('same_receipt_check') != 'on') {
            $reciept = $request->file('expense_reciept');
            $reciept_name =  'ei_' . str()->snake($program->name) . '_' . time() . '.' . $reciept->extension();
            // store reciept file
            $reciept->storePubliclyAs('images/receipt/expense', $reciept_name, 'public');
        } else {
            $reciept_name = ExpenseItem::find($request->input('expense_receipt_same'))->reciept;
        }
        $total_price =  $request->input('expense_qty') *  $request->input('expense_price');
        $data = [
            'program_id' => $id,
            'name' => $request->input('expense_name'),
            'price' => $request->input('expense_price'),
            'qty' => $request->input('expense_qty'),
            'unit' => $request->input('expense_unit'),
            'total_price' => $total_price,
            'reciept' => $reciept_name,
            'updated_at' => now(),
            'created_at' => now()
        ];
        // update necessary data
        $this->updateProgramExpense($id, true, $total_price);
        // sucees insert
        $expenseItem = new ExpenseItem();
        if ($expenseItem->insert($data) > 0) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'New expense item has been added.']);
        };
        return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Can not add new expense item. Please try again later, or contact admin.']);
    }

    /**
     * update ExpenseItem name.
     */
    public function updateExpenseItem(Request $request, $pic_id)
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
        $ei_id = $request->input('id');
        $expenseItem = ExpenseItem::find($ei_id);
        if ($expenseItem->financial_id != null) {
            return redirect()->route('program', ['id' => $expenseItem->program_id])->with('notif', ['type' => 'warning', 'message' => 'This item is already approved by ' . User::find($expenseItem->financial_id)->name . '. You can not edit or delete approved item.']);
        }

        // check delete
        if ($request->input('action') == 'delete') {
            return $this->deleteExpenseItem($request->input('id'));
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
                Storage::disk('public')->delete('images/receipt/expense/' . $expenseItem->reciept);
                $reciept_name = 'ei_' . str()->snake($expenseItem->program->name) . '_' . time() . '.' . $reciept->extension();
                $reciept->storePubliclyAs('images/receipt/expense', $reciept_name, 'public');
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
            $current_price = $expenseItem->price * $expenseItem->qty;
            // update necessary data
            $this->updateProgramExpense($expenseItem->program_id, true, $total_price - $current_price);
            $expenseItem = new ExpenseItem();
            // sucees update
            if ($expenseItem->change($ei_id, $data) > 0) {
                return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Expense item ' . ExpenseItem::find($ei_id)->name . ' is updated.']);
            };
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Can not update expense item at the moment. Please try again later, or contact admin.']);
        }
    }

    /**
     * delete ExpenseItem.
     */
    public function deleteExpenseItem($bi_id)
    {
        $expenseItem = ExpenseItem::find($bi_id);
        $name = $expenseItem->name;
        // update necessary data
        $this->updateProgramExpense($expenseItem->program_id, false, $expenseItem->total_price);
        // delete budget item
        Storage::disk('public')->delete('images/receipt/expense/' . $expenseItem->reciept);
        $expenseItem->delete();

        return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Expense item ' . $name . ' has been deleted.']);
    }

    /**
     * validate receipt ExpenseItem.
     */
    public function validateReceipt(Request $request)
    {
        $id = $request->input('id');
        $financial_id = $request->input('financial_id');
        $data = ['financial_id' => $financial_id, 'updated_at' => now()];
        $expenseItem = new ExpenseItem();
        $expenseItem->change($id, $data);
        if ($financial_id != null) {
            return back()->with('notif', ['type' => 'success', 'message' => 'Success VALIDATE ' . ExpenseItem::find($id)->name . ' receipt by ' . Auth::user()->name . '.']);
        } else {
            return back()->with('notif', ['type' => 'warning', 'message' => 'Succes UNVALIDATE ' . ExpenseItem::find($id)->name . ' receipt by ' . Auth::user()->name . '.']);
        }
    }


    /**
     * update new program total expense.
     * 
     * 
     *  @var $id is program id, @var $add to determine add or minus 
     */
    public function updateProgramExpense(int $id, bool $add, int $new_expense)
    {
        $program = Program::find($id);
        $current_expense = $program->expense;
        if ($add) {
            $data = ['expense' => $current_expense + $new_expense, 'updated_at' => now()];
        } else {
            $data = ['expense' => $current_expense - $new_expense, 'updated_at' => now()];
        }
        return $program->change($id, $data);
    }
}
