<?php

namespace App\Http\Controllers;

use App\Models\Queue;

class DashboardController extends Controller
{

public function index()
{

$queue = Queue::where('status','called')
        ->latest()
        ->first();

return view('dashboard',compact('queue'));

}

}