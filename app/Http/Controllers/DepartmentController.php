<?php

namespace App\Http\Controllers;

use App\Models\BudgetItem;
use App\Models\Department;
use App\Models\DisbursementItem;
use App\Models\ExpenseItem;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    /**
     * Display departments.
     */
    public function department(): View
    {
        $departments = Department::all();
        $users = User::all();

        // refreshing department budget before display to user
        foreach ($departments as $department) {
            $this->refreshBudgetAndExpense($department->id);
        }

        $data = ['departments' => $departments, 'users' => $users, 'sidebar' => 'seeo'];
        return view('pages.department', $data);
    }


    /**
     * add new Department.
     */
    public function insertDepartment(Request $request)
    {
        // Validating data
        $request->validate([
            'name' => ['required', 'unique:' . Department::class],
            'manager_id' => ['required', 'numeric'],
        ]);

        $data = ['name' => $request->input('name'), 'manager_id' => $request->input('manager_id'), 'updated_at' => now(), 'created_at' => now()];
        $department = new Department();
        // sucees insert
        if ($department->insert($data) > 0) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Department ' . $request->input('name') . ' has been added.']);
        };
        return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Can not add Department ' . $request->input('name') . '. Please try again later, or contact admin.']);
    }

    /**
     * update Department.
     */
    public function updateDepartment(Request $request, $id)
    {
        // Validating data
        $request->validate([
            'name' => ['required', 'unique:' . Department::class],
            'manager_id' => ['required', 'numeric'],
        ]);

        $current_name = Department::find($id)->name;
        $new_name = $request->input('name');

        $data = ['name' => $new_name, 'manager_id' => $request->input('manager_id')];
        $department = new Department();
        // sucees update
        if ($department->change($id, $data) > 0) {
            if ($current_name != $new_name) {
                return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Department ' . $current_name . ' has been changed to ' . $new_name . '.']);
            } else {
                return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Department ' . $current_name . ' has been updated.']);
            }
        };
        return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Can not change department name at the moment. Please try again later, or contact admin.']);
    }

    /**
     * delete Department.
     */
    public function deleteDepartment(Request $request, $id)
    {
        // Authorization check
        if (!Hash::check($request->input('password'), $request->user()->password)) {
            return back()->withErrors([
                'password' => 'Your password is wrong.'
            ])->with('notif', ['type' => 'danger', 'message' => 'Your password is wrong.']);
        }

        // delete budget, disbursement, expense in the program
        $programs = Program::where('department_id', $id);
        foreach ($programs as $program) {
            BudgetItem::where('program_id', $program->id)->delete();
            ExpenseItem::where('program_id', $program->id)->delete();
            DisbursementItem::where('program_id', $program->id)->delete();
        }

        // delete program in the department
        $programs->delete();

        // delete department
        $department = Department::find($id);
        $name = $department->name;
        $department->delete();

        return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Department ' . $name . ' has been deleted.']);
    }

    /**
     * refresh budget and expense for make sure program budget is accurate.
     * 
     * 
     *  @var $id is program id, @var $add to determine add or minus 
     */
    public function refreshBudgetAndExpense(int $id)
    {
        $department = Department::find($id);
        $budget = 0;
        $expense = 0;
        $disbursement = 0;
        $programs = Program::where('department_id', $id)->get();
        foreach ($programs as $program) {
            $budget += $program->budget;
            $expense += $program->expense;
            $disbursement += $program->disbursement;
        }
        $data = ['budget' => $budget, 'expense' => $expense, 'disbursement' => $disbursement];
        return $department->change($id, $data);
    }
}
