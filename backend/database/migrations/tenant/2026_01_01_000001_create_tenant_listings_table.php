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
        Schema::connection('tenant')->create('listings', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('poster_id')->index();
            $table->string('poster_name');
            $table->string('poster_verification')->default('VERIFIED');
            $table->decimal('poster_rating', 3, 2)->default(5.0);
            $table->string('category')->index();
            $table->string('target_profession')->nullable()->index();
            $table->string('job_type_level')->default('NON_PROFESSIONAL');
            $table->string('title');
            $table->text('description');
            $table->string('location')->index();
            $table->string('district')->nullable()->index();
            $table->string('compensation');
            $table->unsignedBigInteger('compensation_amount_tzs')->default(0);
            $table->json('required_skills')->nullable();
            $table->date('date_posted');
            $table->date('deadline');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_escrow_required')->default(true);
            $table->integer('match_score')->default(85);
            $table->integer('matched_candidates_count')->default(0);
            $table->string('agri_crop_type')->nullable();
            $table->string('equipment_details')->nullable();
            $table->boolean('is_flagged_suspicious')->default(false);
            $table->integer('fraud_risk_score')->default(0);
            $table->string('status')->default('ACTIVE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('listings');
    }
};
