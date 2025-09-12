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
        // Bir duyurunun hangi rollere hedeflendiğini tutar
        Schema::create('announcement_role', function (Blueprint $table) {
            $table->foreignId('announcement_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->primary(['announcement_id', 'role_id']);
        });

        // Bir duyurunun hangi kişilere hedeflendiğini tutar
        Schema::create('announcement_user', function (Blueprint $table) {
            $table->foreignId('announcement_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->primary(['announcement_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcement_targets');
    }
};
