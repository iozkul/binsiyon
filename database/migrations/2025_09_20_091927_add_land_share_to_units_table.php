<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('units', function (Blueprint $table) {
            if (!Schema::hasColumn('units', 'land_share')) {
                $table->unsignedInteger('land_share')->nullable()->after('size_sqm')->comment('Arsa Payı (Pay)');
            }
        });
    }

    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            if (Schema::hasColumn('units', 'land_share')) {
                $table->dropColumn('land_share');
            }
        });
    }
};
