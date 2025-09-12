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
            // `sites` tablosundaki `id` sütununa referans veren bir foreign key ekliyoruz.
            // nullable(): Her kullanıcının bir sitesi olmak zorunda değil (örneğin super-admin).
            // constrained(): Foreign key kısıtlamalarını otomatik ayarlar.
            // onDelete('set null'): Eğer bir site silinirse, o siteye bağlı kullanıcıların site_id'si null olur.
            $table->foreignId('site_id')->nullable()->after('id')->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
