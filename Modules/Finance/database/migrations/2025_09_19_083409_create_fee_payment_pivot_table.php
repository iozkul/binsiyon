<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('fee_payment')) {
            Schema::create('fee_payment', function (Blueprint $table) {
                $table->id();
                $table->foreignId('fee_id')->constrained('fees')->onDelete('cascade');
                $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');
                $table->decimal('amount', 15, 2); // Bu ödemeden bu borca ne kadar yatırıldığı
                $table->timestamps();
            });
        }

        // Eski 'fee_id' kolonunu payments tablosundan kaldıralım
        if (Schema::hasColumn('payments', 'fee_id')) {
            Schema::table('payments', function (Blueprint $table) {
                // foreign key'i önce drop etmeliyiz. İsmi genellikle `tablo_kolon_foreign` olur.
                // SQL dosyanızı inceleyerek doğru ismi bulmanız gerekebilir.
                // Varsayılan olarak laravel'in verdiği isim bu şekildedir.
                $table->dropForeign(['fee_id']);
                $table->dropColumn('fee_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_payment');

        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'fee_id')) {
                $table->foreignId('fee_id')->nullable()->constrained('fees');
            }
        });
    }
};
