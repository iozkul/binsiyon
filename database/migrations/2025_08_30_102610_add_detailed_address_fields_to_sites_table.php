<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            // Önce eski 'address' kolonunu kaldırıyoruz
            $table->dropColumn('address');

            // Yeni, detaylı adres kolonlarını 'name' kolonundan sonra ekliyoruz
            $table->string('country')->after('name')->default('Türkiye');
            $table->string('city')->after('country');
            $table->string('district')->after('city');
            $table->string('address_line')->after('district'); // Cadde, sokak, no vb.
            $table->string('postal_code')->nullable()->after('address_line');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->string('address')->after('name');
            $table->dropColumn(['country', 'city', 'district', 'address_line', 'postal_code']);

        });
    }
};
