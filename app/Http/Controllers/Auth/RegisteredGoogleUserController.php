<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredGoogleUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create($id): View
    {
        return view('auth.register-google', ['id' => $id]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'phone' => ['required', 'numeric', 'starts_with:08', 'digits_between:9,13', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers(), Password::defaults()],
        ]);

        $user = User::where('id_google', '=', $request->id_google)->first();
        if ($user->password == 'empty') {
            $user->update([
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);
        } else {
            return back()->with('notif', ['type' => 'danger', 'message' => 'Your action are dangerous!']);
        }

        Auth::login($user);

        return $user->roles_id == null ? redirect()->route('intro')->with('notif', ['type' => 'info', 'message' => 'Hi ' . $user->name . ', welcome to Blaterian!']) : redirect()->route('dashboard')->with('notif', ['type' => 'info', 'message' => 'Hi ' . $user->name . ', welcome to SEEO Information System!']);
    }
}
