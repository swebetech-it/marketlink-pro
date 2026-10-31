<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations in the isolated tenant database.
     */
    public function up(): void
    {
        Schema::connection('tenant')->create('messages', function (Blueprint $table) {
            $table->string('id')->primary(); // e.g. "msg_101", UUID
            $table->string('listing_id')->index();
            $table->string('sender_id')->nullable()->index();
            $table->string('sender_name');
            $table->string('receiver_id')->nullable()->index();
            $table->string('receiver_name');
            $table->text('text');
            $table->string('timestamp_str')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('listing_id')->references('id')->on('listings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('messages');
    }
};
