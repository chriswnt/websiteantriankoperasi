<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;
use App\Models\Queue;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();

        $officers = User::where('role', 'officer')
            ->with('serviceRelation')
            ->get();

        return view('admin', compact('totalUsers', 'officers'));
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
        $totalUsers = User::count();

        return view('admin_user', compact('users', 'services', 'totalUsers'));
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

    public function getDashboardStats()
    {
        try {
            $officers = User::where('role', 'officer')->get();
            $stats = [];
            $now = now();

            foreach ($officers as $officer) {
                $isOnline = false;

                if (!empty($officer->last_seen)) {
                    $lastSeen = $officer->last_seen instanceof Carbon
                        ? $officer->last_seen
                        : Carbon::parse($officer->last_seen);

                    $isOnline = $lastSeen->diffInMinutes($now) <= 2;
                }

                if (!$officer->service_id) {
                    $stats['officer_' . $officer->id] = [
                        'total_done' => 0,
                        'avg_time' => '-',
                        'is_online' => $isOnline
                    ];
                    continue;
                }

                $completedQueues = Queue::whereDate('created_at', today())
                    ->where('service_id', $officer->service_id)
                    ->where('status', 'done')
                    ->get();

                $totalDone = $completedQueues->count();

                $totalSeconds = 0;
                $validAverageCount = 0;

                foreach ($completedQueues as $q) {
                    if (!empty($q->called_at) && !empty($q->done_at)) {
                        $start = Carbon::parse($q->called_at);
                        $end = Carbon::parse($q->done_at);

                        $totalSeconds += abs($end->diffInSeconds($start));
                        $validAverageCount++;
                    }
                }

                $avgSeconds = $validAverageCount > 0 ? floor($totalSeconds / $validAverageCount) : 0;
                $m = floor($avgSeconds / 60);
                $s = $avgSeconds % 60;

                if ($validAverageCount == 0) {
                    $formatWaktu = "-";
                } elseif ($m > 0) {
                    $formatWaktu = "{$m} Menit {$s} Detik";
                } else {
                    $formatWaktu = "{$s} Detik";
                }

                $stats['officer_' . $officer->id] = [
                    'total_done' => $totalDone,
                    'avg_time' => $formatWaktu,
                    'is_online' => $isOnline
                ];
            }

            return response()->json([
                'status' => 'success',
                'total_users' => User::count(),
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}