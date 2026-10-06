<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('diagnostic_sessions', function (Blueprint $table) {
            $table->foreignId('work_order_id')->nullable()->change();
            $table->unsignedInteger('mileage')->nullable()->after('vehicle_id');
            $table->json('checks_performed')->nullable()->after('symptoms');
            $table->string('ai_feedback')->nullable()->after('feedback');
            $table->string('solution_status')->nullable()->after('ai_feedback');
        });

        Schema::table('diagnostic_solutions', function (Blueprint $table) {
            $table->json('ai_suggestions')->nullable()->after('obd_codes');
            $table->string('solution_status')->nullable()->after('result');
            $table->text('extra_note')->nullable()->after('solution_status');
        });
    }

    public function down(): void
    {
        Schema::table('diagnostic_solutions', function (Blueprint $table) {
            $table->dropColumn(['ai_suggestions', 'solution_status', 'extra_note']);
        });

        Schema::table('diagnostic_sessions', function (Blueprint $table) {
            $table->dropColumn(['mileage', 'checks_performed', 'ai_feedback', 'solution_status']);
        });
    }
};
