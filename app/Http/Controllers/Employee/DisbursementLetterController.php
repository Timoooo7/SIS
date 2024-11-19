<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\DisbursementLetter;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisbursementLetterController extends Controller
{
    function insertDisbursementLetter(Request $request, $id)
    {
        // Validate data
        $request->flash();
        $request->validate([
            'letter' => ['required', 'mimes:pdf', 'max:5000'],
        ]);
        $program = Program::find($id);

        if (Auth::user()->id !== $program->pic_id) {
            return redirect()->route('program', ['id' => $id, 'default_tab' => 2, 'default_collapse' => 1])->with('notif', ['type' => 'warning', 'message' => 'You are not authorize. Only PIC of this program alowed to add new Disbursement Letter.']);
        }

        $letter_name =  'disbursement_letter_' . str()->snake($program->name) . '_' . time() . '.pdf';
        // store reciept file
        $request->file('letter')->storePubliclyAs('document/letter/disbursement', $letter_name, 'public');

        $disbursement_letter = new DisbursementLetter();
        $disbursement_letter->program_id = $id;
        $disbursement_letter->letter = $letter_name;

        if ($disbursement_letter->save()) {
            return redirect()->route('program', ['id' => $id, 'default_tab' => 2, 'default_collapse' => 1])->with('notif', ['type' => 'info', 'message' => 'Success add new Disbursement Letter.']);
        } else {
            return redirect()->route('program', ['id' => $id, 'default_tab' => 2, 'default_collapse' => 1])->with('notif', ['type' => 'warning', 'message' => 'Failed to add new Disbursement Letter. Please try again or contact admin.']);
        }
    }

    function deleteDisbursementLetter($id)
    {
        $disbursement_letter = DisbursementLetter::find($id);
        if ($disbursement_letter->disbursement) {
            return redirect()->route('program', ['id' => $disbursement_letter->program_id, 'default_tab' => 2, 'default_collapse' => 1])->with('notif', ['type' => 'warning', 'message' => 'You can not delete Disbursement Letter after Disbursement Item made.']);
        }
        if ($disbursement_letter->delete()) {
            return redirect()->route('program', ['id' => $disbursement_letter->program_id, 'default_tab' => 2, 'default_collapse' => 1])->with('notif', ['type' => 'info', 'message' => 'Success delete Disbursement Letter.']);
        } else {
            return redirect()->route('program', ['id' => $disbursement_letter->program_id, 'default_tab' => 2, 'default_collapse' => 1])->with('notif', ['type' => 'warning', 'message' => 'Failed to delete Disbursement Letter. Please try again or contact admin.']);
        }
    }
}
