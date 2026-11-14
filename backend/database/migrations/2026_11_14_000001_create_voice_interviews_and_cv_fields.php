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
            $table->longText('cv_text')->nullable()->after('bio');
            $table->integer('years_of_experience')->default(1)->after('cv_text');
        });

        Schema::table('listings', function (Blueprint $table) {
            $table->boolean('voice_announced')->default(false)->after('status');
            $table->string('voice_announcement_audio_url')->nullable()->after('voice_announced');
        });

        Schema::create('voice_interviews', function (Blueprint $table) {
            $table->string('id')->primary(); // e.g. "vi_101"
            $table->string('listing_id')->index();
            $table->string('candidate_id')->index();
            $table->string('candidate_name');
            $table->string('candidate_phone');
            $table->string('status')->default('INITIATED'); // INITIATED, CALLING, IN_PROGRESS, COMPLETED, FAILED, DECLINED
            $table->string('call_session_id')->nullable()->index();
            $table->json('questions'); // Dynamic CV-derived questions
            $table->json('answers')->nullable(); // Recorded responses and transcripts
            $table->integer('current_question_index')->default(0);
            $table->integer('overall_score')->nullable(); // 0 to 100
            $table->string('technical_fit')->nullable();
            $table->boolean('availability_confirmed')->default(false);
            $table->boolean('tools_ready')->default(false);
            $table->boolean('rate_accepted')->default(false);
            $table->string('recommendation')->nullable(); // HIGHLY_RECOMMENDED, QUALIFIED, NOT_RECOMMENDED
            $table->text('ai_summary')->nullable();
            $table->longText('full_transcript')->nullable();
            $table->json('audio_recording_urls')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voice_interviews');

        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn(['voice_announced', 'voice_announcement_audio_url']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cv_text', 'years_of_experience']);
        });
    }
};
