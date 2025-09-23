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
        if (!Schema::hasTable('payrolls')) {
            Schema::create('payrolls', function (Blueprint $table) {
                $table->id();
                $table->foreignId('site_id')->constrained()->onDelete('cascade');
                $table->unsignedSmallInteger('period_year');
                $table->unsignedTinyInteger('period_month');
                $table->decimal('total_gross_salary', 15, 2);
                $table->decimal('total_net_salary', 15, 2);
                $table->decimal('total_sgk_cost', 15, 2); // İşçi + İşveren
                $table->decimal('total_employer_cost', 15, 2);
                $table->enum('status', ['draft', 'finalized', 'paid'])->default('draft');
                $table->foreignId('created_by_user_id')->constrained('users');
                $table->timestamps();
                $table->unique(['site_id', 'period_year', 'period_month']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
