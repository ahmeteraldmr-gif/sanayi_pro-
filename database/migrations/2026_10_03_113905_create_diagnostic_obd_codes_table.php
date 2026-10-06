<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diagnostic_obd_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diagnostic_session_id')
                  ->constrained('diagnostic_sessions')
                  ->cascadeOnDelete();

            $table->string('code', 20);          // P0401, C1234 vs.
            $table->string('description')->nullable();
            $table->string('system')->nullable(); // Egzost, Yakıt, ABS vs.
            $table->text('usta_note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diagnostic_obd_codes');
    }
};
