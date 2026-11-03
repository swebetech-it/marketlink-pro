<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'referrer_id',
        'referred_id',
        'reward_amount_tzs',
        'status',
        'reward_claimed',
    ];

    protected function casts(): array
    {
        return [
            'reward_amount_tzs' => 'integer',
            'reward_claimed' => 'boolean',
        ];
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referred()
    {
        return $this->belongsTo(User::class, 'referred_id');
    }
}
