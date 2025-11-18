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
        Schema::create('bill_transactions', function (Blueprint $table) {
                $table->ulid('transaction_id');
                $table->ulid('bill_id');
                $table->timestamps();
                
                $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
                $table->foreign('bill_id')->references('id')->on('bills')->onDelete('cascade');

                $table->primary(['transaction_id', 'bill_id']);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_transactions');
    }
};
