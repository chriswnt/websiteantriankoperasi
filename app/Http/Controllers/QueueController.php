<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;
use App\Models\Service;

class QueueController extends Controller
{

    public function index()
    {
        $services = Service::all();

        return view('ambil_antrian', compact('services'));
    }

   public function store(Request $request)
{

$service = Service::find($request->service_id);

$lastQueue = Queue::where('service_id',$service->id)
        ->latest()
        ->first();

$number = $lastQueue
        ? intval(substr($lastQueue->queue_number,1)) + 1
        : 1;

$queueNumber = $service->code . str_pad($number,3,'0',STR_PAD_LEFT);

Queue::create([
'service_id'=>$service->id,
'queue_number'=>$queueNumber,
'status'=>'waiting'
]);

return redirect()->back()->with('number',$queueNumber);

}

}   