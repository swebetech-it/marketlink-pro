<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    public function forListing(string $listingId)
    {
        $messages = Message::where('listing_id', $listingId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn($m) => [
                'id' => (string)$m->id,
                'listingId' => (string)$m->listing_id,
                'senderName' => $m->sender_name,
                'receiverName' => $m->receiver_name,
                'text' => $m->text,
                'timestamp' => $m->timestamp_str ?? date('h:i A', strtotime($m->created_at)),
                'isFromMe' => $m->sender_name === 'Juma Bakari' || $m->sender_name === 'Juma Bakari Mwasiposa',
            ]);

        return response()->json([
            'success' => true,
            'data' => $messages,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'listingId' => 'required|string',
            'text' => 'required|string',
        ]);

        $currentUser = User::where('id', 'current_user')->first() ?? User::first();
        $senderName = $currentUser ? $currentUser->full_name : 'Juma Bakari';

        $msg = Message::create([
            'id' => 'msg_' . round(microtime(true) * 1000),
            'listing_id' => $validated['listingId'],
            'sender_id' => $currentUser ? $currentUser->id : null,
            'sender_name' => $senderName,
            'receiver_id' => null,
            'receiver_name' => 'Poster/Client',
            'text' => $validated['text'],
            'timestamp_str' => date('h:i A'),
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => (string)$msg->id,
                'listingId' => (string)$msg->listing_id,
                'senderName' => $msg->sender_name,
                'receiverName' => $msg->receiver_name,
                'text' => $msg->text,
                'timestamp' => $msg->timestamp_str,
                'isFromMe' => true,
            ],
        ], 201);
    }
}
