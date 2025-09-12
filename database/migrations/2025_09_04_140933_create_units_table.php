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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('block_id')->constrained()->onDelete('cascade');
            $table->string('name_or_number'); // Örn: "Daire 5", "Dükkan 1", "Fitness Salonu"
            $table->enum('type', ['apartment', 'commercial', 'social']); // Birimin türü
            $table->json('properties')->nullable(); // m², oda sayısı gibi özellikler burada JSON olarak tutulacak
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
