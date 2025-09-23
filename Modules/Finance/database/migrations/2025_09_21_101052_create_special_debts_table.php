<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('special_debts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained();
            $table->foreignId('unit_id')->constrained(); // Hangi daireye ait olduğu
            $table->foreignId('expense_id')->nullable()->constrained(); // Hangi harcamadan kaynaklandığı (örn: asansör tamiri)
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->string('status')->default('unpaid'); // unpaid, paid
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('special_debts'); }
};
