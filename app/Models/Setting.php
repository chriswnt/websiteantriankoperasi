<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'logo',
        'background',
        'youtube',
        'title',
        'address',
        'phone'
    ];
}