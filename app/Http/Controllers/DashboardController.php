<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Setting;

class DashboardController extends Controller
{
    public function index()
    {
        $setting = Setting::first();

        $todayQuery = Queue::whereDate('created_at', today());

        $latestCalled = (clone $todayQuery)
            ->where('status', 'called')
            ->whereNotNull('called_at')
            ->latest('called_at')
            ->first();

        $latestDone = (clone $todayQuery)
            ->where('status', 'done')
            ->whereNotNull('done_at')
            ->latest('done_at')
            ->first();

        $queue = null;

        if ($latestCalled) {
            if (!$latestDone || $latestCalled->called_at >= $latestDone->done_at) {
                $queue = $latestCalled;
            }
        }

        $teller = (clone $todayQuery)
            ->where('service_id', 1)
            ->whereNotNull('called_at')
            ->latest('called_at')
            ->first();

        $pinjaman = (clone $todayQuery)
            ->where('service_id', 2)
            ->whereNotNull('called_at')
            ->latest('called_at')
            ->first();

        $administrasi = (clone $todayQuery)
            ->where('service_id', 3)
            ->whereNotNull('called_at')
            ->latest('called_at')
            ->first();

        $youtubeLinks = [];

        if ($setting && !empty($setting->youtube)) {
            $youtubeLinks = preg_split('/\r\n|\r|\n/', $setting->youtube);

            $youtubeLinks = array_filter($youtubeLinks, function ($link) {
                return trim($link) !== '';
            });

            $youtubeLinks = array_map('trim', $youtubeLinks);
            $youtubeLinks = array_values($youtubeLinks);
        }

        $firstYoutube = null;

        if (!empty($youtubeLinks)) {
            $firstYoutube = $youtubeLinks[0];
        }

        return view('dashboard', compact(
            'setting',
            'queue',
            'teller',
            'administrasi',
            'pinjaman',
            'youtubeLinks',
            'firstYoutube'
        ));
    }

    public function data()
    {
        $todayQuery = Queue::with('service')
            ->whereDate('created_at', today());

        $latestCalled = (clone $todayQuery)
            ->where('status', 'called')
            ->whereNotNull('called_at')
            ->latest('called_at')
            ->first();

        $latestDone = (clone $todayQuery)
            ->where('status', 'done')
            ->whereNotNull('done_at')
            ->latest('done_at')
            ->first();

        $main = null;

        if ($latestCalled) {
            if (!$latestDone || $latestCalled->called_at >= $latestDone->done_at) {
                $main = $latestCalled;
            }
        }

        $teller = (clone $todayQuery)
            ->where('service_id', 1)
            ->whereNotNull('called_at')
            ->latest('called_at')
            ->first();

        $administrasi = (clone $todayQuery)
            ->where('service_id', 3)
            ->whereNotNull('called_at')
            ->latest('called_at')
            ->first();

        $pinjaman = (clone $todayQuery)
            ->where('service_id', 2)
            ->whereNotNull('called_at')
            ->latest('called_at')
            ->first();

        return response()->json([
            'main_number'  => $main ? ($main->queue_number ?? $main->id) : '000',
            'main_service' => $main ? ($main->service->name ?? '-') : '-',
            'main_officer' => $main ? ($main->officer_name ?? '-') : '-',

            'teller' => $teller ? ($teller->queue_number ?? $teller->id) : '000',
            'teller_officer' => $teller ? ($teller->officer_name ?? '-') : '-',

            'administrasi' => $administrasi ? ($administrasi->queue_number ?? $administrasi->id) : '000',
            'administrasi_officer' => $administrasi ? ($administrasi->officer_name ?? '-') : '-',

            'pinjaman' => $pinjaman ? ($pinjaman->queue_number ?? $pinjaman->id) : '000',
            'pinjaman_officer' => $pinjaman ? ($pinjaman->officer_name ?? '-') : '-',
        ])
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->header('Pragma', 'no-cache')
        ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }
}