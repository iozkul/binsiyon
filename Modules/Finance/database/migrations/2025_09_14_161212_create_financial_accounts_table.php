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
        if (!Schema::hasTable('financial_accounts')) {
            Schema::create('financial_accounts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('site_id')->nullable()->constrained('sites')->onDelete('cascade');
                $table->string('code', 20)->unique();
                $table->string('name');
                $table->enum('type', ['asset', 'liability', 'equity', 'revenue', 'expense']);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_accounts');
    }
};
