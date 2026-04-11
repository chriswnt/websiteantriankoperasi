<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;
use App\Models\Queue;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();

        $loket1 = Queue::where('loket_id', 1)->count();
        $loket2 = Queue::where('loket_id', 2)->count();
        $loket3 = Queue::where('loket_id', 3)->count();
        $loket4 = Queue::where('loket_id', 4)->count();

        return view('admin', compact(
            'totalUsers',
            'loket1',
            'loket2',
            'loket3',
            'loket4'
        ));
    }

    public function dashboard()
    {
        $setting = Setting::first();
        return view('dashboard', compact('setting'));
    }

    public function user()
    {
        $users = User::with('serviceRelation')->orderBy('id', 'desc')->get();
        $services = Service::orderBy('id', 'asc')->get();

        return view('admin_user', compact('users', 'services'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'min:6'],
            'role' => ['required', 'in:admin,officer'],
            'service_id' => ['nullable', 'exists:services,id'],
        ]);

        if ($validated['role'] === 'officer' && empty($validated['service_id'])) {
            return back()
                ->withErrors(['service_id' => 'Officer wajib memilih layanan.'])
                ->withInput();
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
            'service_id' => $validated['role'] === 'officer'
                ? $validated['service_id']
                : null,
        ]);

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if (Auth::id() === $user->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun yang sedang digunakan.');
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }

    public function setting()
    {
        $setting = Setting::firstOrCreate([]);

        $queue = Queue::whereDate('created_at', today())
            ->where('status', 'called')
            ->latest('called_at')
            ->first();

        return view('admin_setting', compact('setting', 'queue'));
    }

    public function updateSetting(Request $request)
    {
        $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'youtube' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'background' => ['nullable', 'image', 'max:4096'],
        ]);

        $setting = Setting::firstOrCreate([]);

        $setting->title = $request->title;
        $setting->address = $request->address;
        $setting->phone = $request->phone;
        $setting->youtube = $request->youtube;

        if ($request->hasFile('logo')) {
            $setting->logo = $request->file('logo')->store('settings', 'public');
        }

        if ($request->hasFile('background')) {
            $setting->background = $request->file('background')->store('settings', 'public');
        }

        $setting->save();

        return back()->with('success', 'Tampilan berhasil diperbarui.');
    }
}