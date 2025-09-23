<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('employees')) {
            Schema::create('employees', function (Blueprint $table) {
                $table->id();
                $table->foreignId('site_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade'); // Her kullanıcı sadece bir personel kaydına sahip olabilir.
                $table->string('job_title');
                $table->date('hire_date');
                $table->date('termination_date')->nullable();
                $table->decimal('salary', 10, 2)->nullable();
                $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
