<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UssdService;
use Illuminate\Http\Request;

class UssdController extends Controller
{
    protected UssdService $ussdService;

    public function __construct(UssdService $ussdService)
    {
        $this->ussdService = $ussdService;
    }

    /**
     * Africa's Talking Telco Webhook Handler
     */
    public function handleUssd(Request $request)
    {
        $sessionId = $request->input('sessionId', 'sess_' . time());
        $phoneNumber = $request->input('phoneNumber', '+255700000000');
        $text = $request->input('text', '');

        $responseString = $this->ussdService->handleUssd($sessionId, $phoneNumber, $text);

        return response($responseString, 200)
            ->header('Content-Type', 'text/plain');
    }

    /**
     * JSON Simulator for Mobile and Testing
     */
    public function simulate(Request $request)
    {
        $sessionId = $request->input('sessionId', 'sim_' . time());
        $phoneNumber = $request->input('phoneNumber', '+255754892104');
        $text = $request->input('text', '');

        $raw = $this->ussdService->handleUssd($sessionId, $phoneNumber, $text);
        $isCon = str_starts_with($raw, 'CON ');
        $cleanMessage = preg_replace('/^(CON|END)\s+/', '', $raw);

        return response()->json([
            'success' => true,
            'data' => [
                'responseType' => $isCon ? 'CON' : 'END',
                'message' => $cleanMessage,
            ],
        ]);
    }
}
