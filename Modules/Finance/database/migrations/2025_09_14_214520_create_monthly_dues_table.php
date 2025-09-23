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
        if (!Schema::hasTable('monthly_dues')) {
            Schema::create('monthly_dues', function (Blueprint $table) {
                $table->id();
                $table->foreignId('site_id')->constrained()->onDelete('cascade');
                $table->foreignId('apartment_id')->constrained()->onDelete('cascade');
                $table->foreignId('resident_user_id')->constrained('users')->onDelete('cascade');
                $table->date('period');
                $table->decimal('amount', 15, 2);
                $table->date('due_date');
                $table->enum('status', ['pending', 'paid', 'overdue', 'partially_paid'])->default('pending');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_dues');
    }
};
