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
        Schema::create('users', function (Blueprint $table) {
            $table->string('id')->primary(); // e.g. "usr_101", UUID, or "current_user"
            $table->string('full_name');
            $table->string('phone')->unique()->index();
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('role')->default('JOB_SEEKER'); // JOB_SEEKER, FREELANCER, EMPLOYER, SERVICE_CLIENT, FARMER_COOP, INSTITUTION, FINANCIAL_PARTNER, ADMIN
            $table->string('verification_status')->default('UNVERIFIED'); // UNVERIFIED, PENDING, VERIFIED
            $table->string('nida_number')->nullable()->index();
            $table->string('location')->default('Tanzania');
            $table->text('bio')->nullable();
            $table->json('skills')->nullable();
            $table->decimal('rating', 3, 2)->default(5.0);
            $table->integer('total_transactions')->default(0);
            $table->integer('completed_gigs')->default(0);
            $table->integer('reputation_score')->default(800);
            $table->integer('community_vouches')->default(0);
            $table->json('badges')->nullable();
            $table->boolean('mfa_enabled')->default(false);
            $table->string('language')->default('sw'); // 'sw' for Swahili, 'en' for English
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
