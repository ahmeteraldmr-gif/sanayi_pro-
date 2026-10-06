<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('parts', function (Blueprint $table) {
            $table->string('brand')->nullable()->after('name'); // e.g. Mann-Filter, Bosch
            $table->string('oem_code')->nullable()->after('code'); // OEM / Orijinal Kodu
            $table->string('supplier')->nullable()->after('category'); // Tedarikçi Firma
            $table->text('compatible_vehicles')->nullable()->after('supplier'); // Uyumlu Araçlar (BMW 320d, Audi A4...)
            $table->decimal('last_buy_price', 10, 2)->default(0)->after('buy_price');
        });

        Schema::table('part_movements', function (Blueprint $table) {
            $table->foreignId('vehicle_id')->nullable()->after('work_order_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('part_movements', function (Blueprint $table) {
            $table->dropForeign(['vehicle_id']);
            $table->dropColumn('vehicle_id');
        });

        Schema::table('parts', function (Blueprint $table) {
            $table->dropColumn(['brand', 'oem_code', 'supplier', 'compatible_vehicles', 'last_buy_price']);
        });
    }
};
