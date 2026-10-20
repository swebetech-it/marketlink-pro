<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->string('id')->primary(); // e.g. "n_101", UUID
            $table->string('user_id')->nullable()->index();
            $table->string('title');
            $table->text('body');
            $table->string('channel')->default('In-App'); // In-App, SMS (Africa's Talking), WhatsApp, USSD Alert
            $table->string('timestamp_str')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
