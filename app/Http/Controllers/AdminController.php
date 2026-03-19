<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;

class AdminController extends Controller
{

public function index()
{
    $totalUsers = \App\Models\User::count();

    $loket1 = \App\Models\Queue::where('loket_id',1)->count();
    $loket2 = \App\Models\Queue::where('loket_id',2)->count();
    $loket3 = \App\Models\Queue::where('loket_id',3)->count();
    $loket4 = \App\Models\Queue::where('loket_id',4)->count();

    return view('admin', compact(
        'totalUsers',
        'loket1',
        'loket2',
        'loket3',
        'loket4'
    ));
}
// DASHBOARD TV
public function dashboard()
{
    $setting = Setting::first();
    return view('dashboard', compact('setting'));
}

// USER
public function user()
{
    $users = User::all();
    return view('admin_user', compact('users'));
}

public function storeUser(Request $request)
{
    User::create([
        'name'=>$request->name,
        'email'=>$request->email,
        'password'=>bcrypt($request->password),
        'role'=>$request->role
    ]);

    return back();
}

public function deleteUser($id)
{
    $user = User::find($id);
    if ($user) {
        $user->delete();
    }
    return back();
}

// SETTING
public function setting()
{
    $setting = Setting::firstOrCreate([]);

    $queue = \App\Models\Queue::where('status','called')->latest()->first();

    return view('admin_setting', compact('setting','queue'));
}

public function updateSetting(Request $request)
{
    $setting = Setting::firstOrCreate([]);

    $setting->title = $request->title;
    $setting->address = $request->address;
    $setting->phone = $request->phone;
    $setting->youtube = $request->youtube;

    if ($request->hasFile('logo')) {
        $setting->logo = $request->file('logo')->store('public');
    }

    if ($request->hasFile('background')) {
        $setting->background = $request->file('background')->store('public');
    }

    $setting->save();

    return back();
}

}