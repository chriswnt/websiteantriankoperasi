<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{

   protected $fillable = [
        'service_id',
        'queue_number',
        'status',
        'loket',
        'started_at',
        'finished_at'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

}