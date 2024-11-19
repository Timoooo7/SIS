<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Str;

class LogbookController extends Controller
{
    function insertLog(Request $request)
    {
        $request->flash();
        $request->validate([
            'logbook_program_id' => ['numeric'],
            'logbook_description' => ['string'],
            'logbook_image' => [File::types(['jpg', 'jpeg', 'png', 'heic'])->max(5 * 1024)],
        ]);
        $auth_user = Auth::user();
        $program_id = $request->input('logbook_program_id');
        $program = Program::find($program_id);
        $image = $request->file('logbook_image');
        $image_name =  'Log' . $program_id . $auth_user->id . '_' . time() . '.' . $image->extension();
        // store image file
        $image->storePubliclyAs('images/log/' . Str::of($program->name)->snake(), $image_name, 'public');
        $log = Logbook::create([
            'program_id' => $program_id,
            'user_id' => $auth_user->id,
            'image' => $image_name,
            'title' => $request->input('logbook_description'),
        ]);

        if ($log) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success submit new log.']);
        }
    }

    function deleteLog(Request $request, $id)
    {
        $log = Logbook::with('program')->find($id);
        if (!$log) {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'This log is not found. Please contact admin or try other log. ']);
        }

        $image = Storage::disk('public')->delete('images/log/' . Str::snake($log->program->name) . '/' . $log->image);
        if ($log->delete() && $image) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success delete log.']);
        } else {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Failed delete log. Please try again or contact admin.']);
        }
    }
}
