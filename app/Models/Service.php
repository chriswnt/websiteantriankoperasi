<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'code',
        'name'
    ];

    // 🔥 RELASI KE QUEUES
    public function queues()
    {
        return $this->hasMany(\App\Models\Queue::class);
    }

    // 🔥 RELASI KE USERS (OFFICER)
    public function users()
    {
        return $this->hasMany(\App\Models\User::class);
    }
}