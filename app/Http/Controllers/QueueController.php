<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;
use App\Models\Service;
use App\Events\AntreanUpdate; // 🔥 Wajib di-import agar bisa mengirim sinyal ke Pusher

class QueueController extends Controller
{
    public function index()
    {
        return view('ambil_antrian');
    }

    public function store(Request $request)
    {
        $serviceId = $request->service_id;

        // Ambil service
        $service = Service::find($serviceId);

        if(!$service){
            return back();
        }

        // Hitung nomor terakhir berdasarkan service_id
        $last = Queue::where('service_id', $serviceId)->latest()->first();

        $number = 1;
        if($last){
            // Mengambil angka setelah prefix (misal T001 jadi 001) lalu ditambah 1
            $number = (int) substr($last->queue_number, 1) + 1;
        }

        // Prefix dari code (A, T, P, dll)
        $prefix = $service->code;
        $queueNumber = $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);

        // Simpan antrean baru
        Queue::create([
            'queue_number' => $queueNumber,
            'service_id'   => $serviceId,
            'status'       => 'waiting'
        ]);

        // 🔥 SINYAL REAL-TIME
        // Mengirim sinyal ke Pusher bahwa ada antrean baru yang masuk
        event(new AntreanUpdate());

        return back();
    }
}