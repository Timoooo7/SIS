<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\BudgetItem;
use App\Models\Department;
use App\Models\DisbursementItem;
use App\Models\ExpenseItem;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    /**
     * Display departments.
     */
    public function department(Request $request, $array_id = 0, $department_name = ''): View
    {
        $departments = $department_name !== '' ?
            Department::orderByRaw("
                CASE
                    WHEN name = ? THEN 1
                    WHEN name LIKE ? THEN 2
                    WHEN name LIKE ? THEN 3
                    ELSE 4
                END 
            ", [$department_name, "$department_name%", "%$department_name%"])->with(['manager'])->get() :
            Department::orderBy('name', 'asc')->get();


        $users = User::where('roles_id', '!=', null)->orderBy('updated_at', 'asc')->get();
        if ($departments->count() > 0) {
            $active_department = $departments[$array_id];

            // refreshing department budget before display to user
            $this->refreshBudgetAndExpense($active_department->id);

            $data = [
                'active_department' => $active_department,
                'staff_list' => $users->where('department_id', '=', $active_department->id),
                'not_manager_list' => $users->where('department_id', '=', null),
            ];
        } else {
            $data = [
                'active_department' => null,
            ];
        }
        $data += [
            'departments' => $departments,
            'sidebar' => 'seeo',
        ];

        return view('pages.staff.department', $data);
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

        $department = Department::create(
            [
                'name' => $request->input('name'),
                'manager_id' => $request->input('manager_id'),
            ]
        );
        $manager = User::find($request->input('manager_id'));
        $manager->department_id = $department->id;
        // sucees insert
        if ($department && $manager->save()) {
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
            'name' => ['required'],
            'manager_id' => ['required', 'numeric'],
        ]);

        $department = Department::find($id);
        $current_name = $department->name;
        $new_name = $request->input('name');

        $department->name = $new_name;

        if ($department->manager_id !== $request->input('manager_id')) {
            $old_manager = User::find($department->manager_id);
            $old_manager->department_id = null;
            $old_manager->save();

            $new_manager = User::find($request->input('manager_id'));
            $new_manager->department_id = $id;
            $new_manager->save();
        }
        $department->manager_id = $request->input('manager_id');
        // sucees update
        if ($department->save()) {
            if ($current_name != $new_name) {
                return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Department ' . $current_name . ' has been changed to ' . $new_name . '.']);
            } else {
                return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Department ' . $current_name . ' has been updated.']);
            }
        };
        return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'No changes were made.']);
    }

    /**
     * delete Department.
     */
    public function deleteDepartment(Request $request, $id)
    {
        // Authorization check
        if (!Hash::check($request->input('password'), Auth::user()->password)) {
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
        $department->manager->department_id = null;
        $department->manager->save();

        $department->delete();

        return redirect()->route('department')->with('notif', ['type' => 'danger', 'message' => 'Department ' . $name . ' has been deleted.']);
    }

    /**
     * refresh budget and expense for make sure program budget is accurate.
     * 
     * 
     *  @var $id is program id, @var $add to determine add or minus 
     */
    public function refreshBudgetAndExpense(int $id)
    {
        $department = Department::with('program')->find($id);
        $programs = $department->program;
        $department->budget = $programs->sum('budget');
        $department->expense = $programs->sum('expense');
        $department->disbursement = $programs->sum('disbursement');
        return $department->save();
    }

    function insertStaff(Request $request, $id)
    {
        $request->validate(['staff_id' => 'numeric|required']);

        $user = User::find($request->input('staff_id'));
        if (!$user) {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'User not found.']);
        }
        $user->department_id = $id;

        if ($user->save()) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success add ' . $user->name . ' to Department Staff.']);
        } else {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Failed to add ' . $user->name . ' to Department Staff. Please try again or ask admin.']);
        }
    }

    function removeStaff(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'User not found.']);
        }
        $department = Department::find($user->department_id);
        if (Auth::user()->id !== $department->manager_id) {
            return redirect()->back()->with('notif', ['type' => 'danger', 'message' => 'You are not authorize! Only department manager can remove department staff.']);
        }
        $user->department_id = null;
        if ($user->save()) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success remove ' . $user->name . ' from ' . $department->name . ' Department Staff.']);
        } else {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Failed to remove ' . $user->name . ' from ' . $department->name . ' Department Staff. Please try again or ask admin.']);
        }
    }
}
