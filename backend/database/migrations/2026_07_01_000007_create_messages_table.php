<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
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

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
