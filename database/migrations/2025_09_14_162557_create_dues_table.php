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
        if (!Schema::hasTable('dues')) {
            Schema::create('dues', function (Blueprint $table) {
                $table->id();
                $table->foreignId('site_id')->constrained('sites');
                $table->foreignId('apartment_id')->constrained('apartments');
                $table->foreignId('resident_user_id')->constrained('users');
                $table->date('period');
                $table->decimal('amount', 15, 2);
                $table->date('due_date');
                $table->enum('status', ['pending', 'paid', 'overdue', 'partially_paid'])->default('pending');
                $table->timestamps();

                $table->index(['status', 'due_date']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dues');
    }
};
