<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NotificationItem;
use App\Models\User;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected PushNotificationService $pushService;

    public function __construct(PushNotificationService $pushService)
    {
        $this->pushService = $pushService;
    }

    public function index(Request $request)
    {
        $userId = $request->header('X-User-Id') ?? $request->query('user_id');

        $query = NotificationItem::query();

        if ($userId) {
            $query->where(function ($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->orWhereNull('user_id');
            });
        }

        $notifs = $query->orderBy('created_at', 'desc')->take(50)->get()->map(fn($n) => [
            'id' => (string)$n->id,
            'title' => $n->title,
            'body' => $n->body,
            'channel' => $n->channel,
            'timestamp' => $n->timestamp_str ?? (string)$n->created_at,
            'isRead' => (bool)$n->is_read,
        ]);

        return response()->json([
            'success' => true,
            'data' => $notifs,
        ]);
    }

    public function registerFcmToken(Request $request)
    {
        $validated = $request->validate([
            'userId' => 'nullable|string',
            'user_id' => 'nullable|string',
            'fcmToken' => 'required|string',
            'deviceType' => 'nullable|string',
            'device_type' => 'nullable|string',
        ]);

        $userId = $validated['userId'] ?? $validated['user_id'] ?? $request->header('X-User-Id');
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User ID is required to register FCM token',
            ], 422);
        }

        $user = User::findOrFail($userId);
        $user->fcm_token = $validated['fcmToken'];
        $user->device_type = $validated['deviceType'] ?? $validated['device_type'] ?? 'android';
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'FCM push token registered successfully',
            'data' => [
                'userId' => $user->id,
                'deviceType' => $user->device_type,
            ],
        ]);
    }

    public function sendTestNotification(Request $request)
    {
        $userId = $request->input('userId') ?? $request->input('user_id') ?? $request->header('X-User-Id');
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'userId required'], 422);
        }

        $user = User::findOrFail($userId);
        $title = $request->input('title', 'Jaribio la Arifa / Push Notification Test');
        $body = $request->input('body', 'Huduma ya arifa ya Linkora inafanya kazi kikamilifu kwenye kifaa chako!');

        $notif = $this->pushService->notifyUser($user, $title, $body, ['type' => 'TEST']);

        return response()->json([
            'success' => true,
            'message' => 'Notification dispatched',
            'data' => $notif,
        ]);
    }

    public function markAsRead(string $id)
    {
        $notif = NotificationItem::findOrFail($id);
        $notif->is_read = true;
        $notif->save();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
        ]);
    }
}
