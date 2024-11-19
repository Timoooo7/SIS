<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Employee\BlaterianFoodBalanceController;
use App\Http\Controllers\Employee\BlaterianGoodBalanceController;
use App\Http\Controllers\Employee\BudgetItemController;
use App\Http\Controllers\Employee\CashFlowController;
use App\Http\Controllers\Employee\ContributionController;
use App\Http\Controllers\Employee\DashboardController;
use App\Http\Controllers\Employee\DepartmentController;
use App\Http\Controllers\Employee\DisbursementItemController;
use App\Http\Controllers\Employee\DisbursementLetterController;
use App\Http\Controllers\Employee\ExpenseItemController;
use App\Http\Controllers\Employee\GoodController;
use App\Http\Controllers\Employee\GoodDetailController;
use App\Http\Controllers\Employee\GoodInsightController;
use App\Http\Controllers\Employee\GoodOrderController;
use App\Http\Controllers\Employee\GoodSaleController;
use App\Http\Controllers\Employee\LogbookController;
use App\Http\Controllers\Employee\ProfileController;
use App\Http\Controllers\Employee\ProgramController;
use App\Http\Controllers\Employee\SalesController;
use App\Http\Controllers\Employee\StandController;
use App\Http\Controllers\Employee\UserController;
use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

// Test
// Route::get('/test', function () {
//     return redirect()->route('dashboard')->with('notif', ['type' => 'info', 'message' => 'session notif']);
// });
// Welcome
Route::get('/', [WelcomeController::class, 'index'])->name('intro');

// Google Authentication
Route::get('/google/auth/callback', [GoogleController::class, 'callback']);

// Customer
Route::get('/home', [HomeController::class, 'index'])->name('shop');

// Authenticated customer
Route::middleware(['verified'])->group(function () {});

