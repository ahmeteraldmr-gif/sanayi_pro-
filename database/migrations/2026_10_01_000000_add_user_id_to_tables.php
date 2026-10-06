<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('branch_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('branch_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('parts', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('branch_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('work_orders', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('branch_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('parts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
