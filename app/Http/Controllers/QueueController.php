<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;
use App\Models\Service;
use App\Events\AntreanUpdate;

class QueueController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('id', 'asc')->get();
        return view('ambil_antrian', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => ['required', 'exists:services,id'],
        ]);

        $serviceId = $request->service_id;
        $service = Service::find($serviceId);

        if (!$service) {
            return back()->with('error', 'Layanan tidak ditemukan.');
        }

        $last = Queue::where('service_id', $serviceId)
            ->whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        $number = 1;

        if ($last && !empty($last->queue_number)) {
            $lastNumber = (int) preg_replace('/[^0-9]/', '', $last->queue_number);
            $number = $lastNumber + 1;
        }

        $prefix = strtoupper($service->code);
        $queueNumber = $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);

        Queue::create([
            'queue_number' => $queueNumber,
            'service_id'   => $serviceId,
            'status'       => 'waiting',
        ]);

        // 🔥 FIX UTAMA DI SINI
        broadcast(new AntreanUpdate())->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'Antrean berhasil diambil.',
        ]);
    }
}