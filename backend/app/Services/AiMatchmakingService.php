<?php

namespace App\Services;

use App\Models\Listing;
use App\Models\User;
use App\Models\NotificationItem;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Log;

class AiMatchmakingService
{
    protected SmsService $smsService;
    protected GeminiAiService $geminiService;

    // Tanzanian Job Sector Taxonomy (Keywords for Smart Matching)
    protected array $professionTaxonomy = [
        'CLEANING_SANITATION' => [
            'labels' => ['Usafi na Mazingira', 'Cleaning & Sanitation'],
            'keywords' => ['usafi', 'cleaner', 'cleaning', 'housekeeping', 'janitor', 'kuosha', 'usafi wa ofisi', 'usafi wa majumbani', 'dobi', 'laundry', 'sanitation', 'takataka']
        ],
        'SECURITY' => [
            'labels' => ['Ulinzi na Usalama', 'Security Guard'],
            'keywords' => ['ulinzi', 'security', 'guard', 'askari', 'mlinzi', 'cctv', 'doria', 'usalama']
        ],
        'DOMESTIC_WORK' => [
            'labels' => ['Kazi za Ndani', 'Domestic Work'],
            'keywords' => ['kazi za ndani', 'yaya', 'housemaid', 'mpishi wa nyumbani', 'domestic', 'dada wa kazi', 'kole']
        ],
        'CONSTRUCTION_MASONRY' => [
            'labels' => ['Ujenzi na Useremala', 'Construction & Masonry'],
            'keywords' => ['ujenzi', 'fundi ujenzi', 'mason', 'plastering', 'matofali', 'seremala', 'carpenter', 'rangi', 'painter', 'zege', 'welder', 'kuchomelea']
        ],
        'ELECTRICAL' => [
            'labels' => ['Umeme na Sola', 'Electrical & Solar'],
            'keywords' => ['umeme', 'fundi umeme', 'electrical', 'solar', 'wiring', 'inverter', 'sola', 'micro-grid', 'panel', 'betri']
        ],
        'PLUMBING' => [
            'labels' => ['Mabomba', 'Plumbing'],
            'keywords' => ['bomba', 'fundi bomba', 'plumbing', 'pipes', 'drainage', 'tanki', 'sink']
        ],
        'TRANSPORT_DRIVING' => [
            'labels' => ['Udereva na Usafirishaji', 'Driving & Transport'],
            'keywords' => ['udereva', 'dereva', 'driver', 'bodaboda', 'bajaji', 'lori', 'courier', 'pikipiki', 'leseni']
        ],
        'AGRICULTURE' => [
            'labels' => ['Kilimo na Ufugaji', 'Agriculture & Farming'],
            'keywords' => ['kilimo', 'mvunaji', 'kupalilia', 'mifugo', 'agri', 'tractor', 'trekta', 'kahawa', 'mpunga', 'chai', 'avocado', 'palizi', 'mbolea', 'mbegu']
        ],
        'HOSPITALITY_COOKING' => [
            'labels' => ['Upishi na Hoteli', 'Hospitality & Cooking'],
            'keywords' => ['upishi', 'mpishi', 'chef', 'cook', 'waiter', 'hotel', 'baker', 'uokaji', 'huduma ya chakula', 'mgahawa']
        ],
        'ACCOUNTING_FINANCE' => [
            'labels' => ['Uhasibu na Fedha', 'Accounting & Finance'],
            'keywords' => ['uhasibu', 'mhasibu', 'accountant', 'finance', 'tally', 'auditing', 'hesabu', 'bookkeeper', 'tra', 'kodi']
        ],
        'IT_SOFTWARE' => [
            'labels' => ['Tehama na Programu', 'IT & Software'],
            'keywords' => ['it', 'software', 'developer', 'computer', 'network', 'graphics', 'website', 'system', 'programming', 'database']
        ],
        'HEALTHCARE' => [
            'labels' => ['Afya na Uuguzi', 'Healthcare & Nursing'],
            'keywords' => ['afya', 'nursing', 'uguzi', 'mkunga', 'clinical', 'pharmacy', 'daktari', 'nurse', 'maabara']
        ],
        'EDUCATION_TEACHING' => [
            'labels' => ['Ualimu na Elimu', 'Teaching & Education'],
            'keywords' => ['ualimu', 'mwalimu', 'tutor', 'teaching', 'education', 'madarasa', 'masomo', 'shule']
        ],
        'MECHANICS' => [
            'labels' => ['Ufundi Mitambo na Gereji', 'Mechanics & Automotive'],
            'keywords' => ['gereji', 'mechanic', 'fundi gari', 'diesel', 'injini', 'gearbox', 'matengenezo']
        ],
    ];

