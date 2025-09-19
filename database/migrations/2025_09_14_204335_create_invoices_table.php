<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('invoices')) {
            Schema::create('invoices', function (Blueprint $table) {
                $table->id();
                $table->foreignId('site_id')->constrained()->onDelete('cascade');
                $table->foreignId('vendor_id')->nullable()->constrained()->onDelete('set null');
                $table->string('invoice_number')->nullable();
                $table->date('invoice_date');
                $table->date('due_date');
                $table->text('description');
                $table->decimal('subtotal', 15, 2);
                $table->decimal('tax_amount', 15, 2)->default(0);
                $table->decimal('total_amount', 15, 2);
                $table->enum('status', ['pending', 'paid', 'partially_paid', 'overdue'])->default('pending');
                $table->foreignId('expense_category_id')->constrained('expense_categories');
                $table->string('receipt_url')->nullable();
                $table->foreignId('created_by_user_id')->constrained('users');
                $table->timestamps();
                $table->index(['site_id', 'status']);
            });
        }
    }
    public function down(): void { Schema::dropIfExists('invoices'); }
};
