<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FinancialProduct;
use App\Models\User;
use Illuminate\Http\Request;

class FinancialPartnerController extends Controller
{
    public function index()
    {
        $products = FinancialProduct::all()->map(fn($p) => [
            'id' => (string)$p->id,
            'providerName' => $p->provider_name,
            'productName' => $p->product_name,
            'category' => $p->category,
            'maxAmountTzs' => (int)$p->max_amount_tzs,
            'interestRate' => $p->interest_rate,
            'minReputationScore' => (int)$p->min_reputation_score,
            'description' => $p->description,
        ]);

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    public function assessCredit(Request $request)
    {
        $currentUser = User::where('id', 'current_user')->first() ?? User::first();
        $score = $currentUser ? $currentUser->reputation_score : 800;

        $eligibleProducts = FinancialProduct::where('min_reputation_score', '<=', $score)->get();

        return response()->json([
            'success' => true,
            'data' => [
                'reputationScore' => $score,
                'isEligible' => count($eligibleProducts) > 0,
                'eligibleProducts' => $eligibleProducts,
            ],
        ]);
    }
}
