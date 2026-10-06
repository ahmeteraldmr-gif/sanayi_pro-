<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diagnostic_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();

            // Müşteri şikayeti
            $table->text('complaint')->nullable();

            // Belirtiler: JSON array (hem checkbox seçimleri hem serbest metin)
            $table->json('symptoms')->nullable();

            // OBD hata kodları (JSON kısa format — ayrıntılı tablo ayrı)
            $table->json('obd_codes_summary')->nullable();

            // Ustanın yaptığı ölçümler
            $table->text('measurements')->nullable();

            // Daha önce yapılan işlemler
            $table->text('previous_work')->nullable();

            // Ustanın ek notları
            $table->text('usta_notes')->nullable();

            // AI analiz sonucu (ham JSON)
            $table->json('ai_result')->nullable();
            $table->timestamp('ai_analyzed_at')->nullable();

            // Kullanıcı geri bildirimi
            $table->enum('feedback', ['pending', 'solved', 'not_helpful', 'different_cause'])
                  ->default('pending');

            // Genel durum
            $table->enum('status', ['draft', 'analyzed', 'closed'])->default('draft');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diagnostic_sessions');
    }
};
