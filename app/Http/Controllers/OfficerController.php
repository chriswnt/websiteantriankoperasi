<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;

class OfficerController extends Controller
{

public function index()
{
    $queues = Queue::orderBy('id','asc')->get();

    $total = Queue::count();

    $current = Queue::where('status','called')->latest()->first();

    $next = Queue::where('status','waiting')->orderBy('id')->first();

    $remaining = Queue::where('status','waiting')->count();

    $loket1 = Queue::where('loket_id',1)->where('status','called')->latest()->first();
    $loket2 = Queue::where('loket_id',2)->where('status','called')->latest()->first();
    $loket3 = Queue::where('loket_id',3)->where('status','called')->latest()->first();
    $loket4 = Queue::where('loket_id',4)->where('status','called')->latest()->first();

    return view('officer', compact(
        'queues',
        'total',
        'current',
        'next',
        'remaining',
        'loket1',
        'loket2',
        'loket3',
        'loket4'
    ));
}

// PANGGIL
public function call($id)
{
    $queue = Queue::find($id);

    if($queue){
        $queue->status = 'called';
        $queue->save();
    }

    return redirect('/officer');
}

// SELESAI
public function done($id)
{
    $queue = Queue::find($id);

    if($queue){
        $queue->status = 'done';
        $queue->save();
    }

    return redirect('/officer');
}

}