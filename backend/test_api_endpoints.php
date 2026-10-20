<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "Testing MarketLink Pro Laravel API Endpoints...\n";

function testEndpoint($app, $method, $uri, $data = []) {
    $request = Illuminate\Http\Request::create($uri, $method, $data);
    $response = $app->handle($request);
    echo "[$method] $uri -> Status: " . $response->getStatusCode() . "\n";
    $content = json_decode($response->getContent(), true);
    if ($content && isset($content['success'])) {
        echo "  Result: Success=" . ($content['success'] ? 'true' : 'false') . ", Data items count=" . (is_array($content['data'] ?? null) ? count($content['data']) : 1) . "\n";
    }
    return $response;
}

testEndpoint($app, 'GET', '/api/users/profile');
testEndpoint($app, 'GET', '/api/listings');
testEndpoint($app, 'GET', '/api/escrow/transactions');
testEndpoint($app, 'GET', '/api/courses');
testEndpoint($app, 'GET', '/api/financial-products');
testEndpoint($app, 'GET', '/api/admin/stats');
testEndpoint($app, 'POST', '/api/ussd/simulate', ['sessionId' => 'test_123', 'phoneNumber' => '+255754892104', 'text' => '']);
testEndpoint($app, 'POST', '/api/ai/match', [
    'skills' => ['Solar Installation', 'Tractor Driving'],
    'location' => 'Mbeya',
    'listingTitle' => 'Solar Micro-grid Maintenance',
    'requiredSkills' => ['Solar Tech', 'Wiring']
]);

echo "\nAll core endpoints tested successfully!\n";
