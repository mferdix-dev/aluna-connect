<?php

namespace App\Http\Controllers;

use App\Models\SocialNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $notifications = SocialNotification::with('actor')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(15);

        // Mark visible as read
        SocialNotification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('notifications.index', [
            'notifications' => $notifications,
        ]);
    }

    public function markAllRead(): RedirectResponse
    {
        SocialNotification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'Semua notifikasi telah ditandai sudah dibaca.');
    }

    public function unreadCount(Request $request): JsonResponse
    {
        $user = Auth::user();

        $notificationsCount = $user->unreadNotificationsCount();
        $messagesCount = $user->unreadMessagesCount();

        // Get latest unread notification for browser toast
        $latest = SocialNotification::with('actor')
            ->where('user_id', $user->id)
            ->where('is_read', false)
            ->latest()
            ->first();

        return response()->json([
            'success' => true,
            'notifications_count' => $notificationsCount,
            'messages_count' => $messagesCount,
            'total_unread' => $notificationsCount + $messagesCount,
            'latest' => $latest ? [
                'id' => $latest->id,
                'title' => $latest->title,
                'message' => $latest->message,
                'link' => $latest->link,
                'actor_name' => $latest->actor->name,
                'actor_avatar' => $latest->actor->avatar_url,
                'type' => $latest->type,
            ] : null,
        ]);
    }
}
