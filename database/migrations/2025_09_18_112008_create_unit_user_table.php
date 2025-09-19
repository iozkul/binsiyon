<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // 'owner' -> Malik, 'tenant' -> Kiracı, 'resident' -> Dairede yaşayan diğer sakinler
            $table->enum('relation_type', ['owner', 'tenant', 'resident'])->default('resident');
            $table->timestamps();

            // Bir kullanıcı bir daireye sadece bir ilişki tipiyle bağlanabilir.
            $table->unique(['unit_id', 'user_id', 'relation_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_user');
    }
};
