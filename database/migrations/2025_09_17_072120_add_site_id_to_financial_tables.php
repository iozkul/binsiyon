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
        // Expenses Tablosu
        Schema::table('expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('expenses', 'site_id')) {
                $table->foreignId('site_id')->after('id')->constrained()->onDelete('cascade');
            }
        });

        // Incomes Tablosu
        Schema::table('incomes', function (Blueprint $table) {
            if (!Schema::hasColumn('incomes', 'site_id')) {
                $table->foreignId('site_id')->after('id')->constrained()->onDelete('cascade');
            }
        });

        // Fees Tablosu
        Schema::table('fees', function (Blueprint $table) {
            if (!Schema::hasColumn('fees', 'site_id')) {
                $table->foreignId('site_id')->after('id')->constrained()->onDelete('cascade');
            }
        });

        // Payments Tablosu
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'site_id')) {
                $table->foreignId('site_id')->after('id')->constrained()->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
            $table->dropColumn('site_id');
        });

        Schema::table('incomes', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
            $table->dropColumn('site_id');
        });

        Schema::table('fees', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
            $table->dropColumn('site_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
            $table->dropColumn('site_id');
        });
    }
};
