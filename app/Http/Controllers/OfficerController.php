<?php

namespace App\Http\Controllers;

use App\Models\Queue;

class OfficerController extends Controller
{

public function index()
{

$queues = Queue::where('status','waiting')
        ->orderBy('id')
        ->get();

return view('officer',compact('queues'));

}

public function call($id)
{

$queue = Queue::find($id);

$queue->update([
'status'=>'called'
]);

return redirect()->back();

}

}