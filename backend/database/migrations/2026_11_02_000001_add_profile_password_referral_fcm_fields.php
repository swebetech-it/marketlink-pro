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
        Schema::table('users', function (Blueprint $table) {
            $table->text('avatar_url')->nullable()->after('bio');
            $table->string('password_reset_otp', 10)->nullable()->after('password');
            $table->timestamp('password_reset_expires_at')->nullable()->after('password_reset_otp');
            $table->string('referral_code', 30)->nullable()->unique()->after('remember_token');
            $table->string('referred_by', 64)->nullable()->index()->after('referral_code');
            $table->integer('referral_count')->default(0)->after('referred_by');
            $table->bigInteger('referral_earnings_tzs')->default(0)->after('referral_count');
            $table->text('fcm_token')->nullable()->after('referral_earnings_tzs');
            $table->string('device_type', 20)->default('android')->after('fcm_token');
        });

        Schema::create('referrals', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('referrer_id')->index();
            $table->string('referred_id')->index();
            $table->integer('reward_amount_tzs')->default(2000);
            $table->string('status')->default('REGISTERED'); // REGISTERED, COMPLETED_GIG, REWARDED
            $table->boolean('reward_claimed')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrals');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar_url',
                'password_reset_otp',
                'password_reset_expires_at',
                'referral_code',
                'referred_by',
                'referral_count',
                'referral_earnings_tzs',
                'fcm_token',
                'device_type',
            ]);
        });
    }
};
