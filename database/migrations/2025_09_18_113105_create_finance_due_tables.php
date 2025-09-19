<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Önce eski "dues" tablosunu yeniden adlandıralım veya silelim.
        // Schema::rename('dues', 'due_definitions'); // Varsa ismini değiştirin.
        // Yoksa yeniden oluşturalım:
        if (!Schema::hasTable('due_definitions')) {
            Schema::create('due_definitions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('site_id')->constrained()->onDelete('cascade');
                $table->string('name'); // Örn: "Eylül 2025 Aidat Borcu"
                $table->text('description')->nullable();
                $table->date('due_date'); // Son ödeme tarihi
                $table->enum('type', ['fixed', 'per_square_meter'])->default('fixed');
                $table->decimal('default_amount', 15, 2)->nullable(); // m2 başına veya sabit varsayılan tutar
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('apartment_dues')) {
            Schema::create('apartment_dues', function (Blueprint $table) {
                $table->id();
                $table->foreignId('due_definition_id')->constrained()->onDelete('cascade');
                $table->foreignId('apartment_id')->constrained('units')->onDelete('cascade'); // Daireler 'units' tablosunda
                $table->decimal('amount', 15, 2); // Bu daire için hesaplanmış borç tutarı
                $table->decimal('paid_amount', 15, 2)->default(0);
                $table->enum('status', ['pending', 'paid', 'overdue', 'partially_paid'])->default('pending');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('apartment_dues');
        Schema::dropIfExists('due_definitions');
    }
};
