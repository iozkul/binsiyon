<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('expenses', 'status')) {
                $table->string('status')->default('active')->after('amount'); // active, cancelled
            }
            if (!Schema::hasColumn('expenses', 'reversal_of_expense_id')) {
                $table->foreignId('reversal_of_expense_id')->nullable()->constrained('expenses')->onDelete('set null')->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            if (Schema::hasColumn('expenses', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('expenses', 'reversal_of_expense_id')) {
                $table->dropForeign(['reversal_of_expense_id']);
                $table->dropColumn('reversal_of_expense_id');
            }
        });
    }
};
