<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\ProgramStaff;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request, $id = 0): View
    {
        $auth_user = Auth::user();
        $find_user = User::find($id);
        $profile = $id > 0 ? ($find_user ? $find_user : $auth_user) : $auth_user;
        $logbook = Logbook::with(['program', 'employee'])->where('user_id', '=', $profile->id)->get();
        $logbook->each(function ($item, $key) {
            $item->program->name = Str::snake($item->program->name);
            $item->program->budget = format_date_time($item->created_at); //program->budget is to save formated time temporary
        });
        $staff_list = ProgramStaff::with(['program'])->where('user_id', '=', $profile->id)->get();
        $program_list = collect([]);
        foreach ($staff_list as $staff) {
            if ($staff->program) {
                if (!$program_list->keys()->contains($staff->program->name)) {
                    $program_list->put($staff->program->name, $staff->program);
                }
            }
        }
        $data = [
            'profile' => $profile,
            'logbook' => $logbook,
            'program_list' => $program_list,
            'is_authenticated' => $auth_user->id == $profile->id,
        ];
        return view('pages.profile.edit', $data);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $request->validate([
            'phone' => ['numeric', 'max_digits:15', 'min_digits:10', 'starts_with:0', 'required'],
            'profile_image' => [File::types(['jpg', 'jpeg', 'png', 'heic'])->max(5 * 1024)]
        ]);

        $auth_user = Auth::user();
        $user = User::find($auth_user->id);
        // Update new profile image if exist in request
        if ($request->hasFile('profile_image')) {
            // Delete previous profile if exist
            if ($auth_user->profile_image) {
                Storage::disk('public')->delete('images/profile/' . $auth_user->profile_image);
            }

            $image = $request->file('profile_image');
            $image_name =  'profile_' . $auth_user->id . time() . '.' . $image->extension();
            // store reciept file
            $image->storePubliclyAs('images/profile', $image_name, 'public');
            $user->profile_image = $image_name;
        }

        $user->phone = $request->input('phone');


        if ($user->save()) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Success update profile.']);
        } else {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Failed update profile. Please try again or contact admin.']);
        }
    }

    function changePassword(Request $request)
    {
        $attempt = session('password_attempt', 4);
        if (($attempt - 1) == 0) {
            session()->pull('password_attempt');
            return redirect()->route('logout')->with('Because your password is wring too many times, you are logged out. Please try again later.');
        }
        $request->flash();
        $request->validate([
            'old_password' => 'required',
            'password' => ['required', Password::min(8)->mixedCase()->numbers(), Password::defaults(), 'confirmed'],
        ]);
        $auth_user = Auth::user();
        if (!Hash::check($request->input('old_password'), $auth_user->password)) {
            session(['password_attempt' => ($attempt - 1)]);
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Your old password is wrong. You have ' . $attempt . ' more attempt.']);
        }
        if ($request->input('old_password') == $request->input('password')) {
            return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Your new password is same to your old password. Please create new different password.']);
        }

        $user = User::find($auth_user->id);
        $user->password = Hash::make($request->input('password'));
        session()->pull('password_attempt');
        if ($user->save()) {
            return redirect()->route('profile.edit')->with('notif', ['type' => 'info', 'message' => 'Your password is changed.']);
        } else {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Failed to change password. Please try again or ask admin.']);
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
