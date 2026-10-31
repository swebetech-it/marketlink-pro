<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Listing;
use App\Models\Application;
use App\Models\EscrowTransaction;
use App\Models\AuditLog;
use App\Services\GeminiAiService;
use App\Services\EscrowService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminWebController extends Controller
{
    protected GeminiAiService $aiService;
    protected EscrowService $escrowService;

    public function __construct(GeminiAiService $aiService, EscrowService $escrowService)
    {
        $this->aiService = $aiService;
        $this->escrowService = $escrowService;
    }

    public function dashboard()
    {
        $stats = [
            'totalUsers' => User::count(),
            'verifiedUsers' => User::where('verification_status', 'VERIFIED')->count(),
            'activeListings' => Listing::where('status', 'ACTIVE')->count(),
            'totalApplications' => Application::count(),
            'totalEscrowHeldTzs' => EscrowTransaction::where('escrow_status', 'HELD_IN_ESCROW')->sum('amount_tzs'),
            'totalEscrowReleasedTzs' => EscrowTransaction::where('escrow_status', 'RELEASED')->sum('amount_tzs'),
        ];

        $recentListings = Listing::orderBy('created_at', 'desc')->take(10)->get();
        $recentUsers = User::orderBy('created_at', 'desc')->take(10)->get();
        $recentTransactions = EscrowTransaction::orderBy('created_at', 'desc')->take(10)->get();
        $recentApplications = Application::orderBy('created_at', 'desc')->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentListings', 'recentUsers', 'recentTransactions', 'recentApplications'));
    }

    public function listings(Request $request)
    {
        $category = $request->query('category');
        $query = Listing::orderBy('created_at', 'desc');

        if ($category) {
            $query->where('category', $category);
        }

        $listings = $query->paginate(15);
        return view('admin.listings', compact('listings', 'category'));
    }

    public function storeListing(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'required|string',
            'location' => 'required|string',
            'compensation' => 'required|string',
            'compensationAmountTzs' => 'nullable|numeric',
            'requiredSkills' => 'nullable|string',
            'isEscrowRequired' => 'nullable|boolean',
            'posterName' => 'nullable|string',
            'agriCropType' => 'nullable|string',
            'equipmentDetails' => 'nullable|string',
        ]);

        $skillsArray = !empty($validated['requiredSkills'])
            ? array_map('trim', explode(',', $validated['requiredSkills']))
            : ['General Services'];

        $fraudAnalysis = $this->aiService->checkFraudRisk(
            $validated['title'],
            $validated['description'],
            $validated['compensation']
        );

        $adminUser = User::firstOrCreate(
            ['id' => 'admin_portal'],
            [
                'full_name' => $validated['posterName'] ?? 'Swebetech System Admin',
                'phone' => '+255 700 000 000',
                'email' => 'admin@swebetech.co.tz',
                'role' => 'ADMIN',
                'verification_status' => 'VERIFIED',
                'nida_number' => 'ADMIN-SYSTEM-01',
                'location' => 'Mbeya City, Tanzania',
                'bio' => 'Linkora System Administrator & Operations Manager',
                'skills' => ['System Administration', 'KYC Verification', 'Escrow Arbitration'],
                'rating' => 5.0,
                'reputation_score' => 1000,
                'badges' => ['System Administrator', 'Verified Ops']
            ]
        );

        $listingId = 'lst_' . strtolower(Str::random(6));

        Listing::create([
            'id' => $listingId,
            'poster_id' => $adminUser->id,
            'poster_name' => $validated['posterName'] ?? 'Swebetech System Admin',
            'poster_verification' => 'VERIFIED',
            'poster_rating' => 5.0,
            'category' => $validated['category'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'compensation' => $validated['compensation'],
            'compensation_amount_tzs' => $validated['compensationAmountTzs'] ?? 0,
            'required_skills' => $skillsArray,
            'date_posted' => date('Y-m-d'),
            'deadline' => date('Y-m-d', strtotime('+30 days')),
            'is_featured' => true,
            'is_escrow_required' => $request->has('isEscrowRequired'),
            'match_score' => 95,
            'agri_crop_type' => $validated['agriCropType'] ?? null,
            'equipment_details' => $validated['equipmentDetails'] ?? null,
            'is_flagged_suspicious' => $fraudAnalysis['isSuspicious'] ?? false,
            'fraud_risk_score' => ($fraudAnalysis['isSuspicious'] ?? false) ? 85 : 5,
            'status' => 'ACTIVE',
        ]);

        AuditLog::create([
            'event' => 'CREATE_LISTING_WEB_PORTAL',
            'user_id' => $adminUser->id,
            'ip_address' => $request->ip() ?? '127.0.0.1',
            'payload' => [
                'listing_id' => $listingId,
                'title' => $validated['title'],
                'category' => $validated['category'],
                'compensation' => $validated['compensation']
            ],
            'created_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Opportunity listing created successfully and published to mobile app feed!');
    }

    public function deleteListing($id)
    {
        $listing = Listing::findOrFail($id);
        $listing->delete();

        AuditLog::create([
            'event' => 'DELETE_LISTING_WEB_PORTAL',
            'user_id' => 'admin_portal',
            'ip_address' => request()->ip() ?? '127.0.0.1',
            'payload' => ['listing_id' => $id],
            'created_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Listing #' . $id . ' removed from system.');
    }

    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function toggleUserVerification($id)
    {
        $user = User::findOrFail($id);
        $newStatus = ($user->verification_status === 'VERIFIED') ? 'PENDING' : 'VERIFIED';
        $user->verification_status = $newStatus;
        if ($newStatus === 'VERIFIED') {
            $user->reputation_score = min(1000, $user->reputation_score + 50);
            if (!in_array('NIDA Verified', $user->badges ?? [])) {
                $badges = $user->badges ?? [];
                $badges[] = 'NIDA Verified';
                $user->badges = $badges;
            }
        }
        $user->save();

        return redirect()->back()->with('success', "User {$user->full_name} verification status updated to {$newStatus}.");
    }

    public function transactions()
    {
        $transactions = EscrowTransaction::orderBy('created_at', 'desc')->paginate(20);
        $auditLogs = AuditLog::orderBy('created_at', 'desc')->take(25)->get();
        return view('admin.transactions', compact('transactions', 'auditLogs'));
    }
}
