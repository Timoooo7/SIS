<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->flash();
        $findUserMail = User::where('email', '=', $request->email)->first();
        if (!$findUserMail) {
            return redirect()->route('login')->with('notif', ['type' => 'warning', 'message' => 'Can not find account with email ' . $request->email . '. Please register your account first.']);
        }

        if (!$findUserMail->password) {
            return redirect()->route('register.google', ['id' => $findUserMail->id_google])->with('notif', ['type' => 'warning', 'message' => 'You must complete the registration before Login.']);
        }

        $request->authenticate();

        $request->session()->regenerate();

        if (Auth::user()->roles_id !== null) {
            return redirect()->intended(route('dashboard', absolute: false))->with('notif', ['type' => 'info', 'message' => 'Hi ' . Auth::user()->name . ', welcome to SEEO Information System']);
        } else {
            return redirect()->intended(route('shop', absolute: false))->with('notif', ['type' => 'info', 'message' => 'Hi ' . Auth::user()->name . ', welcome to Blaterian']);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
