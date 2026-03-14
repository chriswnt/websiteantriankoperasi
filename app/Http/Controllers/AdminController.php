<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;
use App\Models\Service;

class AdminController extends Controller
{
    public function index()
    {
        $queues = Queue::with('service')->orderBy('created_at', 'desc')->paginate(20);
        $services = Service::all();
        return view('admin', compact('queues', 'services'));
    }

    public function storeService(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:10|unique:services',
            'name' => 'required|string|max:255',
        ]);

        Service::create($request->only(['code', 'name']));

        return back()->with('success', 'Service added successfully');
    }

    public function updateService(Request $request, Service $service)
    {
        $request->validate([
            'code' => 'required|string|max:10|unique:services,code,' . $service->id,
            'name' => 'required|string|max:255',
        ]);

        $service->update($request->only(['code', 'name']));

        return back()->with('success', 'Service updated successfully');
    }

    public function destroyService(Service $service)
    {
        $service->delete();

        return back()->with('success', 'Service deleted successfully');
    }
}
