<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Officer 1',
            'email' => 'officer1@example.com',
            'password' => bcrypt('password'),
            'role' => 'officer',
        ]);

        User::create([
            'name' => 'Officer 2',
            'email' => 'officer2@example.com',
            'password' => bcrypt('password'),
            'role' => 'officer',
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
    }
}
