<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Events\AntreanUpdate; // 🔥 Wajib di-import
use Illuminate\Http\Request;

class OfficerController extends Controller
{
    public function index()
    {
        return view('officer');
    }

    // 🔥 DATA JSON UNTUK AJAX
    public function data()
    {
        $query = Queue::with('service')->whereDate('created_at', today());

        return response()->json([
            'queues' => (clone $query)->orderBy('id', 'desc')->get(), 
            'total' => (clone $query)->count(),
            'current' => (clone $query)
                ->where('status','called')
                ->latest('called_at')
                ->first(),
            'next' => (clone $query)
                ->where('status','waiting')
                ->orderBy('id', 'asc')
                ->first(),
            'remaining' => (clone $query)
                ->where('status','waiting')
                ->count(),
        ]);
    }

    // 🔥 PANGGIL ANTREAN
    public function call($id)
    {
        // 1. Selesaikan antrean yang berstatus 'called' sebelumnya
        Queue::where('status','called')->update([
            'status' => 'done',
            'done_at' => now()
        ]);

        // 2. Update antrean terpilih menjadi 'called'
        $queue = Queue::find($id);
        if ($queue && $queue->status == 'waiting') {
            $queue->status = 'called';
            $queue->called_at = now();
            $queue->save();
        }

        // 🚀 KIRIM SINYAL REALTIME KE SEMUA LAYAR
        event(new AntreanUpdate());

        return response()->json(['success' => true]);
    }

    // 🔥 SELESAIKAN ANTREAN
    public function done($id)
    {
        $queue = Queue::find($id);

        if ($queue && $queue->status != 'done') {
            $queue->status = 'done';
            $queue->done_at = now();
            $queue->save();
        }

        // 🚀 KIRIM SINYAL REALTIME KE SEMUA LAYAR
        event(new AntreanUpdate());

        return response()->json(['success' => true]);
    }
}