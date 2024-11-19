<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\CashInItem;
use App\Models\Contribution;
use App\Models\ContributionConfig;
use App\Models\ContributionReceipt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;
use Illuminate\View\View;

class ContributionController extends Controller
{
    public function index(Request $request): View
    {
        $contribution_session = session('contribution', ['category' => 'name', 'order' => 'asc', 'keyword' => '']);
        // Save to session
        $request->session()->put('contribution', $contribution_session);
        // Stand filter
        $category = $contribution_session['category'];
        $order = $contribution_session['order'];
        $keyword = $contribution_session['keyword'];
        $users = $keyword !== null ?
            User::select('id', 'name')->orderByRaw("
                CASE
                WHEN name = ? THEN 1
                WHEN name LIKE ? THEN 2
                WHEN name LIKE ? THEN 3
                ELSE 4
                END 
            ", [$keyword, "$keyword%", "%$keyword%"])->with(['contribution' => ['receipt']])->get() :
            User::select('id', 'name')->orderBy($category, $order)->with(['contribution' => ['receipt']])->get();
        $contribution_config = ContributionConfig::first() ? ContributionConfig::first() :
            ContributionConfig::create([
                'price' => 0,
                'start' => 0,
                'period' => 0,
                'financial_id' => 0,
            ]);

        $data = [
            'sidebar' => 'seeo',
            'users' => $users,
            'contribution_config' => $contribution_config,
            'filter' => [
                'keyword' => $keyword,
                'category' => $category,
                'order' => $order,
            ],
        ];
        if (CashInItem::where('name', '=', 'Contribution Charge')->get()->count() == 0) {
            $this->setCashIn();
        }
        return view('pages.staff.contribution', $data);
    }

    /**
     * find and filter contribution.
     */
    function filterContribution(Request $request)
    {
        $keyword = $request->has('keyword') ? $request->input('keyword') : null;
        $category = !$request->has('keyword') && $request->has('category') ?  $request->input('category') : 'name';
        $order = !$request->has('keyword') && $request->has('order') ?  $request->input('order') : 'asc';
        session()->put('contribution', ['category' => $category, 'order' => $order, 'keyword' => $keyword,]);
        return redirect()->route('contribution');
    }

    function setCashIn()
    {
        // set Contribution Charge in Cash In Item.
        return CashInItem::create([
            'name' => 'Contribution Charge',
            'price' => 0,
            'financial_id' => Auth::user()->id,
        ]);
    }


    /**
     * insert contribution receipt.
     */
    function insert(Request $request)
    {
        // Data Validation
        $request->validate([
            'insert_contribution_month' => ['required', 'numeric'],
            'insert_contribution_receipt' => [File::types(['jpg', 'jpeg', 'png', 'heic'])->max(5 * 1024)],
        ]);

        $month = $request->input(('insert_contribution_month'));
        $config = ContributionConfig::first();
        $employee = User::select('id', 'name')->with('contribution')->find(Auth::user()->id);
        // check if input month greater than configuration period.
        if ($employee->contribution && $employee->contribution->month + $month > $config->period) {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'You can not contribute more than ' . $config->period - $employee->contribution->month . ' month.']);
        }
        if (!$employee->contribution) {
            $contribution = new Contribution();
            $contribution->user_id = $employee->id;
            $contribution->months = 0;
            $contribution->save();
        }
        $contribution = User::select('id')->find($employee->id)->contribution;
        $updated_contribution = $contribution->receipt ? $contribution->receipt->count() : 0;
        $contribution_receipt = new ContributionReceipt();
        $contribution_receipt->contribution_id = $contribution->id;
        $contribution_receipt->months = $request->input('insert_contribution_month');
        // dd($request->file('insert_contribution_receipt'));
        $receipt = $request->file('insert_contribution_receipt');
        $receipt_name =  'cc_' . str()->snake($employee->name) . '_' . $updated_contribution + 1 . '.' . $receipt->extension();
        $receipt->storePubliclyAs('images/receipt/contribution', $receipt_name, 'public');
        $contribution_receipt->receipt = $receipt_name;

        if ($contribution_receipt->save() > 0) {
            return back()->with('notif', ['type' => 'info', 'message' => 'Your contribution charge upload successfully. Unpaid bill will be updated when Financial validate your receipt.']);
        } else {
            return back()->with('notif', ['type' => 'warning', 'message' => 'Your contribution charge upload failed. Please try again or contact admin.']);
        }
    }

    /**
     * delete contribution receipt.
     */
    function delete(Request $request)
    {
        $receipt = ContributionReceipt::find($request->input('receipt_id'));
        if ($receipt->financial_id) {
            return back()->with('notif', ['type' => 'warning', 'message' => 'Please unvalidate this receipt before deleting it.']);
        }
        if ($receipt->delete() > 0) {
            return back()->with('notif', ['type' => 'info', 'message' => 'Success delete ' . $receipt->receipt . '.']);
        } else {
            return back()->with('notif', ['type' => 'warning', 'message' => 'Failed delete ' . $receipt->receipt . '. Please try again or contact admin.']);
        }
    }

    /**
     * validate contribution receipt.
     */
    function validation(Request $request)
    {
        $request->validate([
            'receipt_id' => ['required', 'numeric'],
            'validate' => ['required', 'string'],
        ]);
        $receipt_id = $request->input('receipt_id');
        $receipt = ContributionReceipt::find($receipt_id);
        $validate = $request->input('validate');
        if ($validate !== 'validate' && $validate !== 'unvalidate') {
            return back()->with('notif', ['type' => 'warning', 'message' => 'Not valid command. Please do not commit any changes to the application.']);
        }
        if (!$receipt) {
            return back()->with('notif', ['type' => 'warning', 'message' => 'Contribution Receipt not found. Please do not commit any changes to the application.']);
        }

        $cash_in = CashInItem::where('name', '=', 'Contribution Charge')->first();
        $config = ContributionConfig::first();

        if ($validate == 'validate') {
            $receipt->financial_id = Auth::user()->id;
            $receipt->contribution->months += $receipt->months;
            $cash_in->price += $receipt->months * $config->price;
        } else {
            $receipt->financial_id = null;
            $receipt->contribution->months -= $receipt->months;
            $cash_in->price -= $receipt->months * $config->price;
        }
        // dd($receipt->save());
        if ($receipt->contribution->save() && $receipt->save() && $cash_in->save()) {
            return back()->with('notif', ['type' => 'info', 'message' => 'Success ' . $validate . ' ' . $receipt->receipt . '.']);
        } else {
            return back()->with('notif', ['type' => 'warning', 'message' => 'Failed ' . $validate . ' ' . $receipt->receipt . '.']);
        }
    }


    // CONFTRIBUTION SETTINGS

    /**
     * update configuration.
     */
    function updateContributionConfiguration(Request $request)
    {
        // Data Validation
        $request->validate([
            'price' => ['required', 'numeric'],
            'start_month' => ['required', 'numeric'],
            'end_month' => ['required', 'numeric', ''],
        ]);

        $contribution_config = ContributionConfig::first();
        $contribution_config->price = $request->input('price');
        $contribution_config->start = $request->input('start_month');
        $contribution_config->period = $request->input('end_month') - $request->input('start_month') + 1;
        $contribution_config->financial_id = Auth::user()->id;

        if ($contribution_config->save()) {
            return back()->with('notif', ['type' => 'info', 'message' => 'Success settting Contribution.']);
        } else {
            return back()->with('notif', ['type' => 'waring', 'message' => 'Failed settting Contribution. Please try again, or contact admin.']);
        }
    }
}
