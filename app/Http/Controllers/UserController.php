<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display the user's roles edit table.
     */
    public function index(): View
    {
        $users = User::where('roles_id', '!=', null)->all();
        $data = [
            'sidebar' => 'seeo',
            'total' => count($users),
            'chiefs' => $users->where('roles_id', '=', 1),
            'financials' => $users->where('roles_id', '=', 2),
            'operationals' => $users->where('roles_id', '=', 3),
            'staffs' => User::where('roles_id', '=', 4)->paginate(10, '*', 'page'),
        ];
        return view('pages.roles', $data);
    }

    /**
     * remove Special Role from selected user.
     */
    public function removeRole(Request $request)
    {
        $id = $request->input('id');

        // CEO must not remove his/her own account
        if ($id == $request->user()->id) {
            return redirect()->back()->with('notif', ['type' => 'danger', 'message' => 'You are not permitted to remove role of your ownself. Please ask another Chief Executive Officer.']);
        }

        $data = ['roles_id' => 4, 'updated_at' => now()];
        $user = new User;
        // sucees update
        if ($user->change($id, $data) > 0) {
            return redirect()->back()->with('notif', ['type' => 'info', 'message' => 'Role of ' . User::find($id)->name . ' has been removed.']);
        };
        return redirect()->back()->with('notif', ['type' => 'warning', 'message' => 'Role of ' . User::find($id)->name . ' can not be removed. Please try again later, or contact admin.']);
    }

    /**
     * update Special Role to selected user.
     */
    public function update(Request $request)
    {
        $input = $request->input();
        $length = count($input);
        $success_count = 0;
        for ($i = 2; $i < $length; $i += 1) {
            if (Str::of(array_keys($input)[$i])->startsWith('id') && $i != ($length - 1)) {
                $id_key = array_keys($input)[$i];
                $role_key = Str::of(array_keys($input)[$i + 1])->startsWith('role') ? array_keys($input)[$i + 1] : 4;
                $id = $request->input($id_key);
                $role = $role_key == 4 ? 4 : $request->input($role_key);

                // Check if user role not staff, then update
                if ($role != 4) {
                    $data = ['roles_id' => $role, 'updated_at' => now()];
                    $user = new User;
                    // sucees update
                    if ($user->change($id, $data) > 0) {
                        $success_count++;
                    }
                }
            }
        }
        if ($success_count > 0) {
            return back()->with('notif', ['type' => 'info', 'message' => 'Success promote ' . $success_count . ' user(s) role.']);
        } else {
            return back()->with('notif', ['type' => 'warning', 'message' => 'Please select new role for promoted user(s).']);
        }
    }

    public function delete(Request $request)
    {
        // Authorization check
        if (!Hash::check($request->input('password'), $request->user()->password)) {
            return redirect()->route('role')->withErrors([
                'password' => 'Your password is wrong.'
            ])->with('notif', ['type' => 'danger', 'message' => 'Your password is wrong.']);
        }

        $input = $request->input();
        $length = count($input);
        $deleted_users = '';
        for ($i = 0; $i < $length - 3; $i++) {
            $id_key = array_keys($input)[$i];
            if (Str::startsWith($id_key, 'id')) {
                $id = $request->input($id_key);
                $delete_key = array_keys($input)[$i + 1];
                // dd(Str::startsWith($delete_key, 'delete'));
                // check if the selected user delete is switch on
                if (Str::startsWith($delete_key, 'delete')) {
                    $user = User::find($id);
                    $username = $user->name;
                    // dd($username);
                    // sucees delete
                    if ($user->delete() > 0) {
                        if ($deleted_users != '') {
                            $deleted_users .= ', ' . $username;
                        } else {
                            $deleted_users .= $username;
                        }
                    }
                }
            }
        }
        if ($deleted_users == '') {
            return redirect()->route('role')->with('notif', ['type' => 'info', 'message' => 'No deleted user. Please make sure, you have selected the user(s).']);
        } else {
            return redirect()->route('role')->with('notif', ['type' => 'danger', 'message' => 'You have deleted ' . $deleted_users . ' account.']);
        }
    }
}
