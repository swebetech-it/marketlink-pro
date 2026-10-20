<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GeminiAiService;
use Illuminate\Http\Request;

class AiController extends Controller
{
    protected GeminiAiService $aiService;

    public function __construct(GeminiAiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function match(Request $request)
    {
        $validated = $request->validate([
            'skills' => 'required|array',
            'location' => 'required|string',
            'listingTitle' => 'required|string',
            'requiredSkills' => 'required|array',
        ]);

        $result = $this->aiService->analyzeMatchScore(
            $validated['skills'],
            $validated['location'],
            $validated['listingTitle'],
            $validated['requiredSkills']
        );

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    public function fraudCheck(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'compensation' => 'required|string',
        ]);

        $result = $this->aiService->checkFraudRisk(
            $validated['title'],
            $validated['description'],
            $validated['compensation']
        );

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    public function generatePitch(Request $request)
    {
        $validated = $request->validate([
            'jobTitle' => 'required|string',
            'applicantSkills' => 'required|array',
            'isSwahili' => 'nullable|boolean',
        ]);

        $pitch = $this->aiService->generateApplicationPitch(
            $validated['jobTitle'],
            $validated['applicantSkills'],
            (bool)($validated['isSwahili'] ?? false)
        );

        return response()->json([
            'success' => true,
            'data' => [
                'pitch' => $pitch,
            ],
        ]);
    }
}
