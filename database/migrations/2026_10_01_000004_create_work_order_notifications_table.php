<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_order_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->cascadeOnDelete();
            $table->string('channel')->default('whatsapp'); // whatsapp, sms
            $table->string('status_key')->nullable(); // arac_kabul, islem_basladi, parca_bekleniyor, teslim_edildi...
            $table->string('recipient_phone');
            $table->text('message');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_order_notifications');
    }
};
