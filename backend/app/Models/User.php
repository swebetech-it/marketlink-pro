<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // The Central User Registry resides in the default database

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'full_name',
        'phone',
        'email',
        'password',
        'role',
        'account_type',
        'organization_name',
        'tin_number',
        'industry_sector',
        'primary_profession',
        'district',
        'verification_status',
        'nida_number',
        'location',
        'bio',
        'skills',
        'rating',
        'total_transactions',
        'completed_gigs',
        'reputation_score',
        'community_vouches',
        'badges',
        'mfa_enabled',
        'language',
        'avatar_url',
        'password_reset_otp',
        'password_reset_expires_at',
        'referral_code',
        'referred_by',
        'referral_count',
        'referral_earnings_tzs',
        'fcm_token',
        'device_type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'password_reset_otp',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password_reset_expires_at' => 'datetime',
            'password' => 'hashed',
            'skills' => 'array',
            'badges' => 'array',
            'rating' => 'float',
            'total_transactions' => 'integer',
            'completed_gigs' => 'integer',
            'reputation_score' => 'integer',
            'community_vouches' => 'integer',
            'referral_count' => 'integer',
            'referral_earnings_tzs' => 'integer',
            'mfa_enabled' => 'boolean',
        ];
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function listings()
    {
        return $this->hasMany(Listing::class, 'poster_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'applicant_id');
    }

    public function escrowPayerTransactions()
    {
        return $this->hasMany(EscrowTransaction::class, 'payer_id');
    }

    public function escrowPayeeTransactions()
    {
        return $this->hasMany(EscrowTransaction::class, 'payee_id');
    }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class, 'user_id');
    }
}
