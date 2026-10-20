<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\User;
use App\Models\EscrowTransaction;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function stats()
    {
        $totalUsers = User::count();
        $totalListings = Listing::where('status', 'ACTIVE')->count();
        $verifiedCount = User::where('verification_status', 'VERIFIED')->count();
        $nidaPercentage = $totalUsers > 0 ? round(($verifiedCount / $totalUsers) * 100, 1) : 0;
        $totalEscrowLocked = EscrowTransaction::where('escrow_status', 'HELD_IN_ESCROW')->sum('amount_tzs');

        return response()->json([
            'success' => true,
            'data' => [
                'registeredUsers' => $totalUsers,
                'activeListings' => $totalListings,
                'nidaVerifiedPercentage' => "{$nidaPercentage}%",
                'totalEscrowLockedTzs' => (int)$totalEscrowLocked,
                'regionsCovered' => ['Mbeya', 'Morogoro', 'Dar es Salaam', 'Dodoma', 'Arusha', 'Iringa', 'Mwanza'],
            ],
        ]);
    }

    public function moderateListing(Request $request, string $id)
    {
        $listing = Listing::findOrFail($id);
        $action = $request->input('action', 'takedown'); // takedown or approve

        if ($action === 'takedown') {
            $listing->status = 'SUSPENDED';
            $listing->is_flagged_suspicious = true;
        } else {
            $listing->status = 'ACTIVE';
            $listing->is_flagged_suspicious = false;
        }
        $listing->save();

        return response()->json([
            'success' => true,
            'message' => "Listing {$action}ed successfully",
            'data' => $listing,
        ]);
    }
}
