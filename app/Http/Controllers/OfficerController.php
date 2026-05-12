<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Events\AntreanUpdate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OfficerController extends Controller
{
    public function index()
    {
        return view('officer');
    }

    private function getServiceId()
    {
        return Auth::user()->service_id;
    }

    public function data()
    {
        $serviceId = $this->getServiceId();

        if (!$serviceId) {
            return response()->json([
                'queues' => [],
                'total' => 0,
                'current' => null,
                'currents' => [],
                'next' => null,
                'remaining' => 0,
                'message' => 'Service user belum diatur.'
            ], 403);
        }

        $query = Queue::with('service')
            ->where('service_id', $serviceId)
            ->whereDate('created_at', today());

        $queues = (clone $query)
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($queue) {
                $queue->waktu_antri = $queue->created_at
                    ? Carbon::parse($queue->created_at)->timezone('Asia/Jakarta')->format('H:i:s')
                    : '-';

                $queue->waktu_diproses = $queue->called_at
                    ? Carbon::parse($queue->called_at)->timezone('Asia/Jakarta')->format('H:i:s')
                    : '-';

                $queue->waktu_selesai = $queue->done_at
                    ? Carbon::parse($queue->done_at)->timezone('Asia/Jakarta')->format('H:i:s')
                    : '-';

                return $queue;
            });

        $current = (clone $query)
            ->where('status', 'called')
            ->where('officer_id', Auth::id())
            ->latest('called_at')
            ->first();

        $currents = (clone $query)
            ->where('status', 'called')
            ->orderBy('called_at', 'asc')
            ->get();

        $next = (clone $query)
            ->where('status', 'waiting')
            ->orderBy('id', 'asc')
            ->first();

        return response()->json([
            'queues' => $queues,
            'total' => (clone $query)->count(),
            'current' => $current,
            'currents' => $currents,
            'next' => $next,
            'remaining' => (clone $query)->where('status', 'waiting')->count(),
        ]);
    }

    public function call($id)
    {
        $serviceId = $this->getServiceId();

        if (!$serviceId) {
            return response()->json([
                'success' => false,
                'message' => 'Service user belum diatur.'
            ], 403);
        }

        try {
            $result = DB::transaction(function () use ($id, $serviceId) {
                $activeQueue = Queue::whereDate('created_at', today())
                    ->where('officer_id', Auth::id())
                    ->where('status', 'called')
                    ->lockForUpdate()
                    ->first();

                if ($activeQueue && (int) $activeQueue->id === (int) $id) {
                    return [
                        'success' => true,
                        'message' => 'Antrean sudah dipanggil.'
                    ];
                }

                if ($activeQueue) {
                    return [
                        'success' => false,
                        'message' => 'Selesaikan antrean ' . ($activeQueue->queue_number ?? $activeQueue->id) . ' terlebih dahulu.',
                        'code' => 422
                    ];
                }

                $queue = Queue::whereDate('created_at', today())
                    ->where('service_id', $serviceId)
                    ->where('id', $id)
                    ->lockForUpdate()
                    ->first();

                if (!$queue) {
                    return [
                        'success' => false,
                        'message' => 'Antrean tidak ditemukan.',
                        'code' => 404
                    ];
                }

                if ($queue->status === 'called' && (int) $queue->officer_id === (int) Auth::id()) {
                    return [
                        'success' => true,
                        'message' => 'Antrean sudah dipanggil.'
                    ];
                }

                if ($queue->status === 'called') {
                    return [
                        'success' => false,
                        'message' => 'Antrean sudah dipanggil officer lain.',
                        'code' => 422
                    ];
                }

                if ($queue->status !== 'waiting') {
                    return [
                        'success' => false,
                        'message' => 'Antrean ini sudah tidak bisa dipanggil.',
                        'code' => 422
                    ];
                }

                $queue->status = 'called';
                $queue->called_at = now();
                $queue->done_at = null;
                $queue->officer_id = Auth::id();
                $queue->officer_name = Auth::user()->name;
                $queue->save();

                return [
                    'success' => true,
                    'message' => 'Antrean berhasil dipanggil.'
                ];
            });

            if ($result['success']) {
                broadcast(new AntreanUpdate());

                return response()->json([
                    'success' => true,
                    'message' => $result['message']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], $result['code'] ?? 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function done($id)
    {
        $serviceId = $this->getServiceId();

        if (!$serviceId) {
            return response()->json([
                'success' => false,
                'message' => 'Service user belum diatur.'
            ], 403);
        }

        $queue = Queue::whereDate('created_at', today())
            ->where('service_id', $serviceId)
            ->where('id', $id)
            ->where('officer_id', Auth::id())
            ->where('status', 'called')
            ->first();

        if (!$queue) {
            return response()->json([
                'success' => false,
                'message' => 'Antrean tidak ditemukan, sudah selesai, atau bukan milik officer ini.'
            ], 404);
        }

        $queue->status = 'done';
        $queue->done_at = now();
        $queue->save();

        broadcast(new AntreanUpdate());

        return response()->json([
            'success' => true,
            'message' => 'Antrean berhasil diselesaikan.'
        ]);
    }

    public function reset()
    {
        $serviceId = $this->getServiceId();

        if (!$serviceId) {
            return response()->json([
                'success' => false,
                'message' => 'Service user belum diatur.'
            ], 403);
        }

        Queue::whereDate('created_at', today())
            ->where('service_id', $serviceId)
            ->delete();

        broadcast(new AntreanUpdate());

        return response()->json([
            'success' => true,
            'message' => 'Antrean layanan ini berhasil direset.'
        ]);
    }
}