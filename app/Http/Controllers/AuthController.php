<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

/* HALAMAN LOGIN */
public function loginForm()
{
    return view('login');
}

/* PROSES LOGIN */
public function login(Request $request)
{

    if(Auth::attempt([
        'email'=>$request->email,
        'password'=>$request->password
    ]))
    {

        $user = Auth::user();

        /* CEK ROLE */

        if($user->role == 'admin'){
            return redirect('/admin');
        }

        if($user->role == 'officer'){
            return redirect('/officer');
        }

        /* kalau role kosong */
        return redirect('/');
    }

    return back()->with('error','Login gagal');

}

/* LOGOUT */
public function logout()
{
    Auth::logout();
    return redirect('/login');
}

}