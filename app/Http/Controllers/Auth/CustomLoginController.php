<?php

namespace App\Http\Controllers\Auth;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class CustomLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.custom-login'); // Custom blade file for login
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->remember)) {
            return redirect()->intended('dashboard'); // Redirect to the dashboard or intended URL
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
