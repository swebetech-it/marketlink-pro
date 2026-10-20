<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialProduct extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'provider_name',
        'product_name',
        'category',
        'max_amount_tzs',
        'interest_rate',
        'min_reputation_score',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'max_amount_tzs' => 'integer',
            'min_reputation_score' => 'integer',
        ];
    }
}
