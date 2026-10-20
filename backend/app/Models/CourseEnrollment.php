<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'user_id',
        'is_enrolled',
        'progress_percent',
        'certificate_issued',
    ];

    protected function casts(): array
    {
        return [
            'is_enrolled' => 'boolean',
            'progress_percent' => 'integer',
            'certificate_issued' => 'boolean',
        ];
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
