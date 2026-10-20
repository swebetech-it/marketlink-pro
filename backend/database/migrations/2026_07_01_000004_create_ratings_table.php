<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->string('id')->primary(); // e.g. "rt_001", UUID
            $table->string('transaction_id')->nullable()->index();
            $table->string('rater_id')->nullable()->index();
            $table->string('rater_name');
            $table->string('ratee_id')->nullable()->index();
            $table->string('ratee_name');
            $table->unsignedTinyInteger('score'); // 1 to 5
            $table->text('comment');
            $table->date('date');
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('escrow_transactions')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
