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
        Schema::create('bills', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('tenancy_id');
            $table->string('month_name');
            $table->date('due_date');
            $table->decimal('amount');
            $table->integer('order_sequence');
            $table->timestamps();

            $table->foreign('tenancy_id')->references('id')->on('tenancies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
