<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'skills' => 'array',
            'badges' => 'array',
            'rating' => 'float',
            'total_transactions' => 'integer',
            'completed_gigs' => 'integer',
            'reputation_score' => 'integer',
            'community_vouches' => 'integer',
            'mfa_enabled' => 'boolean',
        ];
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
