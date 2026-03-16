<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('queues', function (Blueprint $table) {

            $table->foreignId('loket_id')
                  ->nullable()
                  ->after('service_id')
                  ->constrained('lokets')
                  ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('queues', function (Blueprint $table) {

            $table->dropForeign(['loket_id']);
            $table->dropColumn('loket_id');

        });
    }
};