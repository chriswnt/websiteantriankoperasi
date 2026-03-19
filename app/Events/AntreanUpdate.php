<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets; // Tambahkan ini
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AntreanUpdate implements ShouldBroadcastNow
{
    // Tambahkan InteractsWithSockets di sini
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct()
    {
        //
    }

    public function broadcastOn()
    {
        return new Channel('antrean-channel');
    }

    // Sangat disarankan tambahkan ini agar nama event di JS jadi jelas
    public function broadcastAs()
    {
        return 'AntreanUpdate';
    }
}