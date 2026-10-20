<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->string('id')->primary(); // e.g. "crs_01"
            $table->string('title');
            $table->string('provider_name'); // VETA, SUA, CRDB Academy
            $table->string('category');
            $table->string('duration');
            $table->unsignedBigInteger('price_tzs')->default(0);
            $table->decimal('rating', 3, 2)->default(4.9);
            $table->string('skill_badge_name');
            $table->timestamps();
        });

        Schema::create('course_enrollments', function (Blueprint $table) {
            $table->id();
            $table->string('course_id')->index();
            $table->string('user_id')->index();
            $table->boolean('is_enrolled')->default(true);
            $table->unsignedTinyInteger('progress_percent')->default(0);
            $table->boolean('certificate_issued')->default(false);
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['course_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_enrollments');
        Schema::dropIfExists('courses');
    }
};
