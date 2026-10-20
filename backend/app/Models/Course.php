<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'title',
        'provider_name',
        'category',
        'duration',
        'price_tzs',
        'rating',
        'skill_badge_name',
    ];

    protected function casts(): array
    {
        return [
            'price_tzs' => 'integer',
            'rating' => 'float',
        ];
    }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class, 'course_id');
    }
}
