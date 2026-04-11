<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;
use App\Models\Service;
use App\Events\AntreanUpdate;

class QueueController extends Controller
{
    // Menampilkan halaman ambil antrean
    public function index()
    {
        // Ambil semua layanan
        $services = Service::orderBy('id', 'asc')->get();
        return view('ambil_antrian', compact('services'));
    }

    // Menyimpan antrean yang dipilih
    public function store(Request $request)
    {
        // Validasi service_id yang diterima
        $request->validate([
            'service_id' => ['required', 'exists:services,id'], // Memastikan service_id valid
        ]);

        // Ambil service berdasarkan service_id
        $serviceId = $request->service_id;
        $service = Service::find($serviceId);

        // Jika service tidak ditemukan, kembalikan error
        if (!$service) {
            return back()->with('error', 'Layanan tidak ditemukan.');
        }

        // Cari antrean terakhir berdasarkan service_id untuk hari ini
        $last = Queue::where('service_id', $serviceId)
            ->whereDate('created_at', today()) // Pastikan hanya yang hari ini
            ->orderBy('id', 'desc')
            ->first();

        $number = 1;

        // Tentukan nomor antrean baru, increment berdasarkan antrean terakhir
        if ($last && !empty($last->queue_number)) {
            $lastNumber = (int) preg_replace('/[^0-9]/', '', $last->queue_number);
            $number = $lastNumber + 1;
        }

        // Ambil prefix dari layanan (misalnya 'T' untuk Teller)
        $prefix = strtoupper($service->code); // Pastikan di service ada kolom 'code' seperti 'T', 'P', dll
        $queueNumber = $prefix . str_pad($number, 3, '0', STR_PAD_LEFT); // Format antrean: T001, P002, dll

        // Simpan antrean baru ke database
        Queue::create([
            'queue_number' => $queueNumber,
            'service_id'   => $serviceId,
            'status'       => 'waiting', // Status awal adalah 'waiting'
        ]);

        // Kirim sinyal ke semua tampilan untuk memperbarui antrean
        event(new AntreanUpdate());

        // Kembali dengan pesan sukses
        return response()->json([
            'success' => true,
            'message' => 'Antrean berhasil diambil.',
        ]);
    }
}