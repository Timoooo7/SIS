<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\PayrollBalance;
use App\Models\PayrollLevel;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display the employee's roles edit table.
     */
    public function index(Request $request): View
    {
        $employee_session = session('employee', ['category' => 'name', 'order' => 'asc', 'keyword' => '']);
        // Save to session
        $request->session()->put('employee', $employee_session);
        // Stand filter
        $category = $employee_session['category'];
        $order = $employee_session['order'];
        $keyword = $employee_session['keyword'];
        $employees = $keyword !== null ?
            User::where('roles_id', '!=', null)->orderByRaw("
                CASE
                    WHEN name = ? THEN 1
                    WHEN name LIKE ? THEN 2
                    WHEN name LIKE ? THEN 3
                    ELSE 4
                END 
            ", [$keyword, "$keyword%", "%$keyword%"],)->with(['department', 'roles'])->get() :
            User::where('roles_id', '!=', null)->orderBy($category, $order)->with(['department', 'roles'])->get();
        $payroll_balance = PayrollBalance::first();
        if (!$payroll_balance) {
            PayrollBalance::create(['balance' => 0]);
        }
        $payroll_balance = $payroll_balance ? $payroll_balance->balance : 0;
        $data = [
            'sidebar' => 'seeo',
            'employees' => $employees,
            'filter' => [
                'keyword' => $keyword,
                'category' => $category,
                'order' => $order,
            ],
            'roles' => Role::all(),
            'level_list' => PayrollLevel::orderBy('level', 'asc')->get(),
            'payroll_balance' => $payroll_balance,
            'departments' => Department::all(),
        ];
        return view('pages.staff.roles', $data);
    }

    /**
     * Display the enemployee's roles edit table.
     */
    public function unemployee(Request $request): View
    {
        $unemployee_session = session('unemployee', ['category' => 'name', 'order' => 'asc', 'keyword' => '']);
        // Save to session
        $request->session()->put('unemployee', $unemployee_session);
        // Stand filter
        $category = $unemployee_session['category'];
        $order = $unemployee_session['order'];
        $keyword = $unemployee_session['keyword'];
        $unemployees = $keyword !== null ?
            User::where('roles_id', '=', null)->orderByRaw("
                CASE
                    WHEN name = ? THEN 1
                    WHEN name LIKE ? THEN 2
                    WHEN name LIKE ? THEN 3
                    ELSE 4
                END 
            ", [$keyword, "$keyword%", "%$keyword%"],)->get() :
            User::where('roles_id', '=', null)->orderBy($category, $order)->get();
        $data = [
            'sidebar' => 'seeo',
            'unemployees' => $unemployees,
            'filter' => [
                'keyword' => $keyword,
                'category' => $category,
                'order' => $order,
            ],
            'roles' => Role::all(),
            'departments' => Department::all(),
        ];
        return view('pages.staff.unemployee', $data);
    }

    /**
     * find and filter employee.
     */
    function filterEmployee(Request $request)
    {
        $keyword = $request->has('keyword') ? $request->input('keyword') : null;
        $category = !$request->has('keyword') && $request->has('category') ?  $request->input('category') : 'name';
        $order = !$request->has('keyword') && $request->has('order') ?  $request->input('order') : 'asc';
        session()->put('employee', ['category' => $category, 'order' => $order, 'keyword' => $keyword,]);
        return redirect()->route('role');
    }

    /**
     * find and filter employee.
     */
    function filterUnmployee(Request $request)
    {
        $keyword = $request->has('keyword') ? $request->input('keyword') : null;
        $category = !$request->has('keyword') && $request->has('category') ?  $request->input('category') : 'name';
        $order = !$request->has('keyword') && $request->has('order') ?  $request->input('order') : 'asc';
        session()->put('unemployee', ['category' => $category, 'order' => $order, 'keyword' => $keyword,]);
        return redirect()->route('role.add');
    }

    /**
     * add registered user to employee list.
     */
    function addEmployee(Request $request)
    {
        // Validating data
        $request->validate([
            'user_id' => ['required', 'numeric'],
            'roles_id' => ['required', 'numeric'],
        ]);
        if ($request->has("department_id")) {
            $request->validate(['department_id' => ['numeric']]);
        }

        $user = User::find($request->input('user_id'));
        $user->department_id = $request->input('department_id');
        $user->roles_id = $request->input('roles_id');
        $role = Role::find($request->input('roles_id'))->name;

        // save new role
        if ($user->save() > 0) {
            return back()->with('notif', ['type' => 'info', 'message' => 'Success add ' . $user->name . ' as ' . $role . ' .']);
        } else {
            return back()->with('notif', ['type' => 'warning', 'message' => 'Failed to add employee. Please try again or contact adminsitrator.']);
        }
    }

    /**
     * Set payroll balance
     */
    function setPayrollBalance(Request $request)
    {
        $request->flash();
        $request->validate(['payroll_balance' => 'required|numeric']);

        $balance = PayrollBalance::first();
        $balance->balance = $request->input('payroll_balance');
        $balance->save();

        return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success set new payroll balance.']);
    }

    /**
     * update Special Role to selected user.
     */
    public function update(Request $request)
    {
        $request->flash();
        $request->validate([
            'user_id' => ['required', 'numeric'],
            'roles_id' => ['required', 'numeric'],
            'level' => ['required', 'numeric'],
        ]);
        // CEO can not selfupdate 
        if (Auth::user()->id == $request->input('user_id')) {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'You are prohibitted to update your role. Please ask another Chief Executive Officer.']);
        }
        $user = User::find($request->input('user_id'));
        if ($request->input('level') == 0) {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Employee level must not be unset. Please select any level for ' . $user->name . '.']);
        }

        $user->roles_id = $request->input('roles_id');

        if ($user->level == 0 || $user->level == null) {
            $payroll_level = PayrollLevel::where('level', '=', $request->input('level'))->first();
            $payroll_level->employee += 1;
            $payroll_level->save();
            $user->level = $request->input('level');
        } else {
            if ($user->level !== $request->input('level')) {
                $old_payroll_level = PayrollLevel::where('level', '=', $user->level)->first();
                $old_payroll_level->employee -= 1;
                $old_payroll_level->save();
                $new_payroll_level = PayrollLevel::where('level', '=', $request->input('level'))->first();
                $new_payroll_level->employee += 1;
                $new_payroll_level->save();
                $user->level = $request->input('level');
            }
        }


        // save new role
        if ($user->save() > 0) {
            return back()->with('notif', ['type' => 'info', 'message' => 'Success update ' . $user->name . '.']);
        } else {
            return back()->with('notif', ['type' => 'warning', 'message' => 'Update failed. Please try again or contact adminsitrator.']);
        }
    }

    /**
     * remove user from employee list.
     */
    public function delete(Request $request, $id)
    {
        // check if CEO update his/herself
        if (Auth::user()->id == $id) {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'You are prohibitted to delete your role. Please ask another Chief Executive Officer.']);
        }

        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('notif', ['type' => 'danger', 'message' => 'User not found! Please try again or ask admin.']);
        }

        // check if user is have relation to any database
        if ($user->program) {
            return redirect()->route('role')->with('notif', ['type' => 'danger', 'message' => 'Can not delete! ' . $user->name . ' is in charge of ' . $user->program->name . ' Program.']);
        }
        if ($user->stand) {
            return redirect()->route('role')->with('notif', ['type' => 'danger', 'message' => 'Can not delete! ' . $user->name . ' is in charge of ' . $user->stand->name . ' Department.']);
        }
        if ($user->manager) {
            return redirect()->route('role')->with('notif', ['type' => 'danger', 'message' => 'Can not delete! ' . $user->name . ' is manager of ' . $user->manager->name . ' Department.']);
        }
        if ($user->level !== null || $user->level > 0) {
            $payroll_level = PayrollLevel::where('level', '=', $user->level)->first();
            $payroll_level->employee -= 1;
            $payroll_level->save();
        }
        $user->roles_id = NULL;
        $user->department_id = NULL;
        if ($user->save() > 0) {
            return redirect()->route('role')->with('notif', ['type' => 'info', 'message' => $user->name . ' account has been removed from Employee List.']);
        } else {
            return redirect()->route('role')->with('notif', ['type' => 'warning', 'message' => 'Delete failed. Please try again or contact admin.']);
        }
    }

    /**
     * create new employee level.
     */
    function addOrEditLevel(Request $request)
    {
        $request->flash();
        $request->validate([
            'level' => 'required|numeric',
            'salary' => 'required|numeric'
        ]);
        $list = PayrollLevel::where('level', '=', $request->input('level'))->first();
        if ($list) {
            $list->level = $request->input('level');
            $list->salary = $request->input('salary');
            $list->save();
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success edit Level ' . $list->level . '.']);
        }

        PayrollLevel::create([
            'level' => $request->input('level'),
            'salary' => $request->input('salary'),
        ]);

        return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success set new Level.']);
    }
}
