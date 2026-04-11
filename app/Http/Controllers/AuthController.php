<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginForm()
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->role === 'admin') {
                return redirect('/admin');
            }

            if ($user->role === 'officer') {
                return redirect('/officer');
            }
        }

        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return back()
                ->with('error', 'Email atau password salah.')
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect('/admin');
        }

        if ($user->role === 'officer') {
            return redirect('/officer');
        }

        Auth::logout();
        return redirect('/login')->with('error', 'Role tidak dikenali');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}