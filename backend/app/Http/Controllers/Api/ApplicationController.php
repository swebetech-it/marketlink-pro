<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\NotificationItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $targetUserId = $request->header('X-User-Id') ?? $request->query('user_id');
        $currentUser = $targetUserId ? User::find($targetUserId) : null;

        $tenantManager = app(\App\Services\TenantManager::class);
        $tenant = $currentUser ? $tenantManager->findTenantForUser($currentUser->id) : null;

        // Multi-Tenant Isolation: If user is an organization/employer, read directly from isolated tenant database
        if ($tenant && $currentUser && ($currentUser->account_type === 'ORGANIZATION' || in_array($currentUser->role, ['EMPLOYER', 'SERVICE_CLIENT', 'FARMER_COOP']))) {
            $tenantManager->switchToTenant($tenant);
            $apps = Application::on('tenant')->orderBy('created_at', 'desc')->get()->map(fn($a) => [
                'id' => (string)$a->id,
                'listingId' => (string)$a->listing_id,
                'listingTitle' => $a->listing_title,
                'applicantId' => (string)$a->applicant_id,
                'applicantName' => $a->applicant_name,
                'pitchMessage' => $a->pitch_message,
                'status' => $a->status,
                'appliedDate' => (string)$a->applied_date,
            ]);

            return response()->json([
                'success' => true,
                'data' => $apps,
                'tenant_database' => $tenant->database_name,
                'isolation_mode' => 'DATABASE_PER_TENANT',
            ]);
        }

        $query = Application::query();

        if ($currentUser && $currentUser->role !== 'ADMIN') {
            if ($currentUser->account_type === 'ORGANIZATION' || in_array($currentUser->role, ['EMPLOYER', 'SERVICE_CLIENT', 'FARMER_COOP'])) {
                $myListingIds = \App\Models\Listing::where('poster_id', $currentUser->id)->pluck('id');
                $query->whereIn('listing_id', $myListingIds);
            } else {
                // Worker sees only their own applications
                $query->where('applicant_id', $currentUser->id);
            }
        }

        $apps = $query->orderBy('created_at', 'desc')->get()->map(fn($a) => [
            'id' => (string)$a->id,
            'listingId' => (string)$a->listing_id,
            'listingTitle' => $a->listing_title,
            'applicantId' => (string)$a->applicant_id,
            'applicantName' => $a->applicant_name,
            'pitchMessage' => $a->pitch_message,
            'status' => $a->status,
            'appliedDate' => (string)$a->applied_date,
        ]);

        return response()->json([
            'success' => true,
            'data' => $apps,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'listingId' => 'required|string',
            'listingTitle' => 'required|string',
            'pitchMessage' => 'required|string',
        ]);

        $currentUser = User::where('id', 'current_user')->first() ?? User::first();
        $applicantId = $request->input('applicantId') ?? ($currentUser ? $currentUser->id : 'usr_' . Str::random(6));
        $applicantName = $request->input('applicantName') ?? ($currentUser ? $currentUser->full_name : 'Job Seeker');

        $appId = 'app_' . strtolower(Str::random(8));

        $appRecord = [
            'id' => $appId,
            'listing_id' => $validated['listingId'],
            'listing_title' => $validated['listingTitle'],
            'applicant_id' => $applicantId,
            'applicant_name' => $applicantName,
            'pitch_message' => $validated['pitchMessage'],
            'status' => 'Applied (Awaiting Review)',
            'applied_date' => date('Y-m-d'),
        ];

        // Multi-Tenant Isolation: If listing belongs to an organization with a dedicated database,
        // store the application directly into that organization's private database!
        $listing = \App\Models\Listing::find($validated['listingId']);
        if ($listing) {
            $tenantManager = app(\App\Services\TenantManager::class);
            $tenant = $tenantManager->findTenantForUser($listing->poster_id);
            if ($tenant) {
                $tenantManager->switchToTenant($tenant);
                Application::on('tenant')->create($appRecord);
            }
        }

        // Also record in central database for applicant tracking
        $application = Application::create($appRecord);

        NotificationItem::create([
            'id' => 'n_' . round(microtime(true) * 1000),
            'user_id' => $applicantId,
            'title' => 'Application Submitted Successfully',
            'body' => "Maombi yako ya '{$validated['listingTitle']}' yametumwa kikamilifu pamoja na alama zako za Reputation Passport.",
            'channel' => 'In-App',
            'timestamp_str' => 'Just now',
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Application submitted successfully',
            'data' => [
                'id' => (string)$application->id,
                'listingId' => (string)$application->listing_id,
                'listingTitle' => $application->listing_title,
                'applicantId' => (string)$application->applicant_id,
                'applicantName' => $application->applicant_name,
                'pitchMessage' => $application->pitch_message,
                'status' => $application->status,
                'appliedDate' => (string)$application->applied_date,
            ],
        ], 201);
    }
}
