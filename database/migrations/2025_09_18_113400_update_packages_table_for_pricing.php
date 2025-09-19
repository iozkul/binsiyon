<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            if (Schema::hasColumn('packages', 'price')) {
                $table->renameColumn('price', 'price_monthly');
            }
            if (!Schema::hasColumn('packages', 'price_yearly')) {
                $table->decimal('price_yearly', 8, 2)->default(0)->after('price_monthly');
            }
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            if (Schema::hasColumn('packages', 'price_monthly')) {
                $table->renameColumn('price_monthly', 'price');
            }
            if (Schema::hasColumn('packages', 'price_yearly')) {
                $table->dropColumn('price_yearly');
            }
        });
    }
};
