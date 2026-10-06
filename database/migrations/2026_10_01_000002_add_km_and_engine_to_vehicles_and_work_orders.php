<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('engine')->nullable()->after('model'); // e.g. 2.0 Dizel
            $table->unsignedInteger('mileage')->nullable()->default(0)->after('year'); // e.g. 184250
        });

        Schema::table('work_orders', function (Blueprint $table) {
            $table->unsignedInteger('mileage')->nullable()->after('date'); // KM at service time
        });
    }

    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn('mileage');
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['engine', 'mileage']);
        });
    }
};
