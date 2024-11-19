<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\BudgetItem;
use App\Models\Department;
use App\Models\Program;

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
            return redirect()->route('program', ['id' => $id, 'default_tab' => 1, 'default_collapse' => 1])->with('notif', ['type' => 'info', 'message' => 'Success add new budget item .']);
        };
        return redirect()->route('program', ['id' => $id, 'default_tab' => 1, 'default_collapse' => 1])->with('notif', ['type' => 'warning', 'message' => 'Failed to add new budget item. Please try again later, or contact admin.']);
    }

    /**
     * delete BudgetItem.
     */
    public function deleteBudgetItem($id)
    {
        $budget = BudgetItem::with(['program'])->find($id);
        $program = $budget->program;
        // Authorization check
        if (Auth::user()->id != $program->pic_id) {
            return redirect()->route('program', ['id' => $program->id, 'default_tab' => 1, 'default_collapse' => 1])->with('notif', ['type' => 'danger', 'message' => 'You are not authorized. Please contact the person in charge of this program.']);
        }
        $name = $budget->name;
        $this->updateProgramBudget($program->id, false, $budget->total_price);
        // delete budget item
        if ($budget->delete()) {
            return redirect()->route('program', ['id' => $program->id, 'default_tab' => 1, 'default_collapse' => 1])->with('notif', ['type' => 'info', 'message' => 'Success delete ' . $name . ' from ' . $program->name . ' Program Budget']);
        } else {
            return redirect()->route('program', ['id' => $program->id, 'default_tab' => 1, 'default_collapse' => 1])->with('notif', ['type' => 'warning', 'message' => 'Failed to delete ' . $name . ' from ' . $program->name . ' Program Budget. Please try again or contact admin.']);
        }
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
            $program->budget = $current_budget + $new_budget;
        } else {
            $program->budget = $current_budget - $new_budget;
        }
        $department = Department::find($program->department_id);
        $current_budget = $department->budget;
        if ($add) {
            $department->budget = $current_budget + $new_budget;
        } else {
            $department->budget = $current_budget - $new_budget;
        }
        return $program->save() && $department->save();
    }
}
