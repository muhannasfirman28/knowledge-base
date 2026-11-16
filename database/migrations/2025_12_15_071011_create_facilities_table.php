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
        Schema::create('facilities', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name'); // Contoh: "AC", "WiFi", "Kamar Mandi Dalam"
            $table->string('icon')->nullable(); // Opsional: Simpan nama class icon (misal: fa-wifi)
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
