<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_products', function (Blueprint $table) {
            $table->string('id')->primary(); // e.g. "fin_01"
            $table->string('provider_name'); // CRDB Bank, NMB Bank, FINCA
            $table->string('product_name');
            $table->string('category'); // "Agri-Input Microloan", "Equipment Lease", "Working Capital"
            $table->unsignedBigInteger('max_amount_tzs');
            $table->string('interest_rate');
            $table->integer('min_reputation_score')->default(700);
            $table->text('description');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_products');
    }
};