    public function __construct(SmsService $smsService, GeminiAiService $geminiService)
    {
        $this->smsService = $smsService;
        $this->geminiService = $geminiService;
    }

    /**
     * Automated Targeted Matchmaking Engine:
     * Scans registered workers, filters by location & criteria, and dispatches targeted alerts.
     */
    public function matchAndNotifyCandidates(Listing $listing): array
    {
        // 1. Identify Target Profession category if not explicitly set
        $targetProfession = $listing->target_profession ?? $this->detectProfessionCategory($listing->title . ' ' . $listing->description);
        if (!$listing->target_profession && $targetProfession) {
            $listing->target_profession = $targetProfession;
            $listing->save();
        }

        // 2. Query candidates (only individual job seekers/workers, exclude employers and admins)
        $candidates = User::where('role', '!=', 'ADMIN')
            ->where(function ($q) {
                $q->whereIn('role', ['JOB_SEEKER', 'FREELANCER', 'CASUAL_GIG'])
                  ->orWhere('account_type', 'INDIVIDUAL');
            })
            ->get();

        $matchedCandidates = [];
        $notifiedCount = 0;

        foreach ($candidates as $candidate) {
            $score = $this->calculateCandidateMatchScore($candidate, $listing, $targetProfession);

            // Minimum 65% match threshold to avoid spamming unqualified or out-of-region workers
            if ($score >= 65) {
                $matchedCandidates[] = [
                    'candidate' => $candidate,
                    'score' => $score,
                ];
            }
        }

        // Sort descending by match score
        usort($matchedCandidates, fn($a, $b) => $b['score'] <=> $a['score']);

        // 3. Dispatch targeted alerts (In-App Notification + SMS) to top matching candidates
        foreach ($matchedCandidates as $item) {
            /** @var User $candidate */
            $candidate = $item['candidate'];
            $score = $item['score'];

            // A. Create In-App Notification targeted ONLY to this candidate
            NotificationItem::create([
                'id' => 'notif_' . round(microtime(true) * 1000) . '_' . substr($candidate->id, -4),
                'user_id' => $candidate->id,
                'title' => "🎯 Fursa Mpya ya {$listing->title} ({$score}% Match)",
                'body' => "Habari {$candidate->full_name}, kuna fursa mpya inayolingana na sifa zako za {$this->getProfessionNameSw($targetProfession)} eneo la {$listing->location}. Malipo: {$listing->compensation}. Fungua kutuma maombi!",
                'channel' => "SMS (Africa's Talking) & App",
                'timestamp_str' => 'Just now',
                'is_read' => false,
            ]);

            // B. Send SMS via Africa's Talking
            if (!empty($candidate->phone)) {
                $compStr = substr($listing->compensation, 0, 20);
                $smsMessage = "Linkora: Habari {$candidate->full_name}, fursa mpya ya '{$listing->title}' ({$listing->location}, {$compStr}) inakufaa kwa {$score}%. Fungua app ya Linkora kuomba sasa!";
                $this->smsService->sendSms($candidate->phone, $smsMessage);
                $notifiedCount++;
            }
        }

        // 4. Update Listing with matched candidates count
        $listing->matched_candidates_count = count($matchedCandidates);
        $listing->save();

        // 5. Audit Log the Matchmaking Event
        AuditLog::create([
            'event' => 'AI_MATCHMAKING_DISPATCH',
            'user_id' => $listing->poster_id,
            'payload' => [
                'listing_id' => $listing->id,
                'listing_title' => $listing->title,
                'target_profession' => $targetProfession,
                'matched_count' => count($matchedCandidates),
                'notified_sms_count' => $notifiedCount,
            ],
        ]);

        Log::info("AI Matchmaking completed for '{$listing->title}': {$notifiedCount} candidates notified in {$listing->location}.");

        return [
            'target_profession' => $targetProfession,
            'target_profession_label' => $this->getProfessionNameSw($targetProfession),
            'matched_count' => count($matchedCandidates),
            'notified_count' => $notifiedCount,
            'top_matches' => array_slice(array_map(function ($item) {
                return [
                    'id' => $item['candidate']->id,
                    'full_name' => $item['candidate']->full_name,
                    'phone' => $item['candidate']->phone,
                    'location' => $item['candidate']->location,
                    'primary_profession' => $item['candidate']->primary_profession,
                    'match_score' => $item['score'],
                ];
            }, $matchedCandidates), 0, 5),
        ];
    }

