<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /*
     * Sesuaikan ID service dengan database kamu.
     * Dari kode sebelumnya:
     * Teller        = 1
     * Pinjaman      = 2
     * Administrasi  = 3
     */
    private const SERVICE_TELLER_ID = 1;
    private const SERVICE_PINJAMAN_ID = 2;
    private const SERVICE_ADMINISTRASI_ID = 3;

    /*
     * Status yang dianggap sedang dipanggil / sedang diproses.
     * Kalau di database kamu hanya pakai "called", boleh biarkan tetap seperti ini.
     */
    private const CALLED_STATUSES = [
        'called',
        'calling',
        'call',
        'panggil',
        'dipanggil',
        'process',
        'processing',
        'diproses',
    ];

    /*
     * Status yang dianggap selesai.
     */
    private const DONE_STATUSES = [
        'done',
        'selesai',
        'completed',
        'complete',
    ];

    private function todayQueueQuery($serviceId = null): Builder
    {
        $query = Queue::with('service')
            ->whereDate('created_at', today());

        if ($serviceId !== null) {
            $query->where('service_id', $serviceId);
        }

        return $query;
    }

    private function normalizeStatuses(array $statuses): array
    {
        return array_values(array_unique(array_map('strtolower', $statuses)));
    }

    private function whereStatusIn(Builder $query, array $statuses): Builder
    {
        $statuses = $this->normalizeStatuses($statuses);

        $placeholders = implode(',', array_fill(0, count($statuses), '?'));

        return $query->whereRaw("LOWER(status) IN ($placeholders)", $statuses);
    }

    private function isCalledQueue($queue): bool
    {
        if (!$queue || empty($queue->status)) {
            return false;
        }

        return in_array(
            strtolower($queue->status),
            $this->normalizeStatuses(self::CALLED_STATUSES),
            true
        );
    }

    private function isDoneQueue($queue): bool
    {
        if (!$queue || empty($queue->status)) {
            return false;
        }

        return in_array(
            strtolower($queue->status),
            $this->normalizeStatuses(self::DONE_STATUSES),
            true
        );
    }

    private function formatDateTime($value): ?string
    {
        if (!$value) {
            return null;
        }

        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    private function queueNumber($queue): string
    {
        if (!$queue) {
            return '000';
        }

        return (string) ($queue->queue_number ?? $queue->id ?? '000');
    }

    private function serviceName($queue): string
    {
        if (!$queue) {
            return '-';
        }

        return $queue->service->name ?? '-';
    }

    private function officerName($queue): string
    {
        if (!$queue) {
            return '-';
        }

        return $queue->officer_name ?? '-';
    }

    private function calledTimeForSound($queue): ?string
    {
        if (!$this->isCalledQueue($queue)) {
            return null;
        }

        return $this->formatDateTime(
            $queue->called_at
                ?? $queue->updated_at
                ?? $queue->created_at
        );
    }

    /*
     * INI KHUSUS UNTUK BOX BESAR.
     *
     * Logika:
     * - ambil event antrean terakhir hari ini, baik called maupun done;
     * - kalau event terakhir masih called, tampilkan di box besar;
     * - kalau event terakhir done, box besar harus reset;
     * - jangan ambil antrean called lama dari layanan lain.
     */
    private function latestMainEvent()
    {
        $doneStatuses = $this->normalizeStatuses(self::DONE_STATUSES);
        $calledStatuses = $this->normalizeStatuses(self::CALLED_STATUSES);

        $donePlaceholders = implode(',', array_fill(0, count($doneStatuses), '?'));
        $calledPlaceholders = implode(',', array_fill(0, count($calledStatuses), '?'));

        $query = $this->todayQueueQuery();

        return $this->whereStatusIn(
            $query,
            array_merge(self::CALLED_STATUSES, self::DONE_STATUSES)
        )
            ->orderByRaw("
                CASE
                    WHEN LOWER(status) IN ($donePlaceholders)
                        THEN COALESCE(done_at, updated_at, called_at, created_at)

                    WHEN LOWER(status) IN ($calledPlaceholders)
                        THEN COALESCE(called_at, updated_at, created_at)

                    ELSE COALESCE(updated_at, created_at)
                END DESC
            ", array_merge($doneStatuses, $calledStatuses))
            ->orderByDesc('id')
            ->first();
    }

    /*
     * Data final untuk box besar.
     *
     * Kalau event terakhir adalah DONE, return null supaya dashboard tampil 000.
     */
    private function mainQueue()
    {
        $latestEvent = $this->latestMainEvent();

        if ($this->isCalledQueue($latestEvent)) {
            return $latestEvent;
        }

        return null;
    }

    /*
     * INI UNTUK BOX KECIL PER LAYANAN.
     *
     * Box kecil masih boleh menampilkan antrean terakhir per layanan.
     * Prioritas:
     * 1. antrean yang sedang called/proses;
     * 2. kalau tidak ada, antrean done terakhir.
     */
    private function latestServiceActivity($serviceId = null)
    {
        $calledQuery = $this->todayQueueQuery($serviceId);

        $called = $this->whereStatusIn($calledQuery, self::CALLED_STATUSES)
            ->orderByRaw("COALESCE(called_at, updated_at, created_at) DESC")
            ->orderByDesc('id')
            ->first();

        if ($called) {
            return $called;
        }

        $doneQuery = $this->todayQueueQuery($serviceId);

        return $this->whereStatusIn($doneQuery, self::DONE_STATUSES)
            ->orderByRaw("COALESCE(done_at, updated_at, called_at, created_at) DESC")
            ->orderByDesc('id')
            ->first();
    }

    public function index()
    {
        $setting = Setting::first();

        /*
         * Box besar pakai mainQueue(), bukan latestServiceActivity().
         */
        $queue = $this->mainQueue();

        /*
         * Box kecil tetap pakai data per service.
         */
        $teller = $this->latestServiceActivity(self::SERVICE_TELLER_ID);
        $pinjaman = $this->latestServiceActivity(self::SERVICE_PINJAMAN_ID);
        $administrasi = $this->latestServiceActivity(self::SERVICE_ADMINISTRASI_ID);

        $youtubeLinks = [];

        if ($setting && !empty($setting->youtube)) {
            $youtubeLinks = preg_split('/\r\n|\r|\n/', $setting->youtube);
            $youtubeLinks = array_values(array_filter(array_map('trim', $youtubeLinks)));
        }

        $firstYoutube = $youtubeLinks[0] ?? null;

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
        /*
         * Box besar.
         * Kalau antrean terakhir sudah done, $main akan null dan dashboard harus reset.
         */
        $main = $this->mainQueue();

        /*
         * Box kecil per layanan.
         */
        $teller = $this->latestServiceActivity(self::SERVICE_TELLER_ID);
        $administrasi = $this->latestServiceActivity(self::SERVICE_ADMINISTRASI_ID);
        $pinjaman = $this->latestServiceActivity(self::SERVICE_PINJAMAN_ID);

        $mainCalledAt = $this->calledTimeForSound($main);

        return response()->json([
            /*
             * Data box besar.
             */
            'main_number' => $this->queueNumber($main),
            'main_service' => $this->serviceName($main),
            'main_officer' => $this->officerName($main),
            'main_status' => $main ? $main->status : '-',
            'main_is_called' => $this->isCalledQueue($main),
            'main_called_at' => $mainCalledAt,

            /*
             * Key ini lebih aman untuk trigger suara.
             * Kalau null, berarti box besar sedang reset.
             */
            'main_call_key' => $main && $mainCalledAt
                ? $main->id . '|' . $mainCalledAt
                : null,

            /*
             * Data box Teller.
             */
            'teller' => $this->queueNumber($teller),
            'teller_officer' => $this->officerName($teller),
            'teller_status' => $teller ? $teller->status : '-',
            'teller_is_called' => $this->isCalledQueue($teller),

            /*
             * Data box Administrasi.
             */
            'administrasi' => $this->queueNumber($administrasi),
            'administrasi_officer' => $this->officerName($administrasi),
            'administrasi_status' => $administrasi ? $administrasi->status : '-',
            'administrasi_is_called' => $this->isCalledQueue($administrasi),

            /*
             * Data box Pinjaman.
             */
            'pinjaman' => $this->queueNumber($pinjaman),
            'pinjaman_officer' => $this->officerName($pinjaman),
            'pinjaman_status' => $pinjaman ? $pinjaman->status : '-',
            'pinjaman_is_called' => $this->isCalledQueue($pinjaman),
        ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }

    public function queueStatus()
    {
        $teller = $this->latestServiceActivity(self::SERVICE_TELLER_ID);
        $administrasi = $this->latestServiceActivity(self::SERVICE_ADMINISTRASI_ID);
        $pinjaman = $this->latestServiceActivity(self::SERVICE_PINJAMAN_ID);

        return response()->json([
            'teller' => [
                'number' => $this->queueNumber($teller),
                'officer' => $this->officerName($teller),
                'status' => $teller ? $teller->status : '-',
                'is_called' => $this->isCalledQueue($teller),
            ],

            'administrasi' => [
                'number' => $this->queueNumber($administrasi),
                'officer' => $this->officerName($administrasi),
                'status' => $administrasi ? $administrasi->status : '-',
                'is_called' => $this->isCalledQueue($administrasi),
            ],

            'pinjaman' => [
                'number' => $this->queueNumber($pinjaman),
                'officer' => $this->officerName($pinjaman),
                'status' => $pinjaman ? $pinjaman->status : '-',
                'is_called' => $this->isCalledQueue($pinjaman),
            ],
        ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }
}