<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\NotificationItem;
use App\Services\ReputationPassportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    protected ReputationPassportService $reputationService;

    public function __construct(ReputationPassportService $reputationService)
    {
        $this->reputationService = $reputationService;
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'fullName' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:4',
            'role' => 'nullable|string',
            'accountType' => 'nullable|string',
            'account_type' => 'nullable|string',
            'organizationName' => 'nullable|string',
            'organization_name' => 'nullable|string',
            'tinNumber' => 'nullable|string',
            'tin_number' => 'nullable|string',
            'industrySector' => 'nullable|string',
            'industry_sector' => 'nullable|string',
            'primaryProfession' => 'nullable|string',
            'primary_profession' => 'nullable|string',
            'nidaNumber' => 'nullable|string',
            'location' => 'nullable|string',
            'district' => 'nullable|string',
            'bio' => 'nullable|string',
            'skills' => 'nullable|array',
        ]);

        $userId = $request->input('id') ?? ('usr_' . strtolower(Str::random(6)));
        $hasNida = !empty($validated['nidaNumber']);
        $verificationStatus = $hasNida ? 'VERIFIED' : 'PENDING';

        $badges = ['Registered 2026'];
        if ($hasNida) {
            $badges[] = 'NIDA Verified';
        }

        $accountType = $validated['accountType'] ?? $validated['account_type'] ?? 'INDIVIDUAL';
        $orgName = $validated['organizationName'] ?? $validated['organization_name'] ?? null;
        $primaryProf = $validated['primaryProfession'] ?? $validated['primary_profession'] ?? null;

        $updateData = [
            'id' => $userId,
            'full_name' => $validated['fullName'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'role' => $validated['role'] ?? ($accountType === 'ORGANIZATION' ? 'EMPLOYER' : 'JOB_SEEKER'),
            'account_type' => $accountType,
            'organization_name' => $orgName,
            'tin_number' => $validated['tinNumber'] ?? $validated['tin_number'] ?? null,
            'industry_sector' => $validated['industrySector'] ?? $validated['industry_sector'] ?? null,
            'primary_profession' => $primaryProf,
            'district' => $validated['district'] ?? null,
            'verification_status' => $verificationStatus,
            'nida_number' => $validated['nidaNumber'] ?? '',
            'location' => $validated['location'] ?? 'Mbeya City, Tanzania',
            'bio' => $validated['bio'] ?? ($accountType === 'ORGANIZATION' ? "Kampuni/Shirika: {$orgName}" : ''),
            'skills' => $validated['skills'] ?? ['General Labor'],
            'rating' => 5.0,
            'total_transactions' => 0,
            'completed_gigs' => 0,
            'reputation_score' => $hasNida ? 850 : 800,
            'community_vouches' => 1,
            'badges' => $badges,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user = User::updateOrCreate(
            ['phone' => $validated['phone']],
            $updateData
        );

        // Database-Per-Tenant Architecture: If registering as an Organization/Employer,
        // dynamically provision a dedicated isolated physical database for this tenant.
        if ($accountType === 'ORGANIZATION' || in_array($user->role, ['EMPLOYER', 'FARMER_COOP'])) {
            $tenantManager = app(\App\Services\TenantManager::class);
            $tenantManager->createTenantDatabase(
                $user->id,
                $user->organization_name ?: $user->full_name,
                $user->id
            );
        }

        NotificationItem::create([
            'id' => 'notif_' . round(microtime(true) * 1000),
            'user_id' => $user->id,
            'title' => 'Karibu Linkora!',
            'body' => $accountType === 'ORGANIZATION'
                ? "Akaunti ya shirika '{$user->organization_name}' imesajiliwa kikamilifu. Sasa unaweza kutangaza fursa na kuwafikia wafanyakazi wanaokidhi sifa moja kwa moja."
                : "Akaunti ya {$user->full_name} imesajiliwa kikamilifu. Utaanza kupokea fursa za kazi zinazolingana na ujuzi wako wa {$user->primary_profession}.",
            'channel' => "SMS (Africa's Talking)",
            'timestamp_str' => 'Just now',
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => $this->formatUserProfile($user),
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'login' => 'required|string',
            'password' => 'nullable|string',
        ]);

        $login = trim($validated['login']);

        $user = User::where('phone', 'like', "%{$login}%")
            ->orWhere('email', $login)
            ->orWhere('full_name', 'like', "%{$login}%")
            ->orWhere('organization_name', 'like', "%{$login}%")
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Akaunti haikupatikana kwa simu au barua pepe uliyoweka.',
                'data' => null,
            ], 404);
        }

        // Verify password if set and password was passed
        if (!empty($user->password) && !empty($validated['password'])) {
            if (!Hash::check($validated['password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenosiri si sahihi. Tafadhali jaribu tena.',
                    'data' => null,
                ], 401);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => $this->formatUserProfile($user),
        ]);
    }

    public function profile(Request $request)
    {
        $targetId = $request->header('X-User-Id') ?? $request->query('user_id');

        $user = null;
        if ($targetId) {
            $user = User::where('id', $targetId)->first();
        }
        
        if (!$user) {
            $user = User::latest()->first();
        }

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'No active user session', 'data' => null], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatUserProfile($user),
        ]);
    }

    public function index()
    {
        $users = User::orderBy('full_name', 'asc')->get()->map(function ($u) {
            return $this->formatUserProfile($u);
        });

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    public function verifyNida(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $nida = $request->input('nidaNumber', $user->nida_number);

        $user->nida_number = $nida;
        $user->verification_status = 'VERIFIED';
        $badges = is_array($user->badges) ? $user->badges : [];
        if (!in_array('NIDA Verified', $badges)) {
            $badges[] = 'NIDA Verified';
        }
        $user->badges = $badges;
        $this->reputationService->recalculate($user);

        return response()->json([
            'success' => true,
            'message' => 'NIDA identity verified successfully',
            'data' => $this->formatUserProfile($user),
        ]);
    }

    public function vouch(string $id)
    {
        $user = User::findOrFail($id);
        $user->community_vouches += 1;
        $this->reputationService->recalculate($user);

        return response()->json([
            'success' => true,
            'message' => 'Community vouch added',
            'data' => $this->formatUserProfile($user),
        ]);
    }

    public function updateRole(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $user->role = $request->input('role', $user->role);
        if ($request->filled('accountType')) {
            $user->account_type = $request->input('accountType');
        }
        if ($request->filled('primaryProfession')) {
            $user->primary_profession = $request->input('primaryProfession');
        }
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Role updated',
            'data' => $this->formatUserProfile($user),
        ]);
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted',
            'data' => true,
        ]);
    }

    protected function formatUserProfile(User $u): array
    {
        $tenant = \App\Models\Tenant::where('user_id', $u->id)->orWhere('id', $u->id)->first();

        return [
            'id' => (string)$u->id,
            'fullName' => $u->full_name,
            'phone' => $u->phone,
            'email' => $u->email ?? '',
            'role' => $u->role,
            'accountType' => $u->account_type ?? 'INDIVIDUAL',
            'organizationName' => $u->organization_name ?? '',
            'tinNumber' => $u->tin_number ?? '',
            'industrySector' => $u->industry_sector ?? '',
            'primaryProfession' => $u->primary_profession ?? '',
            'district' => $u->district ?? '',
            'verificationStatus' => $u->verification_status,
            'nidaNumber' => $u->nida_number ?? '',
            'location' => $u->location,
            'bio' => $u->bio ?? '',
            'skills' => is_array($u->skills) ? $u->skills : [],
            'rating' => (float)$u->rating,
            'totalTransactions' => (int)$u->total_transactions,
            'completedGigs' => (int)$u->completed_gigs,
            'reputationScore' => (int)$u->reputation_score,
            'communityVouches' => (int)$u->community_vouches,
            'badges' => is_array($u->badges) ? $u->badges : [],
            'tenantId' => $tenant ? $tenant->id : null,
            'tenantDatabase' => $tenant ? $tenant->database_name : null,
            'isolationLevel' => $tenant ? 'DATABASE_PER_TENANT' : 'SHARED',
        ];
    }
}
