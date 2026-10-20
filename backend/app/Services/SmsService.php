<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected string $username;
    protected string $apiKey;

    public function __construct()
    {
        $this->username = env('AFRICAS_TALKING_USERNAME', 'sandbox');
        $this->apiKey = env('AFRICAS_TALKING_API_KEY', '');
    }

    public function sendSms(string $toPhoneNumber, string $message): bool
    {
        if (empty($this->apiKey)) {
            Log::info("[SMS Sandbox / Africa's Talking] To: {$toPhoneNumber} | Message: {$message}");
            return true;
        }

        try {
            $url = $this->username === 'sandbox'
                ? 'https://api.sandbox.africastalking.com/version1/messaging'
                : 'https://api.africastalking.com/version1/messaging';

            $response = Http::withHeaders([
                'apiKey' => $this->apiKey,
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json',
            ])->asForm()->post($url, [
                'username' => $this->username,
                'to' => $toPhoneNumber,
                'message' => $message,
                'from' => 'MarketLink',
            ]);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error("SMS Dispatch Failed: " . $e->getMessage());
            return false;
        }
    }
}
