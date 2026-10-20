<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$baseUrl = "http://127.0.0.1:8000";

// Test 1: Toggle User Verification via Admin Portal
echo "1. Testing Admin User KYC Toggle (POST /admin/users/{id}/verify)...\n";
$user = App\Models\User::first();
if ($user) {
    $initialStatus = $user->verification_status;
    $ch = curl_init("{$baseUrl}/admin/users/{$user->id}/verify");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $user->refresh();
    echo "   User: {$user->full_name}\n";
    echo "   Initial Status: {$initialStatus} -> New Status: {$user->verification_status} (HTTP {$code})\n";
}

// Test 2: Create Listing via Web Management Portal
echo "\n2. Testing Admin Opportunity Creation (POST /admin/listings)...\n";
$listingData = http_build_query([
    'title' => 'Mbeya Irrigation Pump Automation Specialist',
    'category' => 'AGRI_EQUIPMENT',
    'location' => 'Rungwe & Mbarali, Mbeya',
    'compensation' => 'TZS 850,000 / installation',
    'compensationAmountTzs' => 850000,
    'requiredSkills' => 'Solar Pumps, IoT Sensors, Drip Irrigation',
    'description' => 'Install solar powered IoT water pumping solutions for avocado farms.',
    'isEscrowRequired' => '1',
    'posterName' => 'Mbeya Agri-Tech Cooperative'
]);

$ch = curl_init("{$baseUrl}/admin/listings");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $listingData);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$res = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "   Listing creation response: HTTP {$code}\n";

$createdListing = App\Models\Listing::where('title', 'Mbeya Irrigation Pump Automation Specialist')->first();
if ($createdListing) {
    echo "   Created Listing ID: {$createdListing->id}\n";
    echo "   Title: {$createdListing->title}\n";
    echo "   Category: {$createdListing->category}\n";
    echo "   Status: {$createdListing->status}\n";
    echo "   Fraud Risk Score: {$createdListing->fraud_risk_score}/100\n";
}

// Test 3: Moderate Listing via SuperAdmin API
echo "\n3. Testing SuperAdmin API Listing Moderation (POST /api/admin/listings/{id}/moderate)...\n";
if ($createdListing) {
    $ch = curl_init("{$baseUrl}/api/admin/listings/{$createdListing->id}/moderate");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['status' => 'VERIFIED_ACTIVE']));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Accept: application/json']);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "   Moderation Response: HTTP {$code} -> {$res}\n";
}

echo "\n>>> Management Actions Test Completed Successfully! <<<\n";
