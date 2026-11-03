<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Referral;
use App\Models\NotificationItem;
use App\Services\ReputationPassportService;
use App\Services\PushNotificationService;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    protected ReputationPassportService $reputationService;
    protected PushNotificationService $pushService;
    protected SmsService $smsService;

    public function __construct(
        ReputationPassportService $reputationService,
        PushNotificationService $pushService,
        SmsService $smsService
    ) {
        $this->reputationService = $reputationService;
        $this->pushService = $pushService;
        $this->smsService = $smsService;
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
            'avatarUrl' => 'nullable|string',
            'avatar_url' => 'nullable|string',
            'referralCode' => 'nullable|string',
            'referral_code' => 'nullable|string',
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

        $generatedReferralCode = 'LNK-' . strtoupper(Str::random(6));

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
            'avatar_url' => $validated['avatarUrl'] ?? $validated['avatar_url'] ?? null,
            'skills' => $validated['skills'] ?? ['General Labor'],
            'rating' => 5.0,
            'total_transactions' => 0,
            'completed_gigs' => 0,
            'reputation_score' => $hasNida ? 850 : 800,
            'community_vouches' => 1,
            'badges' => $badges,
            'referral_code' => $generatedReferralCode,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user = User::updateOrCreate(
            ['phone' => $validated['phone']],
            $updateData
        );

        // Ensure user has a referral code
        if (empty($user->referral_code)) {
            $user->referral_code = $generatedReferralCode;
            $user->save();
        }

        // Referral processing if invited by an existing user
        $inviteCode = trim($validated['referralCode'] ?? $validated['referral_code'] ?? '');
        if (!empty($inviteCode) && empty($user->referred_by)) {
            $referrer = User::where('referral_code', $inviteCode)->first();
            if ($referrer && $referrer->id !== $user->id) {
                $user->referred_by = $referrer->id;
                $user->reputation_score += 15; // Bonus for signing up with referral
                $user->save();

                $referrer->referral_count += 1;
                $referrer->referral_earnings_tzs += 2000;
                $referrer->reputation_score += 25;
                $referrer->community_vouches += 1;
                $referrer->save();

                Referral::create([
                    'id' => 'ref_' . round(microtime(true) * 1000) . '_' . Str::random(4),
                    'referrer_id' => $referrer->id,
                    'referred_id' => $user->id,
                    'reward_amount_tzs' => 2000,
                    'status' => 'REGISTERED',
                    'reward_claimed' => true,
                ]);

                $this->pushService->notifyUser(
                    $referrer,
                    'Hongera! Umealika Rafiki (+TZS 2,000)',
                    "Rafiki yako {$user->full_name} amejiunga Linkora kwa namba yako ya mwaliko ({$inviteCode}). Umepata bonasi ya TZS 2,000 na alama 25 za sifa!",
                    ['type' => 'REFERRAL_REWARD', 'referred_id' => $user->id]
                );
            }
        }

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

    public function forgotPassword(Request $request)
    {
        $login = trim($request->input('phone') ?? $request->input('login') ?? $request->input('email') ?? '');
        if (empty($login)) {
            return response()->json([
                'success' => false,
                'message' => 'Tafadhali weka namba yako ya simu iliyosajiliwa.',
            ], 422);
        }

        $user = User::where('phone', 'like', "%{$login}%")
            ->orWhere('email', $login)
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Hakuna akaunti inayolingana na namba hiyo.',
            ], 404);
        }

        // Generate 6-digit OTP
        $otp = (string)random_int(100000, 999999);
        $user->password_reset_otp = $otp;
        $user->password_reset_expires_at = now()->addMinutes(15);
        $user->save();

        // Dispatch SMS
        $this->smsService->sendSms($user->phone, "Kodi yako ya kurejesha nenosiri la Linkora ni: {$otp}. Inamalizika baada ya dakika 15.");

        // Dispatch Notification
        $this->pushService->notifyUser(
            $user,
            'Kodi ya Kurejesha Nenosiri',
            "Kodi yako ya kurejesha nenosiri la Linkora ni {$otp}. Usishiriki na mtu yeyote.",
            ['type' => 'PASSWORD_RESET_OTP']
        );

        return response()->json([
            'success' => true,
            'message' => 'Kodi ya siri ya uhakiki (OTP) imetumwa kwenye namba yako ya simu.',
            'otp' => $otp, // Expose for easy testing / development
            'phone' => $user->phone,
        ]);
    }

    public function verifyResetOtp(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string',
            'otp' => 'required|string',
        ]);

        $login = trim($validated['phone']);
        $user = User::where('phone', 'like', "%{$login}%")
            ->orWhere('email', $login)
            ->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Mtumiaji hakupatikana.'], 404);
        }

        if (empty($user->password_reset_otp) || $user->password_reset_otp !== trim($validated['otp'])) {
            return response()->json(['success' => false, 'message' => 'Kodi ya OTP si sahihi. Tafadhali jaribu tena.'], 422);
        }

        if ($user->password_reset_expires_at && $user->password_reset_expires_at->isPast()) {
            return response()->json(['success' => false, 'message' => 'Kodi ya OTP imepitwa na wakati. Tafadhali omba kodi mpya.'], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kodi ya OTP imethibitishwa kikamilifu.',
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string',
            'otp' => 'required|string',
            'newPassword' => 'nullable|string|min:4',
            'new_password' => 'nullable|string|min:4',
            'password' => 'nullable|string|min:4',
        ]);

        $newPass = $validated['newPassword'] ?? $validated['new_password'] ?? $validated['password'];
        if (empty($newPass)) {
            return response()->json(['success' => false, 'message' => 'Tafadhali weka nenosiri jipya lenye herufi zisizopungua 4.'], 422);
        }

        $login = trim($validated['phone']);
        $user = User::where('phone', 'like', "%{$login}%")
            ->orWhere('email', $login)
            ->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Mtumiaji hakupatikana.'], 404);
        }

        if (empty($user->password_reset_otp) || $user->password_reset_otp !== trim($validated['otp'])) {
            return response()->json(['success' => false, 'message' => 'Kodi ya OTP si sahihi au imeshakwisha muda wake.'], 422);
        }

        if ($user->password_reset_expires_at && $user->password_reset_expires_at->isPast()) {
            return response()->json(['success' => false, 'message' => 'Kodi ya OTP imepitwa na wakati.'], 422);
        }

        $user->password = Hash::make($newPass);
        $user->password_reset_otp = null;
        $user->password_reset_expires_at = null;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Nenosiri lako jipya limewekwa kikamilifu. Sasa unaweza kuingia.',
            'data' => $this->formatUserProfile($user),
        ]);
    }

    public function uploadAvatar(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $avatarUrl = null;

        if ($request->hasFile('avatar')) {
            $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg,webp|max:8192',
            ]);
            $path = $request->file('avatar')->store('avatars', 'public');
            $avatarUrl = asset('storage/' . $path);
        } elseif ($request->filled('avatar_base64') || $request->filled('avatarBase64')) {
            $base64 = $request->input('avatar_base64') ?: $request->input('avatarBase64');
            $type = 'png';
            if (preg_match('/^data:image\/(\w+);base64,/', $base64, $match)) {
                $base64 = substr($base64, strpos($base64, ',') + 1);
                $type = strtolower($match[1]);
            }
            $data = base64_decode($base64);
            if ($data !== false) {
                $fileName = 'avatar_' . $user->id . '_' . time() . '.' . $type;
                Storage::disk('public')->put('avatars/' . $fileName, $data);
                $avatarUrl = asset('storage/avatars/' . $fileName);
            }
        } elseif ($request->filled('avatar_url') || $request->filled('avatarUrl')) {
            $avatarUrl = $request->input('avatar_url') ?: $request->input('avatarUrl');
        }

        if (!$avatarUrl) {
            return response()->json([
                'success' => false,
                'message' => 'Tafadhali chagua au pakia picha ya wasifu.',
            ], 422);
        }

        $user->avatar_url = $avatarUrl;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Picha ya wasifu imesasishwa kikamilifu.',
            'avatar_url' => $avatarUrl,
            'data' => $this->formatUserProfile($user),
        ]);
    }

    public function referralStats(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        if (empty($user->referral_code)) {
            $user->referral_code = 'LNK-' . strtoupper(Str::random(6));
            $user->save();
        }

        $referrals = Referral::where('referrer_id', $user->id)
            ->with('referred')
            ->latest()
            ->get()
            ->map(function ($r) {
                return [
                    'id' => $r->id,
                    'referredName' => $r->referred ? $r->referred->full_name : 'Mwanachama Mpya',
                    'referredPhone' => $r->referred ? substr($r->referred->phone, 0, 7) . '***' : '',
                    'rewardAmountTzs' => (int)$r->reward_amount_tzs,
                    'status' => $r->status,
                    'date' => $r->created_at ? $r->created_at->format('Y-m-d H:i') : 'Just now',
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'referralCode' => $user->referral_code,
                'referralCount' => (int)$user->referral_count,
                'referralEarningsTzs' => (int)$user->referral_earnings_tzs,
                'reputationPointsEarned' => (int)$user->referral_count * 25,
                'referrals' => $referrals,
            ],
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
        if (empty($u->referral_code)) {
            $u->referral_code = 'LNK-' . strtoupper(substr(md5($u->id), 0, 6));
            $u->saveQuietly();
        }

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
            'avatarUrl' => $u->avatar_url ?? '',
            'skills' => is_array($u->skills) ? $u->skills : [],
            'rating' => (float)$u->rating,
            'totalTransactions' => (int)$u->total_transactions,
            'completedGigs' => (int)$u->completed_gigs,
            'reputationScore' => (int)$u->reputation_score,
            'communityVouches' => (int)$u->community_vouches,
            'badges' => is_array($u->badges) ? $u->badges : [],
            'referralCode' => $u->referral_code ?? '',
            'referredBy' => $u->referred_by ?? '',
            'referralCount' => (int)($u->referral_count ?? 0),
            'referralEarningsTzs' => (int)($u->referral_earnings_tzs ?? 0),
            'fcmToken' => $u->fcm_token ?? '',
            'tenantId' => $tenant ? $tenant->id : null,
            'tenantDatabase' => $tenant ? $tenant->database_name : null,
            'isolationLevel' => $tenant ? 'DATABASE_PER_TENANT' : 'SHARED',
        ];
    }
}
