<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\LdapAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login attempt.
     *
     * Accepts either an email (local account) or a username
     * (LDAP account) in the same "email" field, and routes the
     * password check to the right place accordingly.
     */
    public function login(Request $request, LdapAuthService $ldap)
    {
        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        $login    = trim($request->input('email'));
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        $user = User::where('email', $login)
            ->orWhere('username', $login)
            ->first();

        $authenticated = false;

        if ($user && $user->isLdap()) {

            $authenticated = $ldap->attempt(
                $user->username,
                $password
            );

            if ($authenticated) {
                Auth::login($user, $remember);
            }

        } elseif ($user) {

            $authenticated = Auth::attempt(
                [
                    'email'    => $user->email,
                    'password' => $password,
                ],
                $remember
            );

        }

        if ($authenticated) {

            if (!Auth::user()->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('pending.approval');
            }

            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()
            ->withInput($request->only('email', 'remember'))
            ->with('error', 'Invalid credentials. Please try again.');
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
