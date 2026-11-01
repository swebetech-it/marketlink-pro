<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Listing;
use App\Models\Application;
use App\Models\EscrowTransaction;
use App\Models\Message;
use App\Models\NotificationItem;
use App\Models\Tenant;
use App\Services\TenantManager;

class ComprehensiveCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Complete Users & Authentication CRUD
     */
    public function test_users_and_auth_crud(): void
    {
        // 1. CREATE: Register Worker
        $registerWorkerResponse = $this->postJson('/api/auth/register', [
            'fullName' => 'Baraka Mushi',
            'phone' => '+255711223344',
            'email' => 'baraka@linkora.co.tz',
            'password' => 'secret123',
            'role' => 'JOB_SEEKER',
            'accountType' => 'INDIVIDUAL',
            'nidaNumber' => '19900101-12345-00001-20',
            'location' => 'Dar es Salaam, Tanzania',
            'district' => 'Kinondoni',
            'primaryProfession' => 'IT_SOFTWARE',
            'skills' => ['Laravel', 'Kotlin', 'Vue'],
            'bio' => 'Full-stack software developer',
        ]);

        $registerWorkerResponse->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.fullName', 'Baraka Mushi')
            ->assertJsonPath('data.verificationStatus', 'VERIFIED');

        $workerId = $registerWorkerResponse->json('data.id');
        $this->assertNotNull($workerId);
        $this->assertDatabaseHas('users', ['id' => $workerId, 'phone' => '+255711223344']);

        // 2. READ: Fetch User Profile
        $profileResponse = $this->getJson("/api/users/profile?id={$workerId}");
        $profileResponse->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $workerId)
            ->assertJsonPath('data.district', 'Kinondoni');

        // READ: List Users
        $usersListResponse = $this->getJson('/api/users');
        $usersListResponse->assertStatus(200)
            ->assertJsonPath('success', true);
        $this->assertTrue(collect($usersListResponse->json('data'))->contains('id', $workerId));

        // 3. UPDATE: Verify NIDA & Vouch
        $verifyResponse = $this->putJson("/api/users/{$workerId}/verify-nida", [
            'nidaNumber' => '19900101-99999-00001-20',
        ]);
        $verifyResponse->assertStatus(200)
            ->assertJsonPath('success', true);
        $this->assertDatabaseHas('users', ['id' => $workerId, 'nida_number' => '19900101-99999-00001-20']);

        $vouchResponse = $this->postJson("/api/users/{$workerId}/vouch", [
            'voucherId' => 'usr_admin',
        ]);
        $vouchResponse->assertStatus(200);

        // 4. DELETE: Delete User
        $deleteResponse = $this->deleteJson("/api/users/{$workerId}");
        $deleteResponse->assertStatus(200)
            ->assertJsonPath('success', true);
        $this->assertDatabaseMissing('users', ['id' => $workerId]);
    }

    /**
     * Test Multi-Tenant Organization Registration & Lifecycle CRUD
     */
    public function test_organization_tenant_lifecycle_crud(): void
    {
        // 1. CREATE: Register Organization
        $registerOrgResponse = $this->postJson('/api/auth/register', [
            'fullName' => 'Kilombero Agro Ltd',
            'phone' => '+255755001122',
            'email' => 'info@kilombero.co.tz',
            'role' => 'EMPLOYER',
            'accountType' => 'ORGANIZATION',
            'organizationName' => 'Kilombero Sugar Works',
            'tinNumber' => '998877665',
            'industrySector' => 'Agribusiness & Commercial',
            'location' => 'Morogoro, Tanzania',
            'district' => 'Kilombero',
        ]);

        $registerOrgResponse->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.accountType', 'ORGANIZATION');

        $orgUserId = $registerOrgResponse->json('data.id');
        $tenantId = $registerOrgResponse->json('data.tenantId');

        $this->assertNotNull($tenantId);
        $this->assertDatabaseHas('tenants', ['id' => $tenantId, 'user_id' => $orgUserId]);

        // 2. READ: Read Tenant Profile and Database Assignment
        $orgProfileResponse = $this->getJson("/api/users/profile?id={$orgUserId}");
        $orgProfileResponse->assertStatus(200)
            ->assertJsonPath('data.tenantId', $tenantId)
            ->assertJsonPath('data.isolationLevel', 'DATABASE_PER_TENANT');
    }

    /**
     * Test Listings / Opportunities CRUD (National Central Marketplace)
     */
    public function test_listings_crud(): void
    {
        // Setup Individual Employer for Central Feed
        $employer = User::create([
            'id' => 'emp_test_101',
            'full_name' => 'Safari Lodges Tanzania',
            'phone' => '+255767889900',
            'email' => 'hr@safarilodges.co.tz',
            'role' => 'EMPLOYER',
            'account_type' => 'INDIVIDUAL',
            'location' => 'Arusha, Tanzania',
            'district' => 'Arusha Mjini',
            'verification_status' => 'VERIFIED',
            'rating' => 4.9,
            'skills' => ['Hospitality'],
        ]);

        // 1. CREATE: Create Listing
        $createListingResponse = $this->postJson('/api/listings', [
            'posterId' => $employer->id,
            'title' => 'Chief Pastry Chef Required',
            'category' => 'SERVICES',
            'targetProfession' => 'HOSPITALITY_COOKING',
            'jobTypeLevel' => 'PROFESSIONAL',
            'description' => 'Experienced pastry chef needed for luxury safari lodge in Serengeti.',
            'location' => 'Arusha, Tanzania',
            'district' => 'Arusha Mjini',
            'compensation' => 'TZS 1,500,000 / month',
            'compensationAmountTzs' => 1500000,
            'requiredSkills' => ['French Pastry', 'Baking', 'Food Hygiene'],
            'isEscrowRequired' => true,
        ]);

        $createListingResponse->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Chief Pastry Chef Required');

        $listingId = $createListingResponse->json('data.id');
        $this->assertNotNull($listingId);
        $this->assertDatabaseHas('listings', ['id' => $listingId, 'title' => 'Chief Pastry Chef Required']);

        // 2. READ: List Listings (National Feed)
        $listResponse = $this->getJson('/api/listings');
        $listResponse->assertStatus(200)
            ->assertJsonPath('success', true);
        $this->assertTrue(collect($listResponse->json('data'))->contains('id', $listingId));

        // READ: Filter by Category
        $filterCategoryResponse = $this->getJson('/api/listings?category=SERVICES');
        $filterCategoryResponse->assertStatus(200)
            ->assertJsonPath('success', true);
        $this->assertTrue(collect($filterCategoryResponse->json('data'))->contains('id', $listingId));

        // READ: Single Listing Details
        $singleListingResponse = $this->getJson("/api/listings/{$listingId}");
        $singleListingResponse->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $listingId)
            ->assertJsonPath('data.compensationAmountTzs', 1500000);

        // 3. UPDATE: Moderate Listing via Admin
        $moderateResponse = $this->postJson("/api/admin/listings/{$listingId}/moderate", [
            'status' => 'ACTIVE',
        ]);
        $moderateResponse->assertStatus(200)
            ->assertJsonPath('success', true);

        // 4. DELETE: Delete Listing
        $deleteListingResponse = $this->withHeader('X-User-Id', $employer->id)
            ->deleteJson("/api/listings/{$listingId}");

        $deleteListingResponse->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('listings', ['id' => $listingId]);
    }

    /**
     * Test Applications / Bids CRUD
     */
    public function test_applications_crud(): void
    {
        $employer = User::create([
            'id' => 'emp_app_test',
            'full_name' => 'Zanzibar Spice Tours',
            'phone' => '+255777123456',
            'role' => 'EMPLOYER',
            'account_type' => 'INDIVIDUAL',
            'location' => 'Stone Town, Zanzibar',
            'verification_status' => 'VERIFIED',
            'skills' => ['Tourism'],
        ]);

        $worker = User::create([
            'id' => 'wrk_app_test',
            'full_name' => 'Amina Juma',
            'phone' => '+255777654321',
            'role' => 'JOB_SEEKER',
            'account_type' => 'INDIVIDUAL',
            'location' => 'Stone Town, Zanzibar',
            'verification_status' => 'VERIFIED',
            'skills' => ['Tour Guide', 'English', 'Italian'],
        ]);

        $listing = Listing::create([
            'id' => 'lst_spice_01',
            'poster_id' => $employer->id,
            'poster_name' => 'Zanzibar Spice Tours',
            'poster_verification' => 'VERIFIED',
            'poster_rating' => 5.0,
            'category' => 'SERVICES',
            'target_profession' => 'HOSPITALITY_COOKING',
            'job_type_level' => 'PROFESSIONAL',
            'title' => 'Italian-Speaking Tour Guide',
            'description' => 'Looking for certified guide fluent in Italian for historical spice route tours.',
            'location' => 'Stone Town, Zanzibar',
            'compensation' => 'TZS 80,000 / day',
            'compensation_amount_tzs' => 80000,
            'status' => 'ACTIVE',
            'required_skills' => ['Italian', 'Tour Guide'],
            'date_posted' => now()->toDateString(),
            'deadline' => now()->addDays(7)->toDateString(),
        ]);

        // 1. CREATE: Worker submits application
        $applyResponse = $this->postJson('/api/applications', [
            'listingId' => $listing->id,
            'listingTitle' => $listing->title,
            'applicantId' => $worker->id,
            'applicantName' => $worker->full_name,
            'pitchMessage' => 'Habari! I am a licensed guide fluent in Italian with 4 years experience.',
        ]);

        $applyResponse->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.listingId', $listing->id);

        $appId = $applyResponse->json('data.id');
        $this->assertNotNull($appId);

        // 2. READ: Read Applications for Worker
        $appsListResponse = $this->withHeader('X-User-Id', $worker->id)
            ->getJson('/api/applications');

        $appsListResponse->assertStatus(200)
            ->assertJsonPath('success', true);
        $this->assertTrue(collect($appsListResponse->json('data'))->contains('id', $appId));
    }

    /**
     * Test Escrow Deposit, Dispute, and Release CRUD
     */
    public function test_escrow_financial_crud(): void
    {
        $employer = User::create([
            'id' => 'emp_escrow_user',
            'full_name' => 'Mbeya Green Estates',
            'phone' => '+255754112233',
            'role' => 'EMPLOYER',
            'account_type' => 'INDIVIDUAL',
            'location' => 'Mbeya',
            'verification_status' => 'VERIFIED',
            'skills' => ['Management'],
        ]);

        $worker = User::create([
            'id' => 'wrk_escrow_user',
            'full_name' => 'Daudi Ndunguru',
            'phone' => '+255754445566',
            'role' => 'JOB_SEEKER',
            'account_type' => 'INDIVIDUAL',
            'location' => 'Mbeya',
            'verification_status' => 'VERIFIED',
            'skills' => ['Farming'],
        ]);

        $listing = Listing::create([
            'id' => 'lst_escrow_job_1',
            'poster_id' => $employer->id,
            'poster_name' => $employer->full_name,
            'poster_verification' => 'VERIFIED',
            'poster_rating' => 5.0,
            'category' => 'AGRICULTURE',
            'target_profession' => 'AGRICULTURE',
            'job_type_level' => 'NON_PROFESSIONAL',
            'title' => 'Avocado Orchard Pruning',
            'description' => 'Pruning 500 avocado trees in Rungwe.',
            'location' => 'Mbeya',
            'compensation' => 'TZS 150,000',
            'compensation_amount_tzs' => 150000,
            'status' => 'ACTIVE',
            'required_skills' => ['Pruning'],
            'date_posted' => now()->toDateString(),
            'deadline' => now()->addDays(7)->toDateString(),
        ]);

        // 1. CREATE: Deposit Escrow Payment
        $depositResponse = $this->postJson('/api/escrow/deposit', [
            'listingId' => 'lst_escrow_job_1',
            'listingTitle' => 'Avocado Orchard Pruning',
            'payerName' => $employer->full_name,
            'payeeName' => $worker->full_name,
            'amountTzs' => 150000,
            'paymentMethod' => 'M-PESA',
            'phone' => '+255754112233',
        ]);

        $depositResponse->assertStatus(201)
            ->assertJsonPath('success', true);

        $txId = $depositResponse->json('data.id');
        $this->assertNotNull($txId);
        $this->assertDatabaseHas('escrow_transactions', ['id' => $txId, 'escrow_status' => 'HELD_IN_ESCROW']);

        // 2. READ: View Transactions List
        $txListResponse = $this->withHeader('X-User-Id', $employer->id)
            ->getJson('/api/escrow/transactions');

        $txListResponse->assertStatus(200)
            ->assertJsonPath('success', true);
        $this->assertTrue(collect($txListResponse->json('data'))->contains('id', $txId));

        // 3. UPDATE: Release Escrow Payment to Payee
        $releaseResponse = $this->postJson("/api/escrow/{$txId}/release", [
            'verifiedBy' => $employer->full_name,
        ]);

        $releaseResponse->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('escrow_transactions', [
            'id' => $txId,
            'escrow_status' => 'RELEASED',
        ]);
    }

    /**
     * Test Messaging and Communication CRUD
     */
    public function test_messages_crud(): void
    {
        $sender = User::create([
            'id' => 'usr_sender_1',
            'full_name' => 'Alice Temba',
            'phone' => '+255767112233',
            'role' => 'JOB_SEEKER',
            'account_type' => 'INDIVIDUAL',
            'location' => 'Moshi',
            'verification_status' => 'VERIFIED',
            'skills' => ['Receptionist'],
        ]);

        $listing = Listing::create([
            'id' => 'lst_msg_test_01',
            'poster_id' => 'usr_sender_1',
            'poster_name' => 'Alice Temba',
            'poster_verification' => 'VERIFIED',
            'poster_rating' => 5.0,
            'category' => 'SERVICES',
            'target_profession' => 'HOSPITALITY_COOKING',
            'job_type_level' => 'NON_PROFESSIONAL',
            'title' => 'Catering Assistant Needed',
            'description' => 'Event catering support needed for weekend wedding.',
            'location' => 'Moshi, Kilimanjaro',
            'compensation' => 'TZS 35,000 / day',
            'compensation_amount_tzs' => 35000,
            'status' => 'ACTIVE',
            'required_skills' => ['Catering'],
            'date_posted' => now()->toDateString(),
            'deadline' => now()->addDays(7)->toDateString(),
        ]);

        // 1. CREATE: Send message between users
        $sendResponse = $this->postJson('/api/messages', [
            'listingId' => $listing->id,
            'senderId' => $sender->id,
            'senderName' => $sender->full_name,
            'text' => 'Hello, is this opportunity still open for tomorrow morning?',
        ]);

        $sendResponse->assertStatus(201)
            ->assertJsonPath('success', true);

        $msgId = $sendResponse->json('data.id');
        $this->assertNotNull($msgId);

        // 2. READ: Read messages for Listing thread
        $readResponse = $this->getJson("/api/messages/{$listing->id}");
        $readResponse->assertStatus(200)
            ->assertJsonPath('success', true);
        $this->assertTrue(collect($readResponse->json('data'))->contains('id', $msgId));
    }
}
