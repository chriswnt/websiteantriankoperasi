<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {

            $table->id();
            $table->string('code');   // contoh: A, B
            $table->string('name');   // contoh: Administrasi, Perizinan
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};