    /**
     * Calculate Match Score (0 - 100%) between a candidate and a listing
     */
    public function calculateCandidateMatchScore(User $candidate, Listing $listing, ?string $targetProfession): int
    {
        $score = 0;

        // 1. Location Proximity Score (Max 40 points)
        $candLoc = strtolower($candidate->location . ' ' . ($candidate->district ?? ''));
        $listLoc = strtolower($listing->location . ' ' . ($listing->district ?? ''));

        $regions = ['mbeya', 'morogoro', 'dar es salaam', 'dodoma', 'arusha', 'mwanza', 'iringa', 'songwe', 'tanga', 'kilimanjaro', 'zanzibar'];
        $locationMatched = false;

        foreach ($regions as $region) {
            if (str_contains($candLoc, $region) && str_contains($listLoc, $region)) {
                $score += 35;
                $locationMatched = true;
                break;
            }
        }

        // Sub-district or specific town match bonus (e.g. Uyole, Mwanjelwa, Kinondoni, Kilombero)
        if ($locationMatched) {
            $candWords = array_filter(explode(' ', preg_replace('/[^a-z0-9]/', ' ', $candLoc)));
            foreach ($candWords as $w) {
                if (strlen($w) > 4 && str_contains($listLoc, $w)) {
                    $score += 5; // local proximity bonus
                    break;
                }
            }
        }

        // If location completely differs across regions (e.g., Mbeya vs Mwanza), heavily penalize physical jobs
        if (!$locationMatched && !str_contains($listLoc, 'tanzania')) {
            // Low location score
            $score += 5;
        }

        // 2. Profession / Category Score (Max 40 points)
        if (!empty($targetProfession)) {
            if ($candidate->primary_profession === $targetProfession) {
                $score += 40; // Direct primary profession match!
            } else {
                // Check if candidate's skills or bio contain taxonomy keywords
                $candSkillsStr = strtolower(implode(' ', (array)$candidate->skills) . ' ' . $candidate->bio);
                $keywords = $this->professionTaxonomy[$targetProfession]['keywords'] ?? [];
                $keywordHits = 0;
                foreach ($keywords as $kw) {
                    if (str_contains($candSkillsStr, strtolower($kw))) {
                        $keywordHits++;
                    }
                }
                if ($keywordHits > 0) {
                    $score += min(35, 20 + ($keywordHits * 5));
                }
            }
        }

        // 3. Required Skills Overlap Score (Max 20 points)
        $reqSkills = (array)($listing->required_skills ?? []);
        $candSkills = (array)($candidate->skills ?? []);
        $overlapCount = 0;

        foreach ($reqSkills as $req) {
            foreach ($candSkills as $candSkill) {
                if (stripos($candSkill, $req) !== false || stripos($req, $candSkill) !== false) {
                    $overlapCount++;
                    break;
                }
            }
        }

        if (count($reqSkills) > 0) {
            $score += (int)round(($overlapCount / count($reqSkills)) * 20);
        } else {
            $score += 10;
        }

        return min(100, $score);
    }

    /**
     * Auto-detect profession category from job text if not supplied
     */
    public function detectProfessionCategory(string $text): ?string
    {
        $textLower = strtolower($text);

        foreach ($this->professionTaxonomy as $category => $data) {
            foreach ($data['keywords'] as $kw) {
                if (str_contains($textLower, strtolower($kw))) {
                    return $category;
                }
            }
        }

        return 'GENERAL_LABOR';
    }

    public function getProfessionNameSw(?string $category): string
    {
        if (!$category || !isset($this->professionTaxonomy[$category])) {
            return 'Kazi ya Jumla';
        }

        return $this->professionTaxonomy[$category]['labels'][0];
    }
}
