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
        if (!Schema::hasTable('staff_members')) {
            Schema::create('staff_members', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('site_id')->constrained()->onDelete('cascade');
                $table->string('job_title');
                $table->date('start_date');
                $table->date('end_date')->nullable();
                $table->enum('salary_type', ['gross', 'net'])->default('gross');
                $table->decimal('salary_amount', 15, 2);
                $table->string('iban')->nullable();
                $table->text('tckn')->nullable(); // Encrypted
                $table->json('sgk_details')->nullable();
                $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('staff_members');
    }
};
