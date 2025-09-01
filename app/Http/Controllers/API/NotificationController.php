<?php

// app/Http/Controllers/API/NotificationController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($notifications);
    }

    public function markAsRead(Request $request, $id)
    {
        $user = $request->user();

        $notification = $user->notifications()->findOrFail($id);
        $notification->is_read = true;
        $notification->save();

        return response()->json(['message' => 'Notification marked as read']);
    }

    public function markAllAsRead(Request $request)
    {
        $user = $request->user();

        $user->notifications()
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['message' => 'All notifications marked as read']);
    }

    public function unreadCount(Request $request)
    {
        $user = $request->user();

        $count = $user->notifications()
            ->where('is_read', false)
            ->count();

        return response()->json(['unread_count' => $count]);
    }
}

