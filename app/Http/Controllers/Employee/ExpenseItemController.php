<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\ExpenseItem;
use App\Models\Program;
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
            'name' => ['required', 'string'],
            'price' => ['required', 'integer'],
            'qty' => ['required', 'integer'],
            'unit' => ['required', 'string'],
            'reciept' => [Rule::requiredIf($request->input('same_receipt_check') != 'on'), File::types(['jpg', 'jpeg', 'png', 'heic'])->max(5 * 1024)],
            'receipt_same' => [Rule::requiredIf($request->input('same_receipt_check') == 'on'), 'integer'],
        ]);
        $program = Program::find($id);
        if ($request->input('same_receipt_check') != 'on') {
            $reciept = $request->file('reciept');
            $reciept_name =  'ei_' . str()->snake($program->name) . '_' . time() . '.' . $reciept->extension();
            // store reciept file
            $reciept->storePubliclyAs('images/receipt/expense', $reciept_name, 'public');
        } else {
            $reciept_name = ExpenseItem::find($request->input('receipt_same'))->reciept;
        }
        $total_price =  $request->input('qty') *  $request->input('price');
        $data = [
            'program_id' => $id,
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
        $expenseItem = new ExpenseItem();
        if ($expenseItem->insert($data) > 0) {
            return redirect()->route('program', ['id' => $id, 'default_tab' => 3, 'default_collapse' => 1])->with('notif', ['type' => 'info', 'message' => 'New expense item has been added.']);
        };
        return redirect()->route('program', ['id' => $id, 'default_tab' => 3, 'default_collapse' => 1])->with('notif', ['type' => 'warning', 'message' => 'Can not add new expense item. Please try again later, or contact admin.']);
    }

    /**
     * delete ExpenseItem.
     */
    public function deleteExpenseItem($id)
    {
        $expenseItem = ExpenseItem::find($id);
        $expense = $expenseItem;
        $program = $expenseItem->program;
        // update necessary data
        $this->updateProgramExpense($expenseItem->program_id, false, $expenseItem->total_price);
        // delete budget item
        $success = $expenseItem->delete();
        $has_same_receipt = ExpenseItem::where('reciept', '=', $expense->reciept);
        if ($has_same_receipt->count() == 0) {
            Storage::disk('public')->delete('images/receipt/expense/' . $expense->reciept);
        }
        if ($success) {
            return redirect()->route('program', ['id' => $expenseItem->program_id, 'default_tab' => 3, 'default_collapse' => 1])->with('notif', ['type' => 'info', 'message' => 'Success delete ' . $expense->name . ' from ' . $program->name . ' Program Expense']);
        } else {
            return redirect()->route('program', ['id' => $expenseItem->program_id, 'default_tab' => 3, 'default_collapse' => 1])->with('notif', ['type' => 'warning', 'message' => 'Failed to delete ' . $expense->name . ' from ' . $program->name . ' Program Expense. Please try again or contact admin.']);
        }
    }

    /**
     * validate receipt ExpenseItem.
     */
    public function validateReceipt(Request $request)
    {
        $id = $request->input('receipt_id');
        $expenseItem = ExpenseItem::find($id);
        $expenseItem->financial_id = $request->input('validate') == 'validate' ? Auth::user()->id : null;
        $valid = $request->input('validate') == 'validate' ? 'validate' : 'unvalidate';
        // update necessary data
        $this->updateProgramExpense($expenseItem->program_id, $request->input('validate') == 'validate', $expenseItem->total_price);
        if ($expenseItem->save()) {
            return redirect()->route('program', ['id' => $expenseItem->program_id, 'default_tab' => 3, 'default_collapse' => 1])->with('notif', ['type' => 'success', 'message' => 'Success ' . $valid . ' ' . $expenseItem->name . '.']);
        } else {
            return redirect()->route('program', ['id' => $expenseItem->program_id, 'default_tab' => 3, 'default_collapse' => 1])->with('notif', ['type' => 'warning', 'message' => 'Failed to ' . $valid . ' ' . $expenseItem->name . '. Please try again or contact admin.']);
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
            $program->expense = $current_expense + $new_expense;
        } else {
            $program->expense = $current_expense - $new_expense;
        }
        $department = Department::find($program->department_id);
        $current_expense = $department->expense;
        if ($add) {
            $department->expense = $current_expense + $new_expense;
        } else {
            $department->expense = $current_expense - $new_expense;
        }
        return $program->save() && $department->save();
    }
}
