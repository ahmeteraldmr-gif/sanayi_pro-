<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Şubeler (Sanayi Dalları)
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // 2. Kullanıcılar tablosuna Rol ve Şube ekle
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('branch_user');
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
        });

        // 3. Diğer tablolara Şube izolasyonu ekle
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->constrained()->cascadeOnDelete();
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->constrained()->cascadeOnDelete();
        });

        Schema::table('parts', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->constrained()->cascadeOnDelete();
        });

        Schema::table('work_orders', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) { $table->dropForeign(['branch_id']); $table->dropColumn('branch_id'); });
        Schema::table('parts', function (Blueprint $table) { $table->dropForeign(['branch_id']); $table->dropColumn('branch_id'); });
        Schema::table('vehicles', function (Blueprint $table) { $table->dropForeign(['branch_id']); $table->dropColumn('branch_id'); });
        Schema::table('customers', function (Blueprint $table) { $table->dropForeign(['branch_id']); $table->dropColumn('branch_id'); });
        Schema::table('users', function (Blueprint $table) { $table->dropForeign(['branch_id']); $table->dropColumn(['role', 'branch_id']); });
        Schema::dropIfExists('branches');
    }
};
