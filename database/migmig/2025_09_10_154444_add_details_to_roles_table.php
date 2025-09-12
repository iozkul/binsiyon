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
        Schema::table('roles', function (Blueprint $table) {
            //$table->text('description')->nullable()->after('guard_name');

            // Hiyerarşi için (üst rol)
            $table->unsignedBigInteger('parent_role_id')->nullable()->after('guard_name');
            $table->foreign('parent_role_id')->references('id')->on('roles')->onDelete('set null')->after('guard_name');

            // Rol tipini belirlemek için (Sistem mi, Müşteri mi?)
            $table->enum('role_type', ['SYSTEM', 'CLIENT'])->default('CLIENT')->after('guard_name');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            Schema::dropIfExists('roles');
        });
    }
};
