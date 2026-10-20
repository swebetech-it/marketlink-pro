<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UssdSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'phone_number',
        'menu_level',
        'last_input',
        'session_data',
    ];

    protected function casts(): array
    {
        return [
            'menu_level' => 'integer',
            'session_data' => 'array',
        ];
    }
}
