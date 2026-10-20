<?php

namespace App\Services;

use App\Models\User;

class ReputationPassportService
{
    /**
     * Recompute user Reputation Passport score (0 to 1000)
     */
    public function recalculate(User $user): int
    {
        $score = 700; // Base score

        // 1. NIDA Verification (+50)
        if ($user->verification_status === 'VERIFIED') {
            $score += 50;
        }

        // 2. Completed Gigs (+10 each, up to 150)
        $score += min(150, $user->completed_gigs * 10);

        // 3. Rating Score (up to 60)
        if ($user->rating > 3.0) {
            $score += (int)(($user->rating - 3.0) * 30);
        }

        // 4. Community Vouches (+15 each, up to 90)
        $score += min(90, $user->community_vouches * 15);

        // 5. Badges (+10 each)
        if (is_array($user->badges)) {
            $score += count($user->badges) * 10;
        }

        $finalScore = max(100, min(1000, $score));
        $user->reputation_score = $finalScore;
        $user->save();

        return $finalScore;
    }
}
