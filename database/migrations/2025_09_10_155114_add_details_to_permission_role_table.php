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
        // Yetkilerin listesini tutacak tablo
        Schema::table('permissions', function (Blueprint $table) {
            //$table->id();
            //$table->string('name'); // Örn: "Kullanıcı Yönetimi"
            //$table->string('key')->unique(); // Örn: "user.manage"
            ///$table->text('description')->nullable();
            //$table->timestamps();
        });

        // Rol-Yetki ilişkisi için pivot tablo
        Schema::table('role_has_permissions', function (Blueprint $table) {
            //$table->primary(['role_id', 'permission_id']);
            //$table->foreignId('role_id')->constrained()->onDelete('cascade');
            //$table->foreignId('permission_id')->constrained()->onDelete('cascade');
        });

        // Kullanıcı-Rol ilişkisi için pivot tablo
        Schema::create('user_role', function (Blueprint $table) {
            $table->primary(['user_id', 'role_id']);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

            Schema::dropIfExists('user_role');
            Schema::dropIfExists('role_has_permissions');
            Schema::dropIfExists('permissions');

    }
};
