<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('income_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->onDelete('cascade');
            $table->string('name'); // örn: "Elektrikli Araç Şarj Geliri", "Vadeli Mevduat Faizi"
            $table->boolean('is_taxable')->default(false); // Vergiye tabi mi?
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('income_sources');
    }
};
