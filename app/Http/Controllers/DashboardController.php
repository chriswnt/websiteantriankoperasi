<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Setting;

class DashboardController extends Controller
{
    public function index()
    {
        $setting = Setting::first();

        // Cari yang sedang dipanggil saat ini
        $queue = Queue::where('status', 'called')->latest('called_at')->first();

        // Cari yang paling terakhir dipanggil per layanan (termasuk yang sudah 'done' agar layar tidak kosong)
        $teller = Queue::where('service_id', 1)->whereNotNull('called_at')->latest('called_at')->first();
        $pinjaman = Queue::where('service_id', 2)->whereNotNull('called_at')->latest('called_at')->first();
        $administrasi = Queue::where('service_id', 3)->whereNotNull('called_at')->latest('called_at')->first();

        return view('dashboard', compact(
            'setting',
            'queue',
            'teller',
            'administrasi',
            'pinjaman'
        ));
    }

    // 🔥 REALTIME (Ganti fungsi data yang ini ya)
    public function data()
    {
        // Tambahkan with('service') agar nama layanan ikut terbawa
        $main = Queue::with('service')->where('status', 'called')->latest('called_at')->first();

        return response()->json([
            // Kirim nomor dan nama layanan
            'main_number'  => $main ? ($main->queue_number ?? $main->id) : '000',
            'main_service' => $main ? ($main->service->name ?? '-') : '-',

            'teller' => optional(
                Queue::where('service_id', 1)->whereNotNull('called_at')->latest('called_at')->first()
            )->queue_number ?? '-',

            'administrasi' => optional(
                Queue::where('service_id', 3)->whereNotNull('called_at')->latest('called_at')->first()
            )->queue_number ?? '-',

            'pinjaman' => optional(
                Queue::where('service_id', 2)->whereNotNull('called_at')->latest('called_at')->first()
            )->queue_number ?? '-',
        ]);
    }       
}