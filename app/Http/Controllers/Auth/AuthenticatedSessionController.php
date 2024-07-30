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
        $findUserMail = User::where('email', '=', $request->email)->first();

        if (!$findUserMail) {
            return redirect()->route('register.google', ['id' => $findUserMail]);
        }

        $request->authenticate();

        $request->session()->regenerate();

        if (Auth::user()->roles_id !== null) {
            return redirect()->intended(route('dashboard', absolute: false))->with('notif', ['type' => 'info', 'message' => 'Hi ' . Auth::user()->name . ', welcome to SEEO Information System']);
        } else {
            return redirect()->intended(route('intro', absolute: false))->with('notif', ['type' => 'info', 'message' => 'Hi ' . Auth::user()->name . ', welcome to Blaterian']);
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
