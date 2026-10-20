<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('escrow_transactions', function (Blueprint $table) {
            $table->string('id')->primary(); // e.g. "tx_8801", UUID
            $table->string('listing_id')->nullable()->index();
            $table->string('listing_title');
            $table->string('payer_id')->nullable()->index();
            $table->string('payer_name');
            $table->string('payee_id')->nullable()->index();
            $table->string('payee_name');
            $table->unsignedBigInteger('amount_tzs');
            $table->unsignedBigInteger('fee_tzs')->default(0); // 5% platform fee
            $table->string('escrow_status')->default('HELD_IN_ESCROW'); // AWAITING_DEPOSIT, HELD_IN_ESCROW, RELEASED, DISPUTED, REFUNDED
            $table->string('payment_method')->default('M-Pesa Escrow'); // M-Pesa, Tigo Pesa, Airtel Money, CRDB Bank, NMB Bank
            $table->string('timestamp_str')->nullable();
            $table->string('audit_code')->unique()->index();
            $table->string('gateway_ref')->nullable();
            $table->text('dispute_reason')->nullable();
            $table->timestamps();

            $table->foreign('listing_id')->references('id')->on('listings')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('escrow_transactions');
    }
};
