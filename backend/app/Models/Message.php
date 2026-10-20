<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'listing_id',
        'sender_id',
        'sender_name',
        'receiver_id',
        'receiver_name',
        'text',
        'timestamp_str',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class, 'listing_id');
    }
}
