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
            // 'name' sütununu nullable yapıyoruz, çünkü artık ad ve soyadı ayrı tutacağız.
            $table->string('name')->nullable()->change();

            // Yeni sütunlar
            $table->string('address')->nullable()->after('phone');
            $table->string('city')->nullable()->after('address');
            $table->string('district')->nullable()->after('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([

                'address',
                'city',
                'district',
            ]);
            $table->string('name')->nullable(false)->change();
        });
    }
};
