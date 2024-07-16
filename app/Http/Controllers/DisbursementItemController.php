<?php

namespace App\Http\Controllers;

use App\Models\DisbursementItem;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class DisbursementItemController extends Controller
{
    /**
     * add new DisbursementItem.
     */
    public function insertDisbursementItem(Request $request, $id)
    {
        $request->flash();
        // Validating data
        $request->validate([
            'disbursement_name' => ['required', 'string'],
            'disbursement_price' => ['required', 'integer'],
            'disbursement_reciept' => ['required', File::types(['jpg', 'jpeg', 'png', 'heic'])->max(5 * 1024)],
        ]);
        $reciept = $request->file('disbursement_reciept');
        $program = Program::find($id);
        $reciept_name =  'di_' . str()->snake($program->name) . '_' . time() . '.' . $reciept->extension();
        // store reciept file
        $reciept->storePubliclyAs('images/receipt/disbursement', $reciept_name, 'public');
        $data = [
            'name' => $request->input('disbursement_name'),
            'financial_id' => Auth::user()->id,
            'program_id' => $id,
            'price' => $request->input('disbursement_price'),
            'reciept' => $reciept_name,
            'updated_at' => now(),
            'created_at' => now()
        ];
        // update necessary data
        $this->updateProgramDisbursement($id, true, $request->input('disbursement_price'));
        // sucees insert
        $disbursementItem = new DisbursementItem();
        if ($disbursementItem->insert($data) > 0) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'New disbursement item has been added.']);
        };
        return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Can not add new disbursement item. Please try again later, or contact admin.']);
    }

    /**
     * update DisbursementItem name.
     */
    public function updateDisbursementItem(Request $request,)
    {
        // check id
        if ($request->input('id_disbursement') == 'null') {
            return back()->with('notif', ['type' => 'warning', 'message' => 'Please select an item.']);
        }

        // check delete
        if ($request->input('action') == 'delete') {
            return $this->deleteDisbursementItem($request->input('id'));
        }

        // check if everything empty
        if (!$request->input()) {
            return back()->with('notif', ['type' => 'warning', 'message' => 'Give some inputs to update.']);
        }
        // Validating data
        $request->validate([
            'price_disbursement' => ['nullable', 'numeric'],
            'reciept_disbursement' => ['nullable', File::types(['jpg', 'jpeg', 'png', 'heic'])->max(5 * 1024)],
        ]);
        $di_id = $request->input('id_disbursement');
        $disbursementItem = DisbursementItem::find($di_id);

        // set default value if input empty
        $price = $request->input('price_disbursement') != null ? $request->input('price_disbursement') : $disbursementItem->price;
        // store reciept file
        if ($request->hasFile('reciept_disbursement')) {
            $reciept = $request->file('reciept_disbursement');
            Storage::disk('public')->delete('images/receipt/disbursement/' . $disbursementItem->reciept);
            $reciept_name = 'di_' . str()->snake($disbursementItem->program->name) . '_' . time() . '.' . $reciept->extension();
            $reciept->storePubliclyAs('images/receipt/disbursement', $reciept_name, 'public');
        } else {
            $reciept_name = $disbursementItem->reciept;
        }
        $data = [
            'price' => $price,
            'reciept' => $reciept_name,
            'updated_at' => now(),
        ];
        $expenseItem = new DisbursementItem();
        // update necessary data
        $this->updateProgramDisbursement($disbursementItem->program_id, true, $price - $disbursementItem->price);
        // sucees update
        if ($expenseItem->change($di_id, $data) > 0) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Disbursement item ' . DisbursementItem::find($di_id)->name . ' is updated.']);
        };
        return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Can not update disbursement item at the moment. Please try again later, or contact admin.']);
    }

    /**
     * delete DisbursementItem.
     */
    public function deleteDisbursementItem($di_id)
    {
        $disbursementItem = DisbursementItem::find($di_id);
        $name = $disbursementItem->name;
        // update necessary data
        $this->updateProgramDisbursement($disbursementItem->program_id, false, $disbursementItem->price);
        // delete budget item
        Storage::disk('public')->delete('images/receipt/disbursement/' . $disbursementItem->reciept);
        $disbursementItem->delete();

        return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Disbursement item ' . $name . ' has been deleted.']);
    }

    /**
     * update new program total disbursement.
     * 
     * 
     *  @var $id is program id, @var $add to determine add or minus 
     */
    public function updateProgramDisbursement(int $id, bool $add, int $new_disbursement)
    {
        $program = Program::find($id);
        $current_disbursement = $program->disbursement;
        if ($add) {
            $data = ['disbursement' => $current_disbursement + $new_disbursement, 'updated_at' => now()];
        } else {
            $data = ['disbursement' => $current_disbursement - $new_disbursement, 'updated_at' => now()];
        }
        return $program->change($id, $data);
    }
}
