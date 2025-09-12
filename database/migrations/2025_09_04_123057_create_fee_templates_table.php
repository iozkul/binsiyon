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
        Schema::create('fee_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Örn: "A Blok Daire Aidatı"
            $table->decimal('amount', 8, 2); // Aidat tutarı

            // Bu şablonun neye uygulanacağını belirleyen polimorfik ilişki
            // (Site, Blok veya Unit modeline bağlanabilir)
            $table->morphs('applicable');

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_templates');
    }
};
