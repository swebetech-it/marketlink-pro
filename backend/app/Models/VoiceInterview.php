<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoiceInterview extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'listing_id',
        'candidate_id',
        'candidate_name',
        'candidate_phone',
        'status',
        'call_session_id',
        'questions',
        'answers',
        'current_question_index',
        'overall_score',
        'technical_fit',
        'availability_confirmed',
        'tools_ready',
        'rate_accepted',
        'recommendation',
        'ai_summary',
        'full_transcript',
        'audio_recording_urls',
    ];

    protected function casts(): array
    {
        return [
            'questions' => 'array',
            'answers' => 'array',
            'audio_recording_urls' => 'array',
            'current_question_index' => 'integer',
            'overall_score' => 'integer',
            'availability_confirmed' => 'boolean',
            'tools_ready' => 'boolean',
            'rate_accepted' => 'boolean',
        ];
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class, 'listing_id');
    }

    public function candidate()
    {
        return $this->belongsTo(User::class, 'candidate_id');
    }
}
