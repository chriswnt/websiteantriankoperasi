<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Setting;

class DashboardController extends Controller
{

// LOAD AWAL
public function index()
{
    $setting = Setting::first();

    $queue = Queue::where('status','called')->latest()->first();

    $loket1 = Queue::where('loket_id',1)->where('status','called')->latest()->first();
    $loket2 = Queue::where('loket_id',2)->where('status','called')->latest()->first();
    $loket3 = Queue::where('loket_id',3)->where('status','called')->latest()->first();
    $loket4 = Queue::where('loket_id',4)->where('status','called')->latest()->first();

    return view('dashboard', compact(
        'setting',
        'queue',
        'loket1',
        'loket2',
        'loket3',
        'loket4'
    ));
}


// REALTIME DATA
public function data()
{
    $queue = Queue::where('status','called')->latest()->first();

    $loket1 = Queue::where('loket_id',1)->where('status','called')->latest()->first();
    $loket2 = Queue::where('loket_id',2)->where('status','called')->latest()->first();
    $loket3 = Queue::where('loket_id',3)->where('status','called')->latest()->first();
    $loket4 = Queue::where('loket_id',4)->where('status','called')->latest()->first();

    return response()->json([
        'queue_number' => $queue->queue_number ?? null,
        'loket' => $queue->loket_id ?? null,
        'loket1' => $loket1->queue_number ?? null,
        'loket2' => $loket2->queue_number ?? null,
        'loket3' => $loket3->queue_number ?? null,
        'loket4' => $loket4->queue_number ?? null,
    ]);
}

}