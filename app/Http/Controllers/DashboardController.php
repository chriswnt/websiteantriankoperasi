<?php

namespace App\Http\Controllers;

use App\Models\Queue;

class DashboardController extends Controller
{
    public function index()
    {
        $tellerPetugas1 = Queue::where('status', 'called')
                              ->where('loket', 1)
                              ->whereHas('service', function($q) {
                                  $q->where('code', 'T');
                              })
                              ->latest()
                              ->take(2)
                              ->get();

        $tellerPetugas2 = Queue::where('status', 'called')
                              ->where('loket', 2)
                              ->whereHas('service', function($q) {
                                  $q->where('code', 'T');
                              })
                              ->latest()
                              ->take(2)
                              ->get();

        $pinjamanQueues = Queue::where('status', '!=', 'done')
                              ->whereHas('service', function($q) {
                                  $q->where('code', 'P');
                              })
                              ->latest()
                              ->take(1)
                              ->get();

        $adminQueues = Queue::where('status', '!=', 'done')
                           ->whereHas('service', function($q) {
                               $q->where('code', 'A');
                           })
                           ->latest()
                           ->take(1)
                           ->get();

        return view('dashboard', compact('tellerPetugas1', 'tellerPetugas2', 'pinjamanQueues', 'adminQueues'));
    }
    public function data()
    {
        $tellerPetugas1 = Queue::where('status', 'called')
                              ->where('loket', 1)
                              ->whereHas('service', function($q) {
                                  $q->where('code', 'T');
                              })
                              ->latest()
                              ->take(2)
                              ->get();

        $tellerPetugas2 = Queue::where('status', 'called')
                              ->where('loket', 2)
                              ->whereHas('service', function($q) {
                                  $q->where('code', 'T');
                              })
                              ->latest()
                              ->take(2)
                              ->get();

        $pinjamanQueues = Queue::where('status', '!=', 'done')
                              ->whereHas('service', function($q) {
                                  $q->where('code', 'P');
                              })
                              ->latest()
                              ->take(1)
                              ->get();

        $adminQueues = Queue::where('status', '!=', 'done')
                           ->whereHas('service', function($q) {
                               $q->where('code', 'A');
                           })
                           ->latest()
                           ->take(1)
                           ->get();

        return response()->json([
            'teller_petugas1' => $tellerPetugas1,
            'teller_petugas2' => $tellerPetugas2,
            'pinjaman' => $pinjamanQueues,
            'admin' => $adminQueues
        ]);
    }
}