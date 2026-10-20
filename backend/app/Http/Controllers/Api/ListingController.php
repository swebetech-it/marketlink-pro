<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\User;
use App\Services\GeminiAiService;
use App\Services\AiMatchmakingService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ListingController extends Controller
{
    protected GeminiAiService $aiService;
    protected AiMatchmakingService $matchmakingService;

    public function __construct(GeminiAiService $aiService, AiMatchmakingService $matchmakingService)
    {
        $this->aiService = $aiService;
        $this->matchmakingService = $matchmakingService;
    }

    public function index(Request $request)
    {
        $query = Listing::query()->where('status', 'ACTIVE');

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('profession')) {
            $query->where('target_profession', $request->input('profession'));
        }

        if ($request->filled('jobTypeLevel')) {
            $query->where('job_type_level', $request->input('jobTypeLevel'));
        }

        if ($request->filled('region') && $request->input('region') !== 'All Regions') {
            $query->where('location', 'LIKE', '%' . $request->input('region') . '%');
        }

        if ($request->filled('query')) {
            $searchTerm = $request->input('query');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('location', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('target_profession', 'LIKE', "%{$searchTerm}%");
            });
        }

        $listings = $query->orderBy('is_featured', 'desc')
                          ->orderBy('date_posted', 'desc')
                          ->get();

        // If requesting user context is provided, personalize the match score for each listing
        $targetUserId = $request->header('X-User-Id') ?? $request->query('for_user_id');
        $currentUser = null;
        if ($targetUserId) {
            $currentUser = User::find($targetUserId);
        }

        $formatted = $listings->map(function ($l) use ($currentUser) {
            $data = $this->formatListing($l);
            if ($currentUser) {
                $personalScore = $this->matchmakingService->calculateCandidateMatchScore(
                    $currentUser,
                    $l,
                    $l->target_profession
                );
                $data['matchScore'] = $personalScore;
                $data['isTopMatch'] = $personalScore >= 75;
            }
            return $data;
        });

        // If personalizing, sort with top candidate matches first
        if ($currentUser) {
            $formatted = $formatted->sortByDesc('matchScore')->values();
        }

        return response()->json([
            'success' => true,
            'data' => $formatted,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'targetProfession' => 'nullable|string',
            'target_profession' => 'nullable|string',
            'jobTypeLevel' => 'nullable|string',
            'job_type_level' => 'nullable|string',
            'description' => 'required|string',
            'location' => 'required|string',
            'district' => 'nullable|string',
            'compensation' => 'required|string',
            'compensationAmountTzs' => 'nullable|integer',
            'requiredSkills' => 'nullable|array',
            'isEscrowRequired' => 'nullable|boolean',
            'agriCropType' => 'nullable|string',
            'equipmentDetails' => 'nullable|string',
        ]);

        $currentUser = User::find($request->input('posterId')) ?? User::first();
        $posterId = $request->input('posterId') ?? ($currentUser ? $currentUser->id : 'usr_' . Str::random(6));
        $posterName = $request->input('posterName') ?? ($currentUser ? ($currentUser->organization_name ?? $currentUser->full_name) : 'Platform Member');
        $posterVerification = $request->input('posterVerification') ?? ($currentUser ? $currentUser->verification_status : 'PENDING');
        $posterRating = (float)($request->input('posterRating') ?? ($currentUser ? $currentUser->rating : 5.0));

        // 1. Run Gemini AI fraud check
        $fraudAnalysis = $this->aiService->checkFraudRisk(
            $validated['title'],
            $validated['description'],
            $validated['compensation']
        );

        $listingId = 'lst_' . strtolower(Str::random(6));

        // Detect target profession if not explicitly provided
        $targetProfession = $validated['targetProfession']
            ?? $validated['target_profession']
            ?? $this->matchmakingService->detectProfessionCategory($validated['title'] . ' ' . $validated['description']);

        $jobTypeLevel = $validated['jobTypeLevel']
            ?? $validated['job_type_level']
            ?? 'NON_PROFESSIONAL';

        // 2. Create the Listing in database
        $listing = Listing::create([
            'id' => $listingId,
            'poster_id' => $posterId,
            'poster_name' => $posterName,
            'poster_verification' => $posterVerification,
            'poster_rating' => $posterRating,
            'category' => $validated['category'],
            'target_profession' => $targetProfession,
            'job_type_level' => $jobTypeLevel,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'district' => $validated['district'] ?? null,
            'compensation' => $validated['compensation'],
            'compensation_amount_tzs' => $validated['compensationAmountTzs'] ?? 0,
            'required_skills' => $validated['requiredSkills'] ?? ['General Labor'],
            'date_posted' => date('Y-m-d'),
            'deadline' => date('Y-m-d', strtotime('+30 days')),
            'is_featured' => false,
            'is_escrow_required' => $validated['isEscrowRequired'] ?? true,
            'match_score' => 90,
            'matched_candidates_count' => 0,
            'agri_crop_type' => $validated['agriCropType'] ?? null,
            'equipment_details' => $validated['equipmentDetails'] ?? null,
            'is_flagged_suspicious' => $fraudAnalysis['isSuspicious'],
            'fraud_risk_score' => $fraudAnalysis['isSuspicious'] ? 85 : 5,
            'status' => 'ACTIVE',
        ]);

        // 3. Trigger Automated Targeted Matchmaking & SMS Dispatch
        $matchReport = $this->matchmakingService->matchAndNotifyCandidates($listing);

        return response()->json([
            'success' => true,
            'message' => "Kazi imechapishwa kikamilifu! Wafanyakazi {$matchReport['matched_count']} wanaokidhi vigezo huko {$listing->location} wamearifiwa kwa App & SMS.",
            'data' => $this->formatListing($listing),
            'match_report' => $matchReport,
        ], 201);
    }

    public function show(string $id)
    {
        $listing = Listing::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $this->formatListing($listing),
        ]);
    }

    public function destroy(string $id)
    {
        $listing = Listing::findOrFail($id);
        $listing->delete();

        return response()->json([
            'success' => true,
            'message' => 'Listing deleted',
            'data' => true,
        ]);
    }

    protected function formatListing(Listing $l): array
    {
        return [
            'id' => (string)$l->id,
            'posterId' => (string)$l->poster_id,
            'posterName' => $l->poster_name,
            'posterVerification' => $l->poster_verification,
            'posterRating' => (float)$l->poster_rating,
            'category' => $l->category,
            'targetProfession' => $l->target_profession,
            'jobTypeLevel' => $l->job_type_level,
            'title' => $l->title,
            'description' => $l->description,
            'location' => $l->location,
            'district' => $l->district,
            'compensation' => $l->compensation,
            'compensationAmountTzs' => (int)$l->compensation_amount_tzs,
            'requiredSkills' => is_array($l->required_skills) ? $l->required_skills : [],
            'datePosted' => (string)$l->date_posted,
            'deadline' => (string)$l->deadline,
            'isFeatured' => (bool)$l->is_featured,
            'isEscrowRequired' => (bool)$l->is_escrow_required,
            'matchScore' => (int)$l->match_score,
            'matchedCandidatesCount' => (int)$l->matched_candidates_count,
            'agriCropType' => $l->agri_crop_type,
            'equipmentDetails' => $l->equipment_details,
            'isFlaggedSuspicious' => (bool)$l->is_flagged_suspicious,
            'fraudRiskScore' => (int)$l->fraud_risk_score,
        ];
    }

    public function professions()
    {
        $professions = [
            ['code' => 'CLEANING_SANITATION', 'labelSw' => 'Usafi na Mazingira', 'labelEn' => 'Cleaning & Sanitation', 'isProfessional' => false],
            ['code' => 'SECURITY', 'labelSw' => 'Ulinzi na Usalama', 'labelEn' => 'Security Guard', 'isProfessional' => false],
            ['code' => 'DOMESTIC_WORK', 'labelSw' => 'Kazi za Ndani', 'labelEn' => 'Domestic Work / Housekeeping', 'isProfessional' => false],
            ['code' => 'CONSTRUCTION_MASONRY', 'labelSw' => 'Ujenzi na Useremala', 'labelEn' => 'Construction & Masonry', 'isProfessional' => false],
            ['code' => 'ELECTRICAL', 'labelSw' => 'Ufundi Umeme na Sola', 'labelEn' => 'Electrical & Solar Technical', 'isProfessional' => true],
            ['code' => 'PLUMBING', 'labelSw' => 'Mabomba ya Maji', 'labelEn' => 'Plumbing Services', 'isProfessional' => false],
            ['code' => 'TRANSPORT_DRIVING', 'labelSw' => 'Udereva na Usafirishaji', 'labelEn' => 'Driving & Logistics', 'isProfessional' => false],
            ['code' => 'AGRICULTURE', 'labelSw' => 'Kilimo na Uvunaji', 'labelEn' => 'Agriculture & Harvesting', 'isProfessional' => false],
            ['code' => 'HOSPITALITY_COOKING', 'labelSw' => 'Upishi na Hoteli', 'labelEn' => 'Cooking & Hospitality', 'isProfessional' => false],
            ['code' => 'ACCOUNTING_FINANCE', 'labelSw' => 'Uhasibu na Fedha', 'labelEn' => 'Accounting & Finance', 'isProfessional' => true],
            ['code' => 'IT_SOFTWARE', 'labelSw' => 'Tehama na Programu', 'labelEn' => 'IT & Software Development', 'isProfessional' => true],
            ['code' => 'HEALTHCARE', 'labelSw' => 'Afya na Uuguzi', 'labelEn' => 'Healthcare & Nursing', 'isProfessional' => true],
            ['code' => 'EDUCATION_TEACHING', 'labelSw' => 'Ualimu na Masomo', 'labelEn' => 'Teaching & Tutoring', 'isProfessional' => true],
            ['code' => 'MECHANICS', 'labelSw' => 'Ufundi Magari na Mitambo', 'labelEn' => 'Mechanics & Automotive', 'isProfessional' => false],
            ['code' => 'GENERAL_LABOR', 'labelSw' => 'Kibarua cha Jumla', 'labelEn' => 'General Labor', 'isProfessional' => false],
        ];

        return response()->json([
            'success' => true,
            'data' => $professions,
        ]);
    }
}
