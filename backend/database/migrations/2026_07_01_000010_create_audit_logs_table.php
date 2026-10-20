<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event')->index(); // ESCROW_DEPOSIT, ESCROW_RELEASE, NIDA_VERIFIED, FRAUD_FLAGGED, USSD_DIAL
            $table->string('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
