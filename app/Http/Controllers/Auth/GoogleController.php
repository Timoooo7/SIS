<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

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
        try {
            $findUserGoogle = User::where('id_google', $user->id)->first();
            $findUserMail = User::where('email', $user->email)->first();
            if ($findUserGoogle && $findUserMail) {
                return  $findUserGoogle->password ? $this->login_google($findUserGoogle) : redirect()->route('register.google', ['id' => $user->id_google]);
            } else if ($findUserMail) {
                return $this->register_google($findUserMail, $user->id);
            } else {
                return $this->register($user->name, $user->email, $user->id);
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    // google login authentication
    private function login_google(User $user)
    {
        Auth::login($user);
        return $user->roles_id ? redirect()->route('intro')->with('notif', ['type' => 'info', 'message' => 'Hi ' . $user->name . ', welcome to Blaterian!']) : redirect()->route('dashboard')->with('notif', ['type' => 'info', 'message' => 'Hi ' . $user->name . ', welcome to SEEO Information System!']);
    }

    // google register authentication 
    private function register_google(User $user, $id_google)
    {
        $user->change($user->id, [
            'id_google' => $id_google,
            'updated_at' => now(),
        ]);
        return $this->login_google($user);
    }

    // manual register authentication 
    private function register($name, $email, $id)
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'id_google' => $id,
        ]);
        $user->markEmailAsVerified();
        return redirect()->route('register.google', ['id' => $id]);
    }
}
