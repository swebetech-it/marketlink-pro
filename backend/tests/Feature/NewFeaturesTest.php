<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Referral;
use App\Models\NotificationItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewFeaturesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. Profile Picture upload and update test
     */
    public function test_profile_picture_upload_and_update()
    {
        $user = User::create([
            'id' => 'usr_pic_101',
            'full_name' => 'Baraka Mushi',
            'phone' => '+255711223344',
            'role' => 'FREELANCER',
            'reputation_score' => 800,
        ]);

        // Upload preset or remote avatar URL
        $avatarUrl = 'https://linkora.co.tz/avatars/avatar_baraka.png';
        $response = $this->postJson("/api/users/{$user->id}/avatar", [
            'avatar_url' => $avatarUrl,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'avatar_url' => $avatarUrl,
            ]);

        $this->assertEquals($avatarUrl, $user->fresh()->avatar_url);

        // Upload base64 encoded photo
        $fakePngBase64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';
        $b64Response = $this->postJson("/api/users/{$user->id}/avatar", [
            'avatar_base64' => $fakePngBase64,
        ]);

        $b64Response->assertStatus(200);
        $this->assertNotEmpty($user->fresh()->avatar_url);
        $this->assertStringContainsString('avatar_', $user->fresh()->avatar_url);
    }

    /**
     * 2. Password recovery flow test (Forgot -> Verify OTP -> Reset -> Login)
     */
    public function test_password_recovery_flow()
    {
        // Register user with initial password
        $user = User::create([
            'id' => 'usr_pw_202',
            'full_name' => 'Neema Mwakipesile',
            'phone' => '+255755667788',
            'password' => bcrypt('OldSecret123'),
            'role' => 'JOB_SEEKER',
        ]);

        // Step 1: Request Password Reset OTP
        $forgotResponse = $this->postJson('/api/auth/forgot-password', [
            'phone' => '+255755667788',
        ]);

        $forgotResponse->assertStatus(200)
            ->assertJsonStructure(['success', 'message', 'otp', 'phone']);

        $otp = $forgotResponse->json('otp');
        $this->assertNotEmpty($otp);
        $this->assertEquals(6, strlen($otp));

        // Step 2: Verify Reset OTP
        $verifyResponse = $this->postJson('/api/auth/verify-reset-otp', [
            'phone' => '+255755667788',
            'otp' => $otp,
        ]);
        $verifyResponse->assertStatus(200)->assertJson(['success' => true]);

        // Step 3: Reset Password
        $resetResponse = $this->postJson('/api/auth/reset-password', [
            'phone' => '+255755667788',
            'otp' => $otp,
            'newPassword' => 'NewSecurePass2026',
        ]);
        $resetResponse->assertStatus(200)->assertJson(['success' => true]);

        // Step 4: Login with New Password
        $loginResponse = $this->postJson('/api/auth/login', [
            'login' => '+255755667788',
            'password' => 'NewSecurePass2026',
        ]);
        $loginResponse->assertStatus(200)->assertJson(['success' => true]);
    }

    /**
     * 3. Referral system test (Referral code generation, friend signup bonus, and statistics)
     */
    public function test_referral_system_flow()
    {
        // 1. Register Referrer (User A)
        $regResponseA = $this->postJson('/api/auth/register', [
            'fullName' => 'Juma Mwambene',
            'phone' => '+255788112233',
            'role' => 'FREELANCER',
            'primaryProfession' => 'ELECTRICAL',
        ]);

        $regResponseA->assertStatus(201);
        $referrerId = $regResponseA->json('data.id');
        $referralCode = $regResponseA->json('data.referralCode');

        $this->assertNotEmpty($referralCode);
        $this->assertStringStartsWith('LNK-', $referralCode);

        // 2. Register Referred Friend (User B) with referralCode
        $regResponseB = $this->postJson('/api/auth/register', [
            'fullName' => 'Amina Rashid',
            'phone' => '+255788998877',
            'role' => 'JOB_SEEKER',
            'primaryProfession' => 'CLEANING_SANITATION',
            'referralCode' => $referralCode,
        ]);

        $regResponseB->assertStatus(201);
        $referredId = $regResponseB->json('data.id');

        // Check Referrer received +1 referral count, 2000 TZS reward, and +25 reputation points
        $referrer = User::find($referrerId);
        $this->assertEquals(1, $referrer->referral_count);
        $this->assertEquals(2000, $referrer->referral_earnings_tzs);
        $this->assertGreaterThanOrEqual(825, $referrer->reputation_score);

        // Check Referral record exists
        $this->assertDatabaseHas('referrals', [
            'referrer_id' => $referrerId,
            'referred_id' => $referredId,
            'reward_amount_tzs' => 2000,
        ]);

        // Check Referral Stats API
        $statsResponse = $this->getJson("/api/users/{$referrerId}/referrals");
        $statsResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'referralCode' => $referralCode,
                    'referralCount' => 1,
                    'referralEarningsTzs' => 2000,
                ],
            ]);
        $this->assertCount(1, $statsResponse->json('data.referrals'));
    }

    /**
     * 4. Push Notification FCM Token Registration and Dispatch Test
     */
    public function test_push_notification_and_fcm_token_registration()
    {
        $user = User::create([
            'id' => 'usr_fcm_404',
            'full_name' => 'Ezekiel Mbwambo',
            'phone' => '+255766334455',
            'role' => 'JOB_SEEKER',
        ]);

        $fcmToken = 'fake_firebase_cloud_messaging_token_xyz_12345';

        // 1. Register FCM Token
        $tokenResponse = $this->postJson('/api/notifications/fcm-token', [
            'userId' => $user->id,
            'fcmToken' => $fcmToken,
            'deviceType' => 'android',
        ]);

        $tokenResponse->assertStatus(200)->assertJson(['success' => true]);
        $this->assertEquals($fcmToken, $user->fresh()->fcm_token);

        // 2. Dispatch Test Push Notification
        $pushResponse = $this->postJson('/api/notifications/test-push', [
            'userId' => $user->id,
            'title' => 'Jaribio la Arifa',
            'body' => 'Arifa ya mfano imewasili kwa mafanikio.',
        ]);

        $pushResponse->assertStatus(200)->assertJson(['success' => true]);

        // Verify In-App Notification was logged
        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'title' => 'Jaribio la Arifa',
            'channel' => 'Push (FCM)',
        ]);
    }
}
