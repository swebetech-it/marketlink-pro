<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Models\Listing;
use App\Models\User;

class LandingPageController extends Controller
{
    /**
     * Show the main Linkora landing page, or return JSON if accessed via API domain.
     */
    public function index(Request $request)
    {
        // If accessed through the API subdomain or with JSON header, return API status
        $host = strtolower($request->getHost());
        if (str_starts_with($host, 'api.') || $request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'status' => 'online',
                'service' => 'Linkora Intelligent Ecosystem API',
                'version' => '1.0.0',
                'environment' => config('app.env', 'production'),
                'domain' => 'linkora.co.tz',
                'api_url' => 'https://api.linkora.co.tz',
                'endpoints' => [
                    'auth' => '/api/auth',
                    'users' => '/api/users',
                    'listings' => '/api/listings',
                    'applications' => '/api/applications',
                    'escrow' => '/api/escrow',
                    'ai' => '/api/ai',
                    'courses' => '/api/courses',
                ],
                'admin_portal' => 'https://linkora.co.tz/admin',
                'timestamp' => now()->toIso8601String(),
            ]);
        }

        // System architectural guarantees & real operational parameters (no false vanity metrics)
        $stats = [
            'kyc_status' => '100% NIDA',
            'escrow_protection' => '100% Escrow',
            'regions_covered' => 'Mikoa 31',
            'channel_access' => 'App & USSD',
        ];

        $recentListings = [];

        try {
            if (class_exists(Listing::class)) {
                $dbListings = Listing::where('status', 'ACTIVE')->latest()->take(6)->get();
                if ($dbListings->count() > 0) {
                    $recentListings = $dbListings;
                }
            }
        } catch (\Throwable $e) {
            // Graceful fallback if database connection is unavailable during build/cache
        }

        return view('landing', compact('stats', 'recentListings'));
    }

    /**
     * Handle direct APK download.
     */
    public function downloadApk()
    {
        $apkPath = public_path('downloads/linkora.apk');

        if (File::exists($apkPath)) {
            return Response::download($apkPath, 'linkora-latest.apk', [
                'Content-Type' => 'application/vnd.android.package-archive',
                'Content-Disposition' => 'attachment; filename="linkora.apk"',
            ]);
        }

        // Return user-friendly response if APK hasn't been uploaded yet
        return response()->view('apk_status', [
            'expected_path' => $apkPath,
        ], 200);
    }
}
