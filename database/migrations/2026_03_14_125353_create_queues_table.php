<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->id();

            $table->foreignId('service_id')->constrained()->cascadeOnDelete();

            $table->string('queue_number'); // T001, P001

            $table->enum('status',['waiting','called','done'])
                  ->default('waiting');

            $table->timestamp('called_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};