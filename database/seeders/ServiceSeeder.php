<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        Service::create([
            'code' => 'T',
            'name' => 'Teller'
        ]);

        Service::create([
            'code' => 'P',
            'name' => 'Pinjaman'
        ]);

        Service::create([
            'code' => 'A',
            'name' => 'Admin'
        ]);
    }
}