// Authenticated staff
Route::middleware(['auth', 'verified', 'staff'])->group(function () {

    // SEEO Management
    Route::get('/profile/{id?}', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password/change', [ProfileController::class, 'changePassword'])->name('password.change');
    Route::put('/logbook/add/{id?}', [LogbookController::class, 'insertLog'])->name('logbook.add');
    Route::put('/logbook/delete/{id?}', [LogbookController::class, 'deleteLog'])->name('logbook.delete');
    Route::get('/seeo/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::put('/dashboard/post/add', [DashboardController::class, 'addPost'])->name('post.add');
    Route::get('/seeo/role', [UserController::class, 'index'])->name('role');
    Route::put('/role', [UserController::class, 'filterEmployee'])->name('role.filter');
    Route::put('/role/level/add', [UserController::class, 'addOrEditLevel'])->name('level.add.edit');
    Route::put('/payroll/balance/add', [UserController::class, 'setPayrollBalance'])->name('payroll.balance.add');
    Route::get('/seeo/department/{array_id?}/{department_name?}', [DepartmentController::class, 'department'])->name('department');
    Route::get('/seeo/unemployee', [UserController::class, 'unemployee'])->name('unemployee');
    Route::put('/unemployee', [UserController::class, 'filterUnmployee'])->name('unemployee.filter');
    Route::get('/seeo/cashflow', [CashFlowController::class, 'index'])->name('cashflow');
    Route::put('/cashflow/in', [CashFlowController::class, 'filterCashIn'])->name('cashIn.filter');
    Route::put('/cashflow/out', [CashFlowController::class, 'filterCashOut'])->name('cashOut.filter');
    Route::get('/seeo/program/{id}/{default_tab?}/{default_collapse?}', [ProgramController::class, 'program'])->name('program');
    Route::put('/program/expense/item/add/{id}', [ExpenseItemController::class, 'insertExpenseItem'])->name('program.expense.add');
    Route::put('/program/expense/item/delete/{id}', [ExpenseItemController::class, 'deleteExpenseItem'])->name('program.expense.delete');
    Route::put('/program/budget/item/add/{id}', [BudgetItemController::class, 'insertBudgetItem'])->name('program.budget.add');
    Route::put('/program/budget/item/delete/{id}', [BudgetItemController::class, 'deleteBudgetItem'])->name('program.budget.delete');
    Route::put('/program/disbursement/letter/add/{id}', [DisbursementLetterController::class, 'insertDisbursementLetter'])->name('program.disbursement.letter.add');
    Route::get('/program/disbursement/letter/delete/{id?}', [DisbursementLetterController::class, 'deleteDisbursementLetter'])->name('program.disbursement.letter.delete');
    Route::put('/program/add/{id}', [ProgramController::class, 'insertProgram'])->name('program.add');
    Route::put('/program/update/{id}', [ProgramController::class, 'updateProgram'])->name('program.update');
    Route::put('/program/delete/{id}', [ProgramController::class, 'deleteProgram'])->name('program.delete');
    Route::put('/program/budget/filter/{id}', [ProgramController::class, 'filterBudget'])->name('program.budget.filter');
    Route::put('/program/disbursement/filter/{id}', [ProgramController::class, 'filterDisbursement'])->name('program.disbursement.filter');
    Route::put('/program/expense/filter/{id}', [ProgramController::class, 'filterExpense'])->name('program.expense.filter');
    Route::put('/program/staff/filter/{id}', [ProgramController::class, 'filterStaff'])->name('program.staff.filter');
    Route::put('/program/staff/add/{id}', [ProgramController::class, 'insertStaff'])->name('program.staff.add');
    Route::put('/program/staff/delete/{id}', [ProgramController::class, 'deleteStaff'])->name('program.staff.delete');
    Route::put('/department/staff/add/{id}', [DepartmentController::class, 'insertStaff'])->name('department.staff.add');
    Route::put('/department/staff/remove/{id}', [DepartmentController::class, 'removeStaff'])->name('department.staff.remove');
    Route::get('/seeo/contribution', [ContributionController::class, 'index'])->name('contribution');
    Route::put('/contribution', [ContributionController::class, 'filterContribution'])->name('contribution.filter');
    Route::put('/contribution/insert', [ContributionController::class, 'insert'])->name('contribution.insert');

    // Blaterian Business
    Route::get('/blaterian/foods/pointofsales/{id?}', [SalesController::class, 'sales'])->name('food.sales');
    Route::get('/blaterian/foods/balance/{default_tab?}/{refresh?}', [BlaterianFoodBalanceController::class, 'balance'])->name('food.balance');
    Route::get('/blaterian/foods/stand/{id?}/{default_tab?}/{default_collapse?}', [StandController::class, 'stand'])->name('food.stand');
    Route::get('/blaterian/goods/balance/{default_tab?}/{refresh?}', [BlaterianGoodBalanceController::class, 'balance'])->name('good.balance');
    Route::get('/blaterian/goods/product', [GoodController::class, 'product'])->name('good.product');
    Route::get('/blaterian/goods/product/detail/{id}', [GoodDetailController::class, 'detail'])->name('good.product.detail');
    Route::get('/blaterian/goods/insight/detail', [GoodInsightController::class, 'insight'])->name('good.insight');
    Route::put('/food/balance/income', [BlaterianFoodBalanceController::class, 'filterIncome'])->name('food.balance.filter.income');
    Route::put('/food/balance/expense', [BlaterianFoodBalanceController::class, 'filterExpense'])->name('food.balance.filter.expense');
    Route::put('/food/stand/{id}', [StandController::class, 'filterStand'])->name('food.stand.filter');
    Route::put('/food/stand/expense/filter/{id}', [StandController::class, 'filterStandExpense'])->name('stand.expense.filter');
    Route::put('/food/stand/expense/add/{id}', [StandController::class, 'insertStandExpense'])->name('stand.expense.add');
    Route::put('/food/stand/expense/delete/{id}', [StandController::class, 'deleteStandExpenseItem'])->name('stand.expense.delete');
    Route::put('/food/stand/menu/filter/{id}', [StandController::class, 'filterStandMenu'])->name('stand.menu.filter');
    Route::put('/food/stand/menu/add/{id}', [StandController::class, 'insertMenu'])->name('stand.menu.add');
    Route::put('/food/stand/menu/delete/{id}', [StandController::class, 'deleteMenu'])->name('stand.menu.delete');
    Route::put('/food/stand/menu/stock/update', [StandController::class, 'updateStock'])->name('stand.menu.stock.update');
    Route::put('/food/stand/sales/add/{id}', [SalesController::class, 'insertSale'])->name('stand.sale.add');
    Route::put('/food/stand/sales/filter/{id}', [SalesController::class, 'filterStandIncome'])->name('stand.income.filter');
    Route::put('/food/stand/sales/delete/{id}', [SalesController::class, 'deleteSale'])->name('stand.sale.delete');
    Route::get('/food/stand/cashiertoken/{id}', [SalesController::class, 'generateToken'])->name('stand.token');
    Route::put('/good/balance/cash_in', [BlaterianGoodBalanceController::class, 'filterCashIn'])->name('good.balance.filter.cash_in');
    Route::put('/good/balance/cash_out', [BlaterianGoodBalanceController::class, 'filterCashOut'])->name('good.balance.filter.cash_out');
    Route::put('/good/product/filter', [GoodController::class, 'filterProduct'])->name('good.product.filter');
    Route::put('/good/product/image/add/{id}', [GoodDetailController::class, 'insertImage'])->name('good.product.image.add');
    Route::put('/good/product/variant/add/{id}', [GoodDetailController::class, 'insertVariant'])->name('good.product.variant.add');
    Route::put('/good/product/stock/update/{id?}', [GoodDetailController::class, 'updateStock'])->name('good.product.stock.update');
    Route::put('/good/variant/description/update/{id?}', [GoodDetailController::class, 'updateDescription'])->name('good.product.description.update');
    Route::put('/goods/insight/filter/{filter_name?}', [GoodInsightController::class, 'filterInsight'])->name('good.insight.filter');
    Route::put('/good/capital/add', [GoodInsightController::class, 'insertCapital'])->name('good.capital.add');
    Route::put('/good/capital/delete/{id}', [GoodInsightController::class, 'deleteCapital'])->name('good.capital.delete');
    Route::put('/good/cart/add', [GoodSaleController::class, 'addCart'])->name('good.cart.add');
    Route::put('/good/order/add/{id?}', [GoodOrderController::class, 'addOrder'])->name('good.order.add');
    Route::put('/good/transaction/add/{id?}', [GoodSaleController::class, 'addTransaction'])->name('good.transaction.add');
    Route::put('/good/transaction/cancel/{id?}', [GoodSaleController::class, 'cancelTransaction'])->name('good.transaction.cancel');

    // Operational Only Feature
    Route::put('/food/stand/add/new', [StandController::class, 'insertStand'])->middleware('role:3')->name('food.stand.insert');
    Route::put('/food/stand/update/{id}', [StandController::class, 'updateStand'])->middleware('role:3')->name('food.stand.update');
    Route::put('/food/stand/delete/{id}', [StandController::class, 'deleteStand'])->middleware('role:3')->name('food.stand.delete');
    Route::put('/food/stand/expense/validate', [StandController::class, 'validateExpenseReceipt'])->middleware('role:3')->name('stand.expense.validate');
    Route::put('/food/stand/menu/lock/{id}/{valid}', [StandController::class, 'lockMenu'])->middleware('role:3')->name('stand.menu.validate');
    Route::put('/food/stand/sales/validate/{id}/{valid}', [SalesController::class, 'validateSales'])->middleware('role:3')->name('stand.income.validate');
    Route::put('/food/balance/send', [BlaterianFoodBalanceController::class, 'withdrawBalance'])->middleware('role:3')->name('food.balance.withdraw');
    Route::put('/good/product/add', [GoodController::class, 'insertProduct'])->middleware('role:3')->name('good.product.add');
    Route::put('/good/product/delete/{id}', [GoodController::class, 'deleteProduct'])->middleware('role:3')->name('good.product.delete');
    Route::put('/good/product/transaction/status/{id}', [GoodDetailController::class, 'productStatus'])->middleware('role:3')->name('good.product.transaction.status');
    Route::put('/good/capital/validate', [GoodInsightController::class, 'validateCapital'])->name('good.capital.validate');
    Route::put('/good/sale/validate/{id}/{valid}', [GoodSaleController::class, 'validateSale'])->name('good.sale.validate');
    Route::put('/good/sale/delete/{id}', [GoodSaleController::class, 'deleteSale'])->name('good.sale.delete');
    Route::put('/good/balance/send', [BlaterianGoodBalanceController::class, 'withdrawBalance'])->middleware('role:3')->name('good.balance.withdraw');

    // Financial Only Feature
    Route::put('/program/budget/validate/{id}/{valid}', [ProgramController::class, 'validateBudget'])->middleware('role:2')->name('program.budget.validate');
    Route::put('/program/expense/validate', [ExpenseItemController::class, 'validateReceipt'])->middleware('role:2')->name('program.expense.validate');
    Route::put('/program/disbursement/add/{id}', [DisbursementItemController::class, 'insertDisbursementItem'])->middleware('role:2')->name('program.disbursement.add');
    Route::put('/program/disbursement/delete/{id}', [DisbursementItemController::class, 'deleteDisbursementItem'])->middleware('role:2')->name('program.disbursement.delete');
    Route::put('/cash_in_item/item/update', [CashFlowController::class, 'updateCashInItem'])->middleware('role:2')->name('cashIn.update');
    Route::put('/cash_in_item/item/add', [CashFlowController::class, 'insertCashInItem'])->middleware('role:2')->name('cashIn.add');
    Route::put('/cash_in_item/item/delete/{id}', [CashFlowController::class, 'deleteCashInItem'])->middleware('role:2')->name('cashIn.delete');
    Route::put('/cash_in_item/item/validate/{id}', [CashFlowController::class, 'validateCashInItem'])->middleware('role:2')->name('cashIn.validate');
    Route::put('/contribution/settings', [ContributionController::class, 'updateContributionConfiguration'])->middleware('role:2')->name('contribution_settings.update');
    Route::put('/contribution/validation', [ContributionController::class, 'validation'])->middleware('role:2')->name('contribution.validation');
    Route::put('/contribution/delete', [ContributionController::class, 'delete'])->middleware('role:2')->name('contribution.delete');

    // CEO Only Feature
    Route::put('/billboard/add', [DashboardController::class, 'addBillboard'])->middleware('role:1')->name('billboard.add');
    Route::put('/dashboard/post/remove/{id}', [DashboardController::class, 'removePost'])->name('post.remove');
    Route::post('/department/delete/{id}', [DepartmentController::class, 'deleteDepartment'])->middleware('role:1')->name('department.delete');
    Route::put('/department/update/{id}', [DepartmentController::class, 'updateDepartment'])->middleware('role:1')->name('department.update');
    Route::put('/department/add', [DepartmentController::class, 'insertDepartment'])->middleware('role:1')->name('department.add');
    Route::put('/employee/add', [UserController::class, 'addEmployee'])->middleware('role:1')->name('employee.add');
    Route::put('/role/update', [UserController::class, 'update'])->middleware('role:1')->name('role.update');
    Route::put('/user/delete/{id?}', [UserController::class, 'delete'])->middleware('role:1')->name('role.remove');
    Route::put('/billboard/delete/{id?}', [DashboardController::class, 'removeBillboard'])->middleware('role:1')->name('billboard.remove');
    Route::put('/attachment/add', [DashboardController::class, 'addAttachment'])->middleware('role:1')->name('attachment.add');
    Route::put('/attachment/remove/{id}', [DashboardController::class, 'removeAttachment'])->middleware('role:1')->name('attachment.remove');
});

require __DIR__ . '/auth.php';
