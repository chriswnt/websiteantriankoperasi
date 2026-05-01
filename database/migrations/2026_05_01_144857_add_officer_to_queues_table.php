<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('queues', function (Blueprint $table) {
        $table->unsignedBigInteger('officer_id')->nullable()->after('service_id');
        $table->string('officer_name')->nullable()->after('officer_id');
    });
}

public function down()
{
    Schema::table('queues', function (Blueprint $table) {
        $table->dropColumn(['officer_id', 'officer_name']);
    });
}
};
