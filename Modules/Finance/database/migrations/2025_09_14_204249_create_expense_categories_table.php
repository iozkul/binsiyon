<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('expense_categories')) {
            Schema::create('expense_categories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('site_id')->nullable()->constrained()->onDelete('cascade');
                $table->string('name');
                $table->string('financial_account_code')->comment('Related code from financial_accounts table');
                $table->timestamps();
            });
        }
    }
    public function down(): void { Schema::dropIfExists('expense_categories'); }
};
