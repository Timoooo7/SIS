<?php

namespace App\Http\Controllers;

use App\Models\BudgetItem;
use App\Models\Department;
use App\Models\DisbursementItem;
use App\Models\ExpenseItem;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProgramController extends Controller
{
    /**
     * Display the program details.
     */
    public function program(int $id)
    {
        $program = Program::find($id);
        // program not exist
        if ($program == null) {
            return redirect()->route('department');
        }

        $this->refreshBudgetAndExpense($id);
        // program exist
        $budget_items = BudgetItem::where('program_id', $id)->paginate(5, '*', 'budget_page');
        $expense_items = ExpenseItem::where('program_id', $id);
        $disbursement_items = DisbursementItem::where('program_id', $id)->paginate(5, '*', 'disbursement_page');
        $data = [
            'sidebar' => 'seeo',
            'users' => User::all(),
            'program' => $program,
            'budget_items' => $budget_items,
            'expense_items' => $expense_items->paginate(5, '*', 'expense_page'),
            'inverted_expense_items' => $expense_items->orderBy('id', 'desc')->get(),
            'disbursement_items' => $disbursement_items,
        ];
        return view('pages.program', $data);
    }

    /**
     * add new Program.
     */
    public function insertProgram(Request $request, $id)
    {
        // Validating data
        $request->validate([
            'name' => ['required', 'unique:' . Program::class],
        ]);

        // check manager
        if (Auth::user()->id != $id) {
            return back()->with('notif', ['type' => 'info', 'message' => 'You are not allowed. Please contact the Manager.']);
        };

        $data = [
            'name' => $request->input('name'),
            'pic_id' => $request->input('pic_id'),
            'department_id' => $request->input('department_id'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $program = new Program();
        // sucees insert
        if ($program->insert($data) > 0) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Program ' . $request->input('name') . ' has been added to Department of ' . Department::find($request->input('department_id'))->name . '.']);
        };
        return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Can not add program ' . $request->input('name') . '. Please try again later, or contact admin.']);
    }

    /**
     * update Program.
     */
    public function updateProgram(Request $request, $id)
    {
        // Validating data
        $request->validate([
            'name' => ['required', 'unique:' . Program::class],
            'pic_id' => ['required', 'numeric'],
        ]);

        $current_name = Program::find($id)->name;
        $new_name = $request->input('name');

        $data = ['name' => $new_name, 'pic_id' => $request->input('pic_id')];
        $program = new Program();
        // sucees update
        if ($program->change($id, $data) > 0) {
            if ($current_name != $new_name) {
                return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Program ' . $current_name . ' has been changed to ' . $new_name . '.']);
            } else {
                return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Program ' . $current_name . ' has been updated.']);
            }
        };
        return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Can not change department name at the moment. Please try again later, or contact admin.']);
    }

    /**
     * delete Department.
     */
    public function deleteProgram(Request $request, $id)
    {
        // Authorization check
        if (!Hash::check($request->input('password'), $request->user()->password)) {
            return back()->withErrors([
                'password' => 'Your password is wrong.'
            ])->with('notif', ['type' => 'danger', 'message' => 'Your password is wrong.']);
        }

        BudgetItem::where('program_id', $id)->delete();
        ExpenseItem::where('program_id', $id)->delete();
        DisbursementItem::where('program_id', $id)->delete();
        // delete department
        $program = Program::find($id);
        $name = $program->name;
        $program->delete();

        return redirect()->route('department')->with('notif', ['type' => 'danger', 'message' => 'Program ' . $name . ' has been deleted.']);
    }

    /**
     * change validation of selected program budget.
     */
    public function validateBudget(Request $request, $id)
    {
        $financial_id = $request->input('financial_id');
        $data = ['financial_id' => $financial_id, 'updated_at' => now()];
        $program = new Program();
        $program->change($id, $data);
        if ($financial_id != 0) {
            return back()->with('notif', ['type' => 'success', 'message' => 'VALIDATE ' . Program::find($id)->name . ' Budget Program by ' . Auth::user()->name . '.']);
        } else {
            return back()->with('notif', ['type' => 'warning', 'message' => 'UNVALIDATE ' . Program::find($id)->name . ' Budget Program by ' . Auth::user()->name . '.']);
        }
    }


    /**
     * refresh budget and expense for make sure program budget is accurate.
     * 
     * 
     *  @var $id is program id, @var $add to determine add or minus 
     */
    public function refreshBudgetAndExpense(int $id)
    {
        // dd($id);
        $program = Program::find($id);
        $budget_price = 0;
        $expense_price = 0;
        $disbursement_price = 0;
        $budgets = BudgetItem::where('program_id', $id)->get();
        foreach ($budgets as $budget) {
            $budget_price += $budget->total_price;
        }
        $expenses = ExpenseItem::where('program_id', $id)->get();
        foreach ($expenses as $expense) {
            $expense_price += $expense->total_price;
        }
        $disbursements = DisbursementItem::where('program_id', $id)->get();
        foreach ($disbursements as $disbursement) {
            $disbursement_price += $disbursement->price;
        }
        $data = ['budget' => $budget_price, 'expense' => $expense_price, 'disbursement' => $disbursement_price];

        // also refresh department budget
        $department = new DepartmentController();
        $department->refreshBudgetAndExpense($program->department_id);

        return $program->change($id, $data);
    }
}
