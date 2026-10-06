<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diagnostic_solutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diagnostic_session_id')
                  ->nullable()
                  ->constrained('diagnostic_sessions')
                  ->nullOnDelete();
            $table->foreignId('work_order_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();
            $table->foreignId('solved_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Araç bilgileri (denormalized — bilgi bankası araması için)
            $table->string('vehicle_brand')->nullable();
            $table->string('vehicle_model')->nullable();
            $table->string('vehicle_year')->nullable();
            $table->string('vehicle_engine')->nullable();
            $table->string('vehicle_fuel_type')->nullable();
            $table->unsignedInteger('mileage')->nullable();

            // Teşhis verileri (arama/eşleştirme için)
            $table->json('symptoms')->nullable();    // belirtiler
            $table->json('obd_codes')->nullable();   // OBD kodları

            // Çözüm bilgileri
            $table->text('root_cause');              // gerçek sorun
            $table->text('action_taken');            // yapılan işlem
            $table->json('parts_replaced')->nullable(); // değiştirilen parçalar
            $table->text('result')->nullable();      // sonuç

            $table->timestamps();

            // Arama için indeksler
            $table->index(['vehicle_brand', 'vehicle_model']);
            $table->index('solved_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diagnostic_solutions');
    }
};
