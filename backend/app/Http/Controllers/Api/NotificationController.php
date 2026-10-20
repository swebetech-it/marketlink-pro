<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NotificationItem;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
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
