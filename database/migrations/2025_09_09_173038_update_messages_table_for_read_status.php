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
        Schema::table('messages', function (Blueprint $table) {
            // Eğer gönderen (from) ve alıcı (to) sütunlarınız yoksa ekleyin
            // Not: Sadece sizde eksik olanları ekleyin.
            $table->foreignId('from_user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('to_user_id')->nullable()->constrained('users')->onDelete('cascade');

            // Okunma zamanını tutacak sütun
            $table->timestamp('read_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['from_user_id']);
            $table->dropForeign(['to_user_id']);
            $table->dropColumn(['from_user_id', 'to_user_id', 'read_at']);
        });
    }
};
