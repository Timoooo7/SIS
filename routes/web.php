<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\BudgetItemController;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DisbursementItemController;
use App\Http\Controllers\ExpenseItemController;
use App\Http\Controllers\GoodController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\StandController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('intro');

// Google Authentication
Route::get('/google/auth/callback', [GoogleController::class, 'callback']);

// Authenticated staff
Route::middleware(['auth', 'verified', 'staff'])->group(function () {
    // General Feature
    Route::put('/expense/item/update/{pic_id}', [ExpenseItemController::class, 'updateExpenseItem'])->name('expense_item.update');
    Route::put('/expense/item/add/{id}', [ExpenseItemController::class, 'insertExpenseItem'])->name('expense_item.add');
    Route::put('/budget/item/update/{pic_id}', [BudgetItemController::class, 'updateBudgetItem'])->name('budget_item.update');
    Route::put('/budget/item/add/{id}', [BudgetItemController::class, 'insertBudgetItem'])->name('budget_item.add');
    Route::put('/program/add/{id}', [ProgramController::class, 'insertProgram'])->name('program.add');
    Route::put('/program/update/{id}', [ProgramController::class, 'updateProgram'])->name('program.update');
    Route::put('/program/delete/{id}', [ProgramController::class, 'deleteProgram'])->name('program.delete');
    Route::get('/seeo/program/{id}', [ProgramController::class, 'program'])->name('program');
    Route::get('/seeo/department', [DepartmentController::class, 'department'])->name('department');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/seeo/role', [UserController::class, 'index'])->name('role');
    Route::get('/seeo/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/seeo/cashflow', [CashFlowController::class, 'index'])->name('cashflow');
    Route::get('/blaterian/foods/sales', [SalesController::class, 'sales'])->name('food.sales');
    Route::get('/blaterian/foods/stand/{array_id}', [StandController::class, 'stand'])->name('food.stand');
    Route::get('/blaterian/good', [GoodController::class, 'sales'])->name('good');
    Route::put('/food/stand', [StandController::class, 'findStand'])->name('food.stand.find');
    Route::put('/food/stand/expense/add/{stand_id}', [StandController::class, 'insertStandExpense'])->name('stand.expense.add');
    Route::put('/food/stand/expense/update/{pic_id}', [StandController::class, 'updateStandExpenseItem'])->name('stand.expense.update');
    Route::put('/food/stand/menu/add/{stand_id}', [StandController::class, 'insertMenu'])->name('stand.menu.add');
    Route::put('/food/stand/menu/update', [StandController::class, 'updateMenu'])->name('stand.menu.update');
    Route::put('/food/stand/sales/add/{stand_id}', [SalesController::class, 'insertSale'])->name('stand.sale.add');
    Route::put('/food/stand/sales/update/{stand_id}', [SalesController::class, 'updateSale'])->name('stand.sale.update');
    Route::get('/food/stand/cashiertoken/{stand_id}', [SalesController::class, 'generateToken'])->name('stand.token');
    // Operational Only Feature
    Route::put('/food/stand/add', [StandController::class, 'insertStand'])->middleware('role:3')->name('food.stand.add');
    Route::put('/food/stand/update/{stand_id}', [StandController::class, 'updateStand'])->middleware('role:3')->name('stand.update');
    Route::put('/food/stand/delete/{stand_id}', [StandController::class, 'deleteStand'])->middleware('role:3')->name('stand.delete');
    Route::put('/food/stand/expense/validate', [StandController::class, 'validateExpenseReceipt'])->middleware('role:3')->name('stand.expense.validate');
    Route::put('/food/stand/menu/lock', [StandController::class, 'lockMenu'])->middleware('role:3')->name('stand.menu.lock');
    Route::put('/food/stand/sales/validate/{stand_id}', [SalesController::class, 'validateSales'])->middleware('role:3')->name('stand.sales.validate');
    // Financial Only Feature
    Route::put('/budget/item/approval/{id}', [BudgetItemController::class, 'approval'])->middleware('role:2')->name('budget.approval');
    Route::put('/expense/item/validate', [ExpenseItemController::class, 'validateReceipt'])->middleware('role:2')->name('expense_item.validate');
    Route::put('/budget/item/validate/{id}', [ProgramController::class, 'validateBudget'])->middleware('role:2')->name('budget_item.validate');
    Route::put('/disbursement/item/update', [DisbursementItemController::class, 'updateDisbursementItem'])->middleware('role:2')->name('disbursement_item.update');
    Route::put('/disbursement/item/add/{id}', [DisbursementItemController::class, 'insertDisbursementItem'])->middleware('role:2')->name('disbursement_item.add');
    Route::put('/cash_in_item/item/update', [CashFlowController::class, 'updateCashInItem'])->middleware('role:2')->name('cash_in_item.update');
    Route::put('/cash_in_item/item/add', [CashFlowController::class, 'insertCashInItem'])->middleware('role:2')->name('cash_in_item.add');
    // CEO Only Feature
    Route::post('/department/delete/{id}', [DepartmentController::class, 'deleteDepartment'])->middleware('role:1')->name('department.delete');
    Route::put('/department/update/{id}', [DepartmentController::class, 'updateDepartment'])->middleware('role:1')->name('department.update');
    Route::put('/department/add', [DepartmentController::class, 'insertDepartment'])->middleware('role:1')->name('department.add');
    Route::put('/role/remove', [UserController::class, 'removeRole'])->middleware('role:1')->name('role.removeRole');
    Route::put('/role/update', [UserController::class, 'update'])->middleware('role:1')->name('role.update');
    Route::put('/user/delete', [UserController::class, 'delete'])->middleware('role:1')->name('user.delete');
});

require __DIR__ . '/auth.php';
