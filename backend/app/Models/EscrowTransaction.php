<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EscrowTransaction extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'listing_id',
        'listing_title',
        'payer_id',
        'payer_name',
        'payee_id',
        'payee_name',
        'amount_tzs',
        'fee_tzs',
        'escrow_status',
        'payment_method',
        'timestamp_str',
        'audit_code',
        'gateway_ref',
        'dispute_reason',
    ];

    protected function casts(): array
    {
        return [
            'amount_tzs' => 'integer',
            'fee_tzs' => 'integer',
        ];
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class, 'listing_id');
    }

    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    public function payee()
    {
        return $this->belongsTo(User::class, 'payee_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'transaction_id');
    }
}
