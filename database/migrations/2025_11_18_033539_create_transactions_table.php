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
        Schema::create('transactions', function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->ulid("tenancy_id");
                $table->string('order_id')->unique();
                $table->string('snap_token')->nullable();
                $table->decimal('gross_amount', 15, 2);
                $table->enum('status', [
                    'pending',
                    'settlement',
                    'capture',
                    'deny',
                    'cancel',
                    'expire',
                    'failure'
                ])->default('pending');

                $table->enum('payment_type', [
                    'cash',
                    'manual_transfer',
                    'bank_transfer',
                    'gopay',
                    'qris',
                    'shopeepay',
                    'cstore',
                    'credit_card',
                    'other'
                ])->nullable();
                $table->timestamps();

                $table->foreign('tenancy_id')->references('id')->on('tenancies')->onDelete('cascade');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
