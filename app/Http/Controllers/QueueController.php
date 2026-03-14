<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;
use App\Models\Service;

class QueueController extends Controller
{

   public function ambil()
   {
       $services = Service::all();
       return view('ambil_antrian', compact('services'));
   }

   public function generate(Request $request)
{

    $service = Service::where('code',$request->service_code)->first();

    if(!$service){
        return back()->with('error','Service tidak ditemukan');
    }

    $last = Queue::where('service_id',$service->id)->count() + 1;

    $number = $service->code . str_pad($last,3,'0',STR_PAD_LEFT);

    Queue::create([
        'number'=>$number,
        'service_id'=>$service->id,
        'status'=>'waiting'
    ]);

    return back()->with('number',$number);

}   

}   