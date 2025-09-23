<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('unit_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['owner', 'tenant']); // Rolü: Malik mi, Kiracı mı?
            $table->date('start_date');
            $table->date('end_date')->nullable(); // Null ise hala aktif demektir
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('unit_histories');
    }
};
