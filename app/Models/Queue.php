<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $fillable = [
        'queue_number',
        'service_id', 
        'status',
        'called_at', // 🔥 Tambahkan ini agar diizinkan Laravel
        'done_at'    // 🔥 Tambahkan ini juga
    ];

    // 🔥 RELASI KE SERVICES
    public function service()
    {
        return $this->belongsTo(\App\Models\Service::class);
    }
}