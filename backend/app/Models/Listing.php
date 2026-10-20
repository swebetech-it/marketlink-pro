<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'poster_id',
        'poster_name',
        'poster_verification',
        'poster_rating',
        'category',
        'target_profession',
        'job_type_level',
        'title',
        'description',
        'location',
        'district',
        'compensation',
        'compensation_amount_tzs',
        'required_skills',
        'date_posted',
        'deadline',
        'is_featured',
        'is_escrow_required',
        'match_score',
        'matched_candidates_count',
        'agri_crop_type',
        'equipment_details',
        'is_flagged_suspicious',
        'fraud_risk_score',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'poster_rating' => 'float',
            'compensation_amount_tzs' => 'integer',
            'required_skills' => 'array',
            'is_featured' => 'boolean',
            'is_escrow_required' => 'boolean',
            'match_score' => 'integer',
            'matched_candidates_count' => 'integer',
            'is_flagged_suspicious' => 'boolean',
            'fraud_risk_score' => 'integer',
        ];
    }

    public function poster()
    {
        return $this->belongsTo(User::class, 'poster_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'listing_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'listing_id');
    }
}
