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
        Schema::dropIfExists('fees');
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained(); // Hangi siteye ait olduğu
            $table->foreignId('block_id')->constrained(); // Hangi bloğa ait olduğu
            $table->foreignId('apartment_id')->constrained(); // Hangi daireye ait olduğu
            $table->foreignId('user_id')->constrained(); // Hangi kullanıcıya (sakine) ait olduğu
            $table->string('description'); // Örn: "Eylül 2025 Aidatı"
            $table->decimal('amount', 8, 2); // Tutar
            $table->date('due_date'); // Son ödeme tarihi
            $table->timestamp('paid_at')->nullable(); // Ödendiği tarih (boş ise ödenmemiş)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fees');
    }
};
