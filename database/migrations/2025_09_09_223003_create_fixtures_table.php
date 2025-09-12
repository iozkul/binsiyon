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
        Schema::create('fixtures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Örn: Asansör 1, Jeneratör
            $table->string('brand')->nullable(); // Marka
            $table->string('model')->nullable(); // Model
            $table->date('purchase_date')->nullable(); // Alım Tarihi
            $table->date('warranty_end_date')->nullable(); // Garanti Bitiş Tarihi
            $table->integer('maintenance_interval_days')->nullable(); // Kaç günde bir bakım yapılacak?
            $table->date('last_maintenance_date')->nullable(); // Son Bakım Tarihi
            $table->date('next_maintenance_date')->nullable(); // Bir Sonraki Bakım Tarihi (Hesaplanacak)
            $table->text('notes')->nullable(); // Ek notlar
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixtures');
    }
};
