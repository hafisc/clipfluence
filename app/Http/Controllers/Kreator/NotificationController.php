<?php

namespace App\Http\Controllers\Kreator;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get all notifications for current user
     */
    public function index()
    {
        $notifications = Notification::forUser(auth()->id())
            ->latest()
            ->take(10)
            ->get()
            ->map(function($notif) {
                return [
                    'id' => $notif->id,
                    'type' => $notif->type,
                    'icon' => $notif->icon,
                    'title' => $notif->title,
                    'message' => $notif->message,
                    'action_url' => $notif->action_url,
                    'read_at' => $notif->read_at,
                    'time_ago' => $notif->time_ago,
                ];
            });

        $unreadCount = Notification::forUser(auth()->id())
            ->unread()
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark single notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::forUser(auth()->id())->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notification::forUser(auth()->id())
            ->unread()
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }
}
