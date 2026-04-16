<?php 

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Events\AntreanUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfficerController extends Controller
{
    public function index()
    {
        return view('officer');
    }

    /**
     * Ambil service_id langsung dari user yang login
     */
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
                'next' => null,
                'remaining' => 0,
                'message' => 'Service user belum diatur.'
            ], 403);
        }

        $query = Queue::with('service')
            ->where('service_id', $serviceId)
            ->whereDate('created_at', today());

        // FIX: Mapping data untuk memformat zona waktu langsung di backend (Asia/Jakarta)
        $queues = (clone $query)->orderBy('id', 'desc')->get()->map(function ($queue) {
            $queue->waktu_antri = $queue->created_at ? \Carbon\Carbon::parse($queue->created_at)->timezone('Asia/Jakarta')->format('H:i:s') : '-';
            $queue->waktu_diproses = $queue->called_at ? \Carbon\Carbon::parse($queue->called_at)->timezone('Asia/Jakarta')->format('H:i:s') : '-';
            $queue->waktu_selesai = $queue->done_at ? \Carbon\Carbon::parse($queue->done_at)->timezone('Asia/Jakarta')->format('H:i:s') : '-';
            return $queue;
        });

        return response()->json([
            'queues' => $queues,
            'total' => (clone $query)->count(),
            'current' => (clone $query)
                ->where('status', 'called')
                ->latest('called_at')
                ->first(),
            'next' => (clone $query)
                ->where('status', 'waiting')
                ->orderBy('id', 'asc')
                ->first(),
            'remaining' => (clone $query)
                ->where('status', 'waiting')
                ->count(),
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

        Queue::whereDate('created_at', today())
            ->where('service_id', $serviceId)
            ->where('status', 'called')
            ->update([
                'status' => 'done',
                'done_at' => now()
            ]);

        $queue = Queue::whereDate('created_at', today())
            ->where('service_id', $serviceId)
            ->where('id', $id)
            ->first();

        if ($queue && $queue->status === 'waiting') {
            $queue->status = 'called';
            $queue->called_at = now();
            $queue->done_at = null;
            $queue->save();
        }

        event(new AntreanUpdate());

        return response()->json(['success' => true]);
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
            ->first();

        if ($queue && $queue->status !== 'done') {
            $queue->status = 'done';
            $queue->done_at = now();
            $queue->save();
        }

        event(new AntreanUpdate());

        return response()->json(['success' => true]);
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

        event(new AntreanUpdate());

        return response()->json([
            'success' => true,
            'message' => 'Antrean layanan ini berhasil direset.'
        ]);
    }
}