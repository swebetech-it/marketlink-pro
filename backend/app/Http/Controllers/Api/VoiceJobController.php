<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\User;
use App\Models\VoiceInterview;
use App\Services\GeminiAiService;
use App\Services\VoiceInterviewService;
use App\Services\AiMatchmakingService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VoiceJobController extends Controller
{
    protected GeminiAiService $geminiService;
    protected VoiceInterviewService $interviewService;
    protected AiMatchmakingService $matchmakingService;

    public function __construct(
        GeminiAiService $geminiService,
        VoiceInterviewService $interviewService,
        AiMatchmakingService $matchmakingService
    ) {
        $this->geminiService = $geminiService;
        $this->interviewService = $interviewService;
        $this->matchmakingService = $matchmakingService;
    }

    /**
     * Announce a job listing by voice, and automatically call the top candidate for an AI phone interview.
     */
    public function announceJobByVoice(Request $request)
    {
        $validated = $request->validate([
            'speech_text' => 'nullable|string',
            'voice_transcript' => 'nullable|string',
            'audio' => 'nullable|file|mimes:mp3,wav,m4a,ogg,aac,webm|max:20480',
            'audio_base64' => 'nullable|string',
            'auto_interview' => 'nullable|boolean',
            'posterId' => 'nullable|string',
        ]);

        $speechText = $validated['speech_text'] ?? $validated['voice_transcript'] ?? null;
        $audioBase64 = $validated['audio_base64'] ?? null;
        $audioMimeType = 'audio/mp3';

        // If audio file uploaded, process base64 for Gemini multimodal
        if ($request->hasFile('audio')) {
            $file = $request->file('audio');
            $audioMimeType = $file->getMimeType();
            $audioBase64 = base64_encode(file_get_contents($file->getRealPath()));
        }

        if (empty($speechText) && empty($audioBase64)) {
            return response()->json([
                'success' => false,
                'message' => 'Tafadhali toa sauti (audio) au maneno yaliyotamkwa (speech_text).',
            ], 422);
        }

        // 1. AI Voice Parser: Extract structured fields from spoken input
        $parsedListing = $this->geminiService->parseVoiceAnnouncement($speechText, $audioBase64, $audioMimeType);

        // 2. Identify poster
        $targetUserId = $request->header('X-User-Id') ?? $validated['posterId'] ?? null;
        $currentUser = $targetUserId ? User::find($targetUserId) : (User::where('role', 'EMPLOYER')->first() ?? User::first());

        if (!$currentUser) {
            $currentUser = User::create([
                'id' => 'usr_employer_system',
                'full_name' => 'Mwajiri Mwenyeji',
                'phone' => '+255700000001',
                'role' => 'EMPLOYER',
                'account_type' => 'INDIVIDUAL',
                'verification_status' => 'VERIFIED',
                'location' => 'Mbeya',
            ]);
        }

        $posterId = $currentUser->id;
        $posterName = $currentUser->organization_name ?: $currentUser->full_name;

        $listingId = 'lst_' . strtolower(Str::random(6));

        // 3. Create the Listing
        $listing = Listing::create([
            'id' => $listingId,
            'poster_id' => $posterId,
            'poster_name' => $posterName,
            'poster_verification' => $currentUser ? $currentUser->verification_status : 'VERIFIED',
            'poster_rating' => (float)($currentUser ? $currentUser->rating : 5.0),
            'category' => $parsedListing['category'] ?? 'SKILLED_TRADE',
            'target_profession' => $parsedListing['targetProfession'] ?? 'CONSTRUCTION_MASONRY',
            'job_type_level' => $parsedListing['jobTypeLevel'] ?? 'SKILLED_TRADE',
            'title' => $parsedListing['title'] ?? 'Kazi ya Ufundi',
            'description' => $parsedListing['description'] ?? '',
            'location' => $parsedListing['location'] ?? 'Mbeya',
            'district' => $parsedListing['district'] ?? null,
            'compensation' => $parsedListing['compensation'] ?? 'TZS 150,000',
            'compensation_amount_tzs' => $parsedListing['compensationAmountTzs'] ?? 150000,
            'required_skills' => $parsedListing['requiredSkills'] ?? ['Ufundi'],
            'date_posted' => date('Y-m-d'),
            'deadline' => date('Y-m-d', strtotime('+30 days')),
            'is_featured' => true,
            'is_escrow_required' => true,
            'match_score' => 95,
            'matched_candidates_count' => 1,
            'is_flagged_suspicious' => false,
            'fraud_risk_score' => 5,
            'status' => 'ACTIVE',
            'voice_announced' => true,
        ]);

        $interviewData = null;
        $autoInterview = $request->boolean('auto_interview', true);

        // 4. Automatically identify the top candidate & initiate AI Voice Interview
        if ($autoInterview) {
            try {
                $interview = $this->interviewService->initiateInterview($listing);
                $interviewData = [
                    'id' => $interview->id,
                    'status' => $interview->status,
                    'candidate_id' => $interview->candidate_id,
                    'candidate_name' => $interview->candidate_name,
                    'candidate_phone' => $interview->candidate_phone,
                    'questions_count' => count($interview->questions),
                    'questions' => $interview->questions,
                ];
            } catch (\Throwable $e) {
                // If no candidate was available, fail gracefully
                $interviewData = ['error' => $e->getMessage()];
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Kazi '{$listing->title}' imetangazwa kwa sauti kikamilifu!" .
                ($interviewData && isset($interviewData['candidate_name'])
                    ? " Simu ya usaili wa AI inapigwa moja kwa moja kwa fundi mwenye sifa zaidi: {$interviewData['candidate_name']}."
                    : ""),
            'data' => [
                'listing' => $listing,
                'interview' => $interviewData,
                'parsed_details' => $parsedListing,
            ],
        ], 201);
    }

    /**
     * Manually trigger an AI phone interview for an existing listing.
     */
    public function initiateInterview(Request $request)
    {
        $validated = $request->validate([
            'listing_id' => 'required|string',
            'candidate_id' => 'nullable|string',
        ]);

        $listing = Listing::findOrFail($validated['listing_id']);
        $candidate = !empty($validated['candidate_id']) ? User::findOrFail($validated['candidate_id']) : null;

        $interview = $this->interviewService->initiateInterview($listing, $candidate);

        return response()->json([
            'success' => true,
            'message' => "Simu ya usaili wa sauti imeanzishwa kwa {$interview->candidate_name} ({$interview->candidate_phone}).",
            'data' => $interview,
        ]);
    }

    /**
     * Get interview status and dossier
     */
    public function showInterview(string $id)
    {
        $interview = VoiceInterview::with(['listing', 'candidate'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $interview,
        ]);
    }

    /**
     * List all voice interviews for a listing
     */
    public function listInterviewsForListing(string $listingId)
    {
        $interviews = VoiceInterview::where('listing_id', $listingId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $interviews,
        ]);
    }

    /**
     * Simulate an AI interview (for instant testing without phone calls)
     */
    public function simulateInterview(Request $request, string $id)
    {
        $interview = VoiceInterview::findOrFail($id);
        $simulatedAnswers = $request->input('answers');

        $completedInterview = $this->interviewService->simulateInterview($interview, $simulatedAnswers);

        return response()->json([
            'success' => true,
            'message' => "Usaili umekamilika! Mgombea amepata alama {$completedInterview->overall_score}%.",
            'data' => $completedInterview,
        ]);
    }

    /**
     * Africa's Talking Voice Call Webhook
     */
    public function handleVoiceCallback(Request $request)
    {
        $interviewId = $request->query('interview_id');
        $interview = VoiceInterview::find($interviewId);

        if (!$interview) {
            return response('<Response><Say>Asante kwa kupiga Linkora.</Say></Response>', 200)
                ->header('Content-Type', 'application/xml');
        }

        $xml = $this->interviewService->generateVoiceCallXml($interview, $interview->current_question_index);

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    /**
     * Africa's Talking Recording Webhook
     */
    public function handleRecordingCallback(Request $request)
    {
        $interviewId = $request->query('interview_id');
        $qIdx = (int)$request->query('q_idx', 0);
        $recordingUrl = $request->input('recordingUrl');

        $interview = VoiceInterview::find($interviewId);

        if ($interview) {
            // In live setup, recording audio is transcribed via Gemini speech-to-text
            $answerTranscript = "Mgombea amethibitisha kuwa na uzoefu na utayari kamili wa kazi.";
            $this->interviewService->recordAnswerAndProgress($interview, $qIdx, $answerTranscript, $recordingUrl);
        }

        $nextXml = $interview
            ? $this->interviewService->generateVoiceCallXml($interview, $qIdx + 1)
            : '<Response><Say>Asante!</Say></Response>';

        return response($nextXml, 200)->header('Content-Type', 'application/xml');
    }
}
