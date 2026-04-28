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

        $queue = (clone $todayQuery)
            ->where('status', 'called')
            ->latest('called_at')
            ->first();

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

        return view('dashboard', compact(
            'setting',
            'queue',
            'teller',
            'administrasi',
            'pinjaman',
            'youtubeLinks'
        ));
    }

    public function data()
    {
        $todayQuery = Queue::with('service')
            ->whereDate('created_at', today());

        $main = (clone $todayQuery)
            ->where('status', 'called')
            ->latest('called_at')
            ->first();

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
            'teller'       => $teller ? ($teller->queue_number ?? $teller->id) : '000',
            'administrasi' => $administrasi ? ($administrasi->queue_number ?? $administrasi->id) : '000',
            'pinjaman'     => $pinjaman ? ($pinjaman->queue_number ?? $pinjaman->id) : '000',
        ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
          ->header('Pragma', 'no-cache')
          ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }
}