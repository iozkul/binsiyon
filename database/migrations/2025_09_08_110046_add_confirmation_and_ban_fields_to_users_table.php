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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_email_confirmed')->default(false)->after('remember_token');
            $table->string('confirmation_token')->nullable()->unique()->after('is_email_confirmed');
            $table->boolean('is_banned_by_admin')->default(false)->after('confirmation_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_email_confirmed', 'confirmation_token', 'is_banned_by_admin']);
        });
    }
};
