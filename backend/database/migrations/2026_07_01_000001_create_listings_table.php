<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->string('id')->primary(); // e.g. "lst_001", UUID
            $table->string('poster_id')->index();
            $table->string('poster_name');
            $table->string('poster_verification')->default('VERIFIED');
            $table->decimal('poster_rating', 3, 2)->default(5.0);
            $table->string('category')->index(); // EMPLOYMENT, SKILLED_SERVICE, CASUAL_GIG, BUSINESS_OPP, AGRI_LABOR, EQUIPMENT_RENTAL
            $table->string('title');
            $table->text('description');
            $table->string('location')->index();
            $table->string('compensation');
            $table->unsignedBigInteger('compensation_amount_tzs')->default(0);
            $table->json('required_skills')->nullable();
            $table->date('date_posted');
            $table->date('deadline');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_escrow_required')->default(true);
            $table->integer('match_score')->default(85);
            $table->string('agri_crop_type')->nullable();
            $table->string('equipment_details')->nullable();
            $table->boolean('is_flagged_suspicious')->default(false);
            $table->integer('fraud_risk_score')->default(0);
            $table->string('status')->default('ACTIVE'); // ACTIVE, CLOSED, COMPLETED, SUSPENDED
            $table->timestamps();

            $table->foreign('poster_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
