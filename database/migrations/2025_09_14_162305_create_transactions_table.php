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
        if (!Schema::hasTable('transactions')) {
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('site_id')->constrained('sites')->onDelete('cascade');
                $table->date('transaction_date');
                $table->string('description');
                $table->decimal('amount', 15, 2);
                $table->string('type'); // due, expense, rent_payment, etc.
                $table->morphs('transactionable'); // reference to due, expense etc.
                $table->foreignId('created_by_user_id')->constrained('users');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
