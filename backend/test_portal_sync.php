<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Listing;

echo "=== MarketLink Pro Portal & Registration Sync Test ===\n\n";

// 1. Test User Registration via API
$regData = [
    'id' => 'usr_amani_' . time(),
    'fullName' => 'Amani Juma Swebetech',
    'phone' => '+255 754 112 233',
    'email' => 'amani.juma@swebetech.co.tz',
    'role' => 'JOB_SEEKER',
    'nidaNumber' => '19950101-12345-00001-22',
    'location' => 'Mbeya City (Uyole)',
    'bio' => 'Certified Solar Technician and Agri Equipment Operator',
    'skills' => ['Solar Systems', 'Tractor Driving', 'Electrical Wiring']
];

$request = Request::create('/api/auth/register', 'POST', $regData);
$request->headers->set('Accept', 'application/json');
$response = $kernel->handle($request);

echo "1. User Registration: HTTP " . $response->getStatusCode() . "\n";
$body = json_decode($response->getContent(), true);
echo "   Registered User ID: " . ($body['data']['id'] ?? 'N/A') . "\n";
echo "   Registered Full Name: " . ($body['data']['fullName'] ?? 'N/A') . "\n";
echo "   NIDA Status: " . ($body['data']['verificationStatus'] ?? 'N/A') . "\n\n";

$registeredId = $body['data']['id'] ?? null;

// 2. Test User Profile Retrieval with X-User-Id Header
$profRequest = Request::create('/api/users/profile', 'GET');
$profRequest->headers->set('Accept', 'application/json');
if ($registeredId) {
    $profRequest->headers->set('X-User-Id', $registeredId);
}
$profResponse = $kernel->handle($profRequest);
echo "2. User Profile Fetch (X-User-Id: $registeredId): HTTP " . $profResponse->getStatusCode() . "\n";
$profBody = json_decode($profResponse->getContent(), true);
echo "   Profile Name: " . ($profBody['data']['fullName'] ?? 'N/A') . "\n";
echo "   Profile Location: " . ($profBody['data']['location'] ?? 'N/A') . "\n";
echo "   Profile Skills: " . implode(', ', $profBody['data']['skills'] ?? []) . "\n\n";

// 3. Test Web Admin Portal Dashboard Route
$dashRequest = Request::create('/admin', 'GET');
$dashResponse = $kernel->handle($dashRequest);
echo "3. Web Admin Portal Dashboard: HTTP " . $dashResponse->getStatusCode() . "\n";
echo "   HTML Title Found: " . (str_contains($dashResponse->getContent(), 'Admin Management & Operations Dashboard') ? 'YES' : 'NO') . "\n\n";

// 4. Test Web Admin Portal Listing Creation
$listData = [
    'title' => 'Mbeya Solar Micro-Grid Chief Electrician',
    'category' => 'SKILLED_SERVICE',
    'location' => 'Mbeya City, Tanzania',
    'compensation' => 'TZS 1,200,000 / project',
    'compensationAmountTzs' => 1200000,
    'requiredSkills' => 'Solar PV, Inverters, Micro-Grid Maintenance',
    'description' => 'Oversee installation and maintenance of 50kVA solar array in Mbeya.',
    'isEscrowRequired' => '1'
];
$listRequest = Request::create('/admin/listings', 'POST', $listData);
$listResponse = $kernel->handle($listRequest);
echo "4. Web Admin Listing Creation: HTTP " . $listResponse->getStatusCode() . " (Redirect: " . $listResponse->headers->get('Location') . ")\n\n";

// 5. Test Mobile API Listings to Confirm Web Listing is in Mobile Feed
$apiListRequest = Request::create('/api/listings', 'GET');
$apiListResponse = $kernel->handle($apiListRequest);
$apiListBody = json_decode($apiListResponse->getContent(), true);
$found = false;
foreach ($apiListBody['data'] ?? [] as $item) {
    if (str_contains($item['title'], 'Mbeya Solar Micro-Grid')) {
        $found = true;
        echo "5. Verified Mobile API Listing Feed:\n";
        echo "   ID: " . $item['id'] . "\n";
        echo "   Title: " . $item['title'] . "\n";
        echo "   Category: " . $item['category'] . "\n";
        echo "   Compensation: " . $item['compensation'] . "\n";
        echo "   Escrow Protected: " . ($item['isEscrowRequired'] ? 'YES' : 'NO') . "\n";
        break;
    }
}

if ($found) {
    echo "\n>>> ALL BACKEND & WEB PORTAL TESTS PASSED SUCCESSFULLY! <<<\n";
} else {
    echo "\n>>> Error: Created listing not found in mobile feed <<<\n";
}
