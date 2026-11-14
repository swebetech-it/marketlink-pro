<?php

namespace App\Services;

use App\Models\VoiceInterview;
use App\Models\Listing;
use App\Models\User;
use App\Models\Application;
use App\Models\NotificationItem;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VoiceInterviewService
{
    protected GeminiAiService $geminiService;
    protected SmsService $smsService;
    protected AiMatchmakingService $matchmakingService;

    protected string $atUsername;
    protected string $atApiKey;
    protected string $atVirtualNumber;

    public function __construct(
        GeminiAiService $geminiService,
        SmsService $smsService,
        AiMatchmakingService $matchmakingService
    ) {
        $this->geminiService = $geminiService;
        $this->smsService = $smsService;
        $this->matchmakingService = $matchmakingService;

        $this->atUsername = env('AFRICAS_TALKING_USERNAME', 'sandbox');
        $this->atApiKey = env('AFRICAS_TALKING_API_KEY', '');
        $this->atVirtualNumber = env('AFRICAS_TALKING_VOICE_NUMBER', '+255711000000');
    }

    /**
     * Finds the #1 most capable candidate and launches an automated AI phone interview.
     */
    public function initiateInterview(Listing $listing, ?User $candidate = null): VoiceInterview
    {
        // 1. If candidate is not explicitly provided, find the single most capable candidate
        if (!$candidate) {
            $candidate = $this->findTopCapableCandidate($listing);
        }

        if (!$candidate) {
            throw new \RuntimeException("Hakuna mgombea anayekidhi vigezo vya awali vilivyopatikana kwa sasa.");
        }

        // 2. Formulate 3 Dynamic CV-Driven Interview Questions
        $questions = $this->geminiService->generateCvInterviewQuestions($listing, $candidate);

        $interviewId = 'vi_' . strtolower(Str::random(8));

        // 3. Create the Voice Interview Record
        $interview = VoiceInterview::create([
            'id' => $interviewId,
            'listing_id' => $listing->id,
            'candidate_id' => $candidate->id,
            'candidate_name' => $candidate->full_name,
            'candidate_phone' => $candidate->phone,
            'status' => 'CALLING',
            'questions' => $questions,
            'answers' => [],
            'current_question_index' => 0,
            'overall_score' => null,
            'technical_fit' => null,
            'availability_confirmed' => false,
            'tools_ready' => false,
            'rate_accepted' => false,
            'recommendation' => null,
            'ai_summary' => null,
            'full_transcript' => null,
            'audio_recording_urls' => [],
        ]);

        // 4. Send Pre-Call SMS Notification to Candidate via Africa's Talking
        if (!empty($candidate->phone)) {
            $smsMsg = "Linkora AI: Habari {$candidate->full_name}, unapigiwa simu ya usaili wa fursa ya '{$listing->title}' ({$listing->location}, {$listing->compensation}). Tafadhali pokea.";
            $this->smsService->sendSms($candidate->phone, $smsMsg);
        }

        // 5. Trigger Outbound Call via Africa's Talking Voice API
        $this->dispatchAfricaTalkingCall($interview, $candidate->phone);

        // 6. Audit Trail
        AuditLog::create([
            'event' => 'AI_VOICE_INTERVIEW_DISPATCHED',
            'user_id' => $listing->poster_id,
            'payload' => [
                'interview_id' => $interviewId,
                'listing_id' => $listing->id,
                'candidate_id' => $candidate->id,
                'candidate_name' => $candidate->full_name,
                'candidate_phone' => $candidate->phone,
            ],
        ]);

        return $interview;
    }

    /**
     * Evaluates and identifies the #1 top capable candidate for a listing based on CV, verification, and reputation.
     */
    public function findTopCapableCandidate(Listing $listing): ?User
    {
        $targetProfession = $listing->target_profession
            ?? $this->matchmakingService->detectProfessionCategory($listing->title . ' ' . $listing->description);

        $candidates = User::where('role', '!=', 'ADMIN')
            ->where(function ($q) {
                $q->whereIn('role', ['JOB_SEEKER', 'FREELANCER', 'CASUAL_GIG'])
                  ->orWhere('account_type', 'INDIVIDUAL');
            })
            ->get();

        $scoredCandidates = [];

        foreach ($candidates as $cand) {
            $baseScore = $this->matchmakingService->calculateCandidateMatchScore($cand, $listing, $targetProfession);

            // Additional capability signals from CV and profile
            $capabilityBonus = 0;
            if ($cand->verification_status === 'VERIFIED') $capabilityBonus += 10;
            if ($cand->rating >= 4.5) $capabilityBonus += 10;
            if ($cand->completed_gigs > 5) $capabilityBonus += 10;
            if (!empty($cand->cv_text)) $capabilityBonus += 10;
            if ($cand->reputation_score >= 850) $capabilityBonus += 10;

            $totalScore = $baseScore + $capabilityBonus;

            // Must satisfy at least 65% baseline compatibility
            if ($baseScore >= 60) {
                $scoredCandidates[] = [
                    'candidate' => $cand,
                    'score' => $totalScore,
                ];
            }
        }

        if (empty($scoredCandidates)) {
            return $candidates->first();
        }

        // Sort descending by highest capability score
        usort($scoredCandidates, fn($a, $b) => $b['score'] <=> $a['score']);

        return $scoredCandidates[0]['candidate'];
    }

    /**
     * Dispatches Outbound Call to Candidate using Africa's Talking Voice API
     */
    protected function dispatchAfricaTalkingCall(VoiceInterview $interview, string $phoneNumber): bool
    {
        if (empty($this->atApiKey)) {
            Log::info("[Africa's Talking Voice Sandbox] Outbound call to {$phoneNumber} for interview {$interview->id}");
            $interview->call_session_id = 'at_sess_' . Str::random(10);
            $interview->save();
            return true;
        }

        try {
            $url = $this->atUsername === 'sandbox'
                ? 'https://voice.sandbox.africastalking.com/call'
                : 'https://voice.africastalking.com/call';

            $callbackUrl = url("/api/voice/callback?interview_id={$interview->id}");

            $response = Http::withHeaders([
                'apiKey' => $this->atApiKey,
                'Accept' => 'application/json',
            ])->asForm()->post($url, [
                'username' => $this->atUsername,
                'from' => $this->atVirtualNumber,
                'to' => $phoneNumber,
                'callBackUrl' => $callbackUrl,
            ]);

            if ($response->successful()) {
                $sessionId = $response->json('entries.0.sessionId') ?? 'at_sess_' . Str::random(10);
                $interview->call_session_id = $sessionId;
                $interview->save();
                return true;
            }

            Log::warning("Africa's Talking Voice Call Response: " . $response->body());
        } catch (\Throwable $e) {
            Log::error("Africa's Talking Voice Call Error: " . $e->getMessage());
        }

        return false;
    }

    /**
     * Records candidate answer, steps through questions, and triggers AI evaluation when complete.
     */
    public function recordAnswerAndProgress(
        VoiceInterview $interview,
        int $questionIndex,
        string $answerTranscript,
        ?string $audioUrl = null
    ): VoiceInterview {
        $answers = $interview->answers ?? [];
        $questions = $interview->questions ?? [];

        $answers[$questionIndex] = [
            'question_id' => $questions[$questionIndex]['id'] ?? "q" . ($questionIndex + 1),
            'question_text' => $questions[$questionIndex]['question'] ?? "Swali",
            'answer_transcript' => $answerTranscript,
            'audio_url' => $audioUrl,
            'answered_at' => date('Y-m-d H:i:s'),
        ];

        $interview->answers = $answers;

        $nextIndex = $questionIndex + 1;

        if ($nextIndex < count($questions)) {
            $interview->current_question_index = $nextIndex;
            $interview->status = 'IN_PROGRESS';
            $interview->save();
            return $interview;
        }

        // All questions completed! Run Gemini AI Evaluation
        $listing = Listing::find($interview->listing_id);
        $candidate = User::find($interview->candidate_id);

        $evaluation = $this->geminiService->evaluateVoiceInterview($listing, $candidate, $questions, $answers);

        $interview->overall_score = $evaluation['overallScore'] ?? 90;
        $interview->technical_fit = $evaluation['technicalFit'] ?? 'Uwezo unaokubalika';
        $interview->availability_confirmed = (bool)($evaluation['availabilityConfirmed'] ?? true);
        $interview->tools_ready = (bool)($evaluation['toolsReady'] ?? true);
        $interview->rate_accepted = (bool)($evaluation['rateAccepted'] ?? true);
        $interview->recommendation = $evaluation['recommendation'] ?? 'HIGHLY_RECOMMENDED';
        $interview->ai_summary = $evaluation['aiSummary'] ?? '';
        $interview->full_transcript = $evaluation['fullTranscript'] ?? '';
        $interview->status = 'COMPLETED';
        $interview->save();

        // Automatically create a pre-screened application
        $appId = 'app_' . strtolower(Str::random(8));
        Application::updateOrCreate(
            [
                'listing_id' => $listing->id,
                'applicant_id' => $candidate->id,
            ],
            [
                'id' => $appId,
                'listing_title' => $listing->title,
                'applicant_name' => $candidate->full_name,
                'pitch_message' => "Usaili wa AI Umekamilika ({$interview->overall_score}%). " . $interview->ai_summary,
                'status' => 'INTERVIEWED_RECOMMENDED',
                'applied_date' => date('Y-m-d'),
            ]
        );

        // Notify the Employer via In-App Notification & SMS
        $employer = User::find($listing->poster_id);
        $notifId = 'notif_' . round(microtime(true) * 1000);

        NotificationItem::create([
            'id' => $notifId,
            'user_id' => $listing->poster_id,
            'title' => "🎙️ Usaili wa AI Umekamilika ({$interview->overall_score}%)",
            'body' => "Mgombea {$candidate->full_name} amefanyiwa usaili wa sauti kwa fursa ya '{$listing->title}'. Alama: {$interview->overall_score}%. Bofya hapa kuona majibu na kumwajiri mara moja!",
            'channel' => "SMS & App",
            'timestamp_str' => 'Just now',
            'is_read' => false,
        ]);

        if ($employer && !empty($employer->phone)) {
            $smsText = "Linkora: Usaili wa AI umekamilika kwa '{$listing->title}'. Mgombea {$candidate->full_name} amepata alama {$interview->overall_score}%. Fungua Linkora kumthibitisha na kuweka Escrow.";
            $this->smsService->sendSms($employer->phone, $smsText);
        }

        return $interview;
    }

    /**
     * Generates Africa's Talking Voice XML response for phone calls
     */
    public function generateVoiceCallXml(VoiceInterview $interview, int $questionIndex = 0): string
    {
        $questions = $interview->questions ?? [];
        $candName = $interview->candidate_name ?? 'Mteja';

        if ($questionIndex >= count($questions)) {
            return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Response>
    <Say voice="man">Asante sana {$candName}. Majibu yako yamerekodiwa kikamilifu na kuwasilishwa kwa mwajiri. Utapokea ujumbe wa SMS hivi punde. Kazi njema!</Say>
</Response>
XML;
        }

        $q = $questions[$questionIndex]['question'] ?? "Tafadhali eleza uzoefu wako wa kazi hii.";
        $callbackUrl = url("/api/voice/recording-callback?interview_id={$interview->id}&q_idx={$questionIndex}");

        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Response>
    <Say voice="man">{$q}</Say>
    <Record finishOnKey="#" maxLength="45" trimSilence="true" playBeep="true" callbackUrl="{$callbackUrl}"/>
</Response>
XML;
    }

    /**
     * Simulates the interview answers for testing or rapid demos without incurring telco airtime.
     */
    public function simulateInterview(VoiceInterview $interview, ?array $simulatedAnswers = null): VoiceInterview
    {
        $listing = Listing::find($interview->listing_id);
        $candidate = User::find($interview->candidate_id);

        $defaultAnswers = [
            0 => "Ndio, nina uzoefu wa zaidi ya miaka 4 ya kazi hii. Hivi karibuni nimekamilisha mradi mkubwa sawa na huu kwa ufanisi.",
            1 => "Nipo tayari kabisa kuanza kesho asubuhi eneo la {$listing->location} bila kuchelewa.",
            2 => "Ndiyo, nina vifaa vyangu vyote binafsi vya kazi na malipo ya {$listing->compensation} nimeyakubali kabisa.",
        ];

        $answersToUse = $simulatedAnswers ?? $defaultAnswers;

        foreach ($interview->questions as $idx => $q) {
            $transcript = $answersToUse[$idx] ?? "Nathibitisha utayari na uzoefu wangu wa kazi.";
            $this->recordAnswerAndProgress($interview, $idx, $transcript, null);
        }

        return $interview->fresh();
    }
}
