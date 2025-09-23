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
        if (!Schema::hasTable('expenses')) {
            Schema::create('expenses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('site_id')->constrained('sites');
                $table->string('vendor')->nullable();
                $table->string('category');
                $table->date('expense_date');
                $table->decimal('amount', 15, 2);
                $table->decimal('tax_rate', 5, 2)->default(20.00);
                $table->decimal('total_amount', 15, 2);
                $table->text('description')->nullable();
                $table->string('receipt_url')->nullable();
                $table->foreignId('created_by_user_id')->constrained('users');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
