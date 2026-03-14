<?php

namespace App\Http\Controllers;

use App\Models\Queue;

class OfficerController extends Controller
{
    public function index()
    {
        $current1 = Queue::where('status','called')
                         ->where('loket', 1)
                         ->first();

        $next1 = Queue::where('status','waiting')
                      ->whereHas('service', function($q) {
                          $q->where('code', 'T');
                      })
                      ->first();

        $current2 = Queue::where('status','called')
                         ->where('loket', 2)
                         ->first();

        $next2 = Queue::where('status','waiting')
                      ->whereHas('service', function($q) {
                          $q->where('code', 'T');
                      })
                      ->skip(1)
                      ->first();

        return view('officer', compact('current1', 'next1', 'current2', 'next2'));
    }

    public function data()
    {
        $waiting = Queue::where('status', 'waiting')
                        ->whereHas('service', function($q) {
                            $q->where('code', 'T');
                        })
                        ->orderBy('created_at')
                        ->get(['id', 'number', 'created_at']);

        $current1 = Queue::where('status','called')
                         ->where('loket', 1)
                         ->first(['id','number','started_at']);

        $current2 = Queue::where('status','called')
                         ->where('loket', 2)
                         ->first(['id','number','started_at']);

        return response()->json([
            'waiting' => $waiting,
            'current1' => $current1,
            'current2' => $current2,
        ]);
    }

    public function start($id, $loket)
    {
        $queue = Queue::where('id', $id)
                      ->where('status', 'waiting')
                      ->first();

        if ($queue) {
            $queue->update([
                'status' => 'called',
                'loket' => $loket,
                'started_at' => now(),
            ]);
        }

        return back();
    }

    public function finish($id)
    {
        $queue = Queue::where('id', $id)
                      ->where('status', 'called')
                      ->first();

        if ($queue) {
            $queue->update([
                'status' => 'done',
                'finished_at' => now(),
            ]);
        }

        return back();
    }
}