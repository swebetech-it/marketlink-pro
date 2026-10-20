<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'transaction_id',
        'rater_id',
        'rater_name',
        'ratee_id',
        'ratee_name',
        'score',
        'comment',
        'date',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'integer',
        ];
    }

    public function transaction()
    {
        return $this->belongsTo(EscrowTransaction::class, 'transaction_id');
    }
}
