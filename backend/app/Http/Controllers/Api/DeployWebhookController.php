<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeployWebhookController extends Controller
{
    /**
     * Handle incoming deployment webhook from GitHub or manual trigger.
     */
    public function handle(Request $request)
    {
        $configuredSecret = env('DEPLOY_WEBHOOK_SECRET');

        // Security check: if secret is configured, verify header or token
        if (!empty($configuredSecret)) {
            $token = $request->header('X-Deploy-Token')
                ?? $request->query('token')
                ?? $request->input('token');

            $hubSignature = $request->header('X-Hub-Signature-256');

            $isTokenValid = ($token === $configuredSecret);
            $isHubValid = false;

            if ($hubSignature) {
                $payload = $request->getContent();
                $expectedSig = 'sha256=' . hash_hmac('sha256', $payload, $configuredSecret);
                $isHubValid = hash_equals($expectedSig, $hubSignature);
            }

            if (!$isTokenValid && !$isHubValid) {
                Log::warning("DeployWebhook: Unauthorized attempt from IP " . $request->ip());
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized webhook token or signature',
                ], 403);
            }
        }

        // Run deployment script in background
        $deployScript = base_path('../server_config/deploy.sh');
        if (!file_exists($deployScript)) {
            $deployScript = base_path('server_config/deploy.sh');
        }

        if (file_exists($deployScript)) {
            Log::info("DeployWebhook: Triggering deployment script at {$deployScript}");
            // Trigger in background and redirect output to deployment log
            $logFile = storage_path('logs/deploy.log');
            shell_exec("nohup bash {$deployScript} > {$logFile} 2>&1 &");

            return response()->json([
                'success' => true,
                'message' => 'Deployment initiated successfully in the background.',
                'log_path' => 'storage/logs/deploy.log',
            ]);
        }

        // Fallback: run Artisan update directly
        Log::info("DeployWebhook: Running Artisan app:update directly");
        \Illuminate\Support\Facades\Artisan::call('app:update', ['--force' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Platform update completed via Artisan.',
        ]);
    }
}
