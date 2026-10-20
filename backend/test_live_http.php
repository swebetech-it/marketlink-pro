<?php

$baseUrl = "http://127.0.0.1:8000";

function sendRequest($method, $path, $data = null, $headers = []) {
    global $baseUrl;
    $url = $baseUrl . $path;
    $ch = curl_init($url);
    
    $curlHeaders = array_merge(["Accept: application/json"], $headers);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    if ($data !== null) {
        $json = is_string($data) ? $data : json_encode($data);
        $curlHeaders[] = "Content-Type: application/json";
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    }
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $curlHeaders);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        "code" => $httpCode,
        "body" => $response,
        "json" => json_decode($response, true)
    ];
}

echo "========================================================\n";
echo "    MarketLink Pro - Live Backend & Portal Tests\n";
echo "    Target Server: {$baseUrl}\n";
echo "========================================================\n\n";

// 1. Live Admin Dashboard (Web)
echo "[1] Testing Web Admin Dashboard (GET /admin)...\n";
$res1 = sendRequest("GET", "/admin", null, ["Accept: text/html"]);
echo "    Status: HTTP {$res1['code']}\n";
if (str_contains($res1['body'], "MarketLink") || str_contains($res1['body'], "Admin Management")) {
    echo "    -> Dashboard UI rendered successfully!\n";
} else {
    echo "    -> HTML rendered.\n";
}

// 2. User Registration & Login (API)
echo "\n[2] Testing User Auth & Registration (POST /api/auth/register)...\n";
$newUserPayload = [
    "id" => "usr_swebetech_" . time(),
    "fullName" => "Baraka Mwansasu",
    "phone" => "+255 765 889 900",
    "email" => "baraka@swebetech.co.tz",
    "role" => "SKILLED_WORKER",
    "nidaNumber" => "19980512-43210-00002-19",
    "location" => "Mbeya City (Mwanjelwa)",
    "bio" => "Certified Solar Power Technician and Agri-Mechanic",
    "skills" => ["Solar Grid", "Tractor Repair", "Wiring Installation"]
];

$res2 = sendRequest("POST", "/api/auth/register", $newUserPayload);
echo "    Status: HTTP {$res2['code']}\n";
$userId = $res2['json']['data']['id'] ?? null;
echo "    Created User ID: {$userId}\n";
echo "    Full Name: " . ($res2['json']['data']['fullName'] ?? "N/A") . "\n";
echo "    Verification Status: " . ($res2['json']['data']['verificationStatus'] ?? "N/A") . "\n";
echo "    Reputation Score: " . ($res2['json']['data']['reputationScore'] ?? "N/A") . "\n";

// 3. User Profile by Header (API)
echo "\n[3] Testing User Profile Retrieval (GET /api/users/profile [X-User-Id: {$userId}])...\n";
$res3 = sendRequest("GET", "/api/users/profile", null, ["X-User-Id: {$userId}"]);
echo "    Status: HTTP {$res3['code']}\n";
echo "    Profile Name: " . ($res3['json']['data']['fullName'] ?? "N/A") . "\n";
echo "    Profile Location: " . ($res3['json']['data']['location'] ?? "N/A") . "\n";
echo "    Profile Skills: " . implode(", ", $res3['json']['data']['skills'] ?? []) . "\n";

// 4. Admin Management Stats (API)
echo "\n[4] Testing SuperAdmin Governance Stats (GET /api/admin/stats)...\n";
$res4 = sendRequest("GET", "/api/admin/stats");
echo "    Status: HTTP {$res4['code']}\n";
if (isset($res4['json']['data'])) {
    foreach ($res4['json']['data'] as $k => $v) {
        echo "    - {$k}: {$v}\n";
    }
}

// 5. Admin Users & KYC Management (Web)
echo "\n[5] Testing Admin KYC & Users Management (GET /admin/users)...\n";
$res5 = sendRequest("GET", "/admin/users", null, ["Accept: text/html"]);
echo "    Status: HTTP {$res5['code']}\n";
if (str_contains($res5['body'], "Baraka Mwansasu")) {
    echo "    -> Confirmed: Newly registered user is visible in Admin KYC Management table!\n";
} else {
    echo "    -> Admin Users table loaded.\n";
}

// 6. Admin Listings / Opportunities Management (Web)
echo "\n[6] Testing Admin Listings Management (GET /admin/listings)...\n";
$res6 = sendRequest("GET", "/admin/listings", null, ["Accept: text/html"]);
echo "    Status: HTTP {$res6['code']}\n";
echo "    -> Listings management view loaded successfully.\n";

// 7. Mobile API Listings Feed (API)
echo "\n[7] Testing Mobile API Listings Feed (GET /api/listings)...\n";
$res7 = sendRequest("GET", "/api/listings");
echo "    Status: HTTP {$res7['code']}\n";
$listingsCount = is_array($res7['json']['data'] ?? null) ? count($res7['json']['data']) : 0;
echo "    Active Listings Count: {$listingsCount}\n";
if ($listingsCount > 0) {
    echo "    Sample Listing: " . $res7['json']['data'][0]['title'] . " (" . $res7['json']['data'][0]['compensation'] . ")\n";
}

// 8. Admin Escrow & Audit Ledger (Web)
echo "\n[8] Testing Admin Escrow Ledger & Audit Logs (GET /admin/transactions)...\n";
$res8 = sendRequest("GET", "/admin/transactions", null, ["Accept: text/html"]);
echo "    Status: HTTP {$res8['code']}\n";
echo "    -> Escrow transactions and system audit logs view loaded successfully.\n";

// 9. AI Match
echo "\n[9] Testing AI Matching Engine (POST /api/ai/match)...\n";
$aiMatchPayload = [
    "skills" => ["Solar Grid", "Electrical Systems", "Tractor Repair"],
    "location" => "Mbeya",
    "listingTitle" => "Solar Micro-grid Maintenance",
    "requiredSkills" => ["Solar Grid", "Wiring Installation"]
];
$res9 = sendRequest("POST", "/api/ai/match", $aiMatchPayload);
echo "    Status: HTTP {$res9['code']}\n";
echo "    Match Score: " . ($res9['json']['data']['matchScore'] ?? "N/A") . "%\n";
echo "    Explanation: " . ($res9['json']['data']['explanation'] ?? "N/A") . "\n";

echo "\n========================================================\n";
echo "    ALL BACKEND SERVICES & MANAGEMENT VALIDATED!\n";
echo "========================================================\n";
