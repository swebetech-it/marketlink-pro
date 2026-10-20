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
            $table->string('account_type')->default('INDIVIDUAL')->after('role'); // INDIVIDUAL, ORGANIZATION
            $table->string('organization_name')->nullable()->after('account_type');
            $table->string('tin_number')->nullable()->after('organization_name');
            $table->string('industry_sector')->nullable()->after('tin_number');
            $table->string('primary_profession')->nullable()->index()->after('industry_sector');
            $table->string('district')->nullable()->index()->after('location');
        });

        Schema::table('listings', function (Blueprint $table) {
            $table->string('target_profession')->nullable()->index()->after('category');
            $table->string('job_type_level')->default('NON_PROFESSIONAL')->after('target_profession'); // NON_PROFESSIONAL, SKILLED_TRADE, PROFESSIONAL, CASUAL_GIG
            $table->string('district')->nullable()->index()->after('location');
            $table->integer('matched_candidates_count')->default(0)->after('match_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn(['target_profession', 'job_type_level', 'district', 'matched_candidates_count']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['account_type', 'organization_name', 'tin_number', 'industry_sector', 'primary_profession', 'district']);
        });
    }
};
