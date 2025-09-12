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
        // 1. Konuşmaların kendisini tutar (başlık gibi)
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->string('subject'); // Konuşma başlığı
            $table->timestamps();
        });

        // 2. Bir konuşmaya hangi kullanıcıların dahil olduğunu tutan pivot tablo
        Schema::create('conversation_user', function (Blueprint $table) {
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('read_at')->nullable(); // Kullanıcının bu konuşmayı en son ne zaman okuduğu
            $table->primary(['conversation_id', 'user_id']);
        });

        // 3. Her bir konuşmadaki mesajları tutar
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Mesajı gönderen
            $table->text('body');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messaging_tables');
    }
};
