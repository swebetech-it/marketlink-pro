<?php

namespace App\Services;

use App\Models\User;
use App\Models\NotificationItem;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    protected string $fcmServerKey;

    public function __construct()
    {
        $this->fcmServerKey = env('FCM_SERVER_KEY', '');
    }

    /**
     * Dispatch push notification to a specific user and create an in-app notification item.
     */
    public function notifyUser(User $user, string $title, string $body, array $payload = []): NotificationItem
    {
        // 1. Create In-App Notification record
        $notification = NotificationItem::create([
            'id' => 'notif_' . round(microtime(true) * 1000) . '_' . substr(md5(uniqid()), 0, 4),
            'user_id' => $user->id,
            'title' => $title,
            'body' => $body,
            'channel' => !empty($user->fcm_token) ? 'Push (FCM)' : 'In-App',
            'timestamp_str' => 'Just now',
            'is_read' => false,
        ]);

        // 2. Dispatch FCM Push if token exists
        if (!empty($user->fcm_token)) {
            $this->sendFcmDirect($user->fcm_token, $title, $body, $payload);
        } else {
            Log::info("[FCM Push (Local/Mock)] To User: {$user->id} ({$user->full_name}) | Title: {$title} | Body: {$body}");
        }

        return $notification;
    }

    /**
     * Send direct HTTP request to FCM
     */
    public function sendFcmDirect(string $fcmToken, string $title, string $body, array $payload = []): bool
    {
        if (empty($this->fcmServerKey)) {
            Log::info("[FCM Push (No Server Key)] Target Token: " . substr($fcmToken, 0, 15) . "... | {$title}: {$body}");
            return true;
        }

        try {
            // Legacy FCM HTTP protocol / FCM HTTP v1 compatible fallback
            $response = Http::withHeaders([
                'Authorization' => 'key=' . $this->fcmServerKey,
                'Content-Type' => 'application/json',
            ])->post('https://fcm.googleapis.com/fcm/send', [
                'to' => $fcmToken,
                'priority' => 'high',
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                    'sound' => 'default',
                    'click_action' => 'OPEN_MARKETLINK_MAIN',
                ],
                'data' => array_merge([
                    'title' => $title,
                    'body' => $body,
                ], $payload),
            ]);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error("[FCM Push Error] " . $e->getMessage());
            return false;
        }
    }

    /**
     * Alert matched candidates when a new listing is published
     */
    public function notifyMatchingCandidates($listing): int
    {
        $query = User::query();

        if (!empty($listing->target_profession)) {
            $query->where('primary_profession', $listing->target_profession);
        }

        if (!empty($listing->district)) {
            $query->where('district', $listing->district);
        }

        $candidates = $query->limit(50)->get();
        $dispatched = 0;

        foreach ($candidates as $candidate) {
            if ($candidate->id === $listing->poster_id) {
                continue;
            }

            $title = "Fursa Mpya: {$listing->title}";
            $body = "Kazi mpya inayolingana na ujuzi wako imetangazwa eneo la {$listing->location}. Malipo: {$listing->compensation}.";

            $this->notifyUser($candidate, $title, $body, [
                'type' => 'NEW_LISTING',
                'listing_id' => (string)$listing->id,
            ]);
            $dispatched++;
        }

        return $dispatched;
    }
}
