<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->string('title')->nullable()->after('name'); // Uzmanlık Başlığı (örn: Motor Revizyon)
            $table->text('description')->nullable()->after('title');
            $table->string('phone')->nullable()->after('description');
            $table->text('address')->nullable()->after('phone');
            $table->string('tax_office')->nullable()->after('address');
            $table->string('tax_no')->nullable()->after('tax_office');
            $table->text('receipt_footer')->nullable()->after('tax_no');
            $table->text('whatsapp_message')->nullable()->after('receipt_footer');
            $table->string('currency')->default('TRY')->after('whatsapp_message');
            $table->string('logo')->nullable()->after('currency');
        });
    }

    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn([
                'title', 'description', 'phone', 'address',
                'tax_office', 'tax_no', 'receipt_footer',
                'whatsapp_message', 'currency', 'logo'
            ]);
        });
    }
};
