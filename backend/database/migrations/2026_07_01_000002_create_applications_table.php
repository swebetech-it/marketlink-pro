<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->string('id')->primary(); // e.g. "app_101", UUID
            $table->string('listing_id')->index();
            $table->string('listing_title');
            $table->string('applicant_id')->index();
            $table->string('applicant_name');
            $table->text('pitch_message');
            $table->string('status')->default('Applied'); // Applied, Shortlisted, Accepted, Completed, Rejected
            $table->date('applied_date');
            $table->timestamps();

            $table->foreign('listing_id')->references('id')->on('listings')->onDelete('cascade');
            $table->foreign('applicant_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
