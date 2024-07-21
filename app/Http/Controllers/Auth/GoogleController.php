<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use PhpParser\Node\Expr\Throw_;

class GoogleController extends Controller
{
    // redirect to google authentication
    function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // get response from google
    function callback()
    {
        $user = Socialite::driver('google')->user();
        dd($user);
        try {
            $findUser = User::where('id_google', $user->id)->first();
            if ($findUser) {
                Auth::login($findUser);
                return redirect()->intended('intro')->with('notif', ['type' => 'info', 'message' => 'Hi ' . $findUser->name . ', welcome to Blaterian!']);
            } else {
                $newUser = User::updateOrCreate([
                    'name' => $user->name,
                    'email' => $user->email,
                    'id_google' => $user->id,
                    'email_verified_at' => now(),
                ]);
                Auth::login($newUser);
                return redirect()->intended('intro')->with('notif', ['type' => 'info', 'message' => 'Hi ' . $newUser->name . ', welcome to Blaterian!']);
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
