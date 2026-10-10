<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\SocialNotification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(): View
    {
        $userId = Auth::id();

        // Get all users with whom the current user has chatted
        $partnerIds = Message::where('sender_id', $userId)
            ->pluck('receiver_id')
            ->merge(Message::where('receiver_id', $userId)->pluck('sender_id'))
            ->unique();

        $conversations = User::whereIn('id', $partnerIds)
            ->get()
            ->map(function ($partner) use ($userId) {
                $lastMessage = Message::where(function ($q) use ($userId, $partner) {
                    $q->where('sender_id', $userId)->where('receiver_id', $partner->id);
                })->orWhere(function ($q) use ($userId, $partner) {
                    $q->where('sender_id', $partner->id)->where('receiver_id', $userId);
                })->latest()->first();

                $unreadCount = Message::where('sender_id', $partner->id)
                    ->where('receiver_id', $userId)
                    ->where('is_read', false)
                    ->count();

                $partner->last_message = $lastMessage;
                $partner->unread_messages_count = $unreadCount;
                return $partner;
            })
            ->sortByDesc(fn ($p) => $p->last_message?->created_at);

        $suggestedUsers = User::where('id', '!=', $userId)->take(10)->get();

        return view('messages.index', [
            'conversations' => $conversations,
            'suggestedUsers' => $suggestedUsers,
            'activeUser' => null,
            'messages' => collect(),
        ]);
    }

    public function show(string $username): View
    {
        $userId = Auth::id();
        $targetUser = User::where('username', strtolower($username))->firstOrFail();
        abort_if($targetUser->id === $userId, 404);

        // Mark incoming messages as read
        Message::where('sender_id', $targetUser->id)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Get messages between both users
        $messages = Message::where(function ($q) use ($userId, $targetUser) {
            $q->where('sender_id', $userId)->where('receiver_id', $targetUser->id);
        })->orWhere(function ($q) use ($userId, $targetUser) {
            $q->where('sender_id', $targetUser->id)->where('receiver_id', $userId);
        })->oldest()->get();

        // Get conversation partners
        $partnerIds = Message::where('sender_id', $userId)
            ->pluck('receiver_id')
            ->merge(Message::where('receiver_id', $userId)->pluck('sender_id'))
            ->push($targetUser->id)
            ->unique();

        $conversations = User::whereIn('id', $partnerIds)
            ->get()
            ->map(function ($partner) use ($userId) {
                $lastMessage = Message::where(function ($q) use ($userId, $partner) {
                    $q->where('sender_id', $userId)->where('receiver_id', $partner->id);
                })->orWhere(function ($q) use ($userId, $partner) {
                    $q->where('sender_id', $partner->id)->where('receiver_id', $userId);
                })->latest()->first();

                $unreadCount = Message::where('sender_id', $partner->id)
                    ->where('receiver_id', $userId)
                    ->where('is_read', false)
                    ->count();

                $partner->last_message = $lastMessage;
                $partner->unread_messages_count = $unreadCount;
                return $partner;
            })
            ->sortByDesc(fn ($p) => $p->last_message?->created_at);

        $suggestedUsers = User::where('id', '!=', $userId)->take(8)->get();

        return view('messages.index', [
            'conversations' => $conversations,
            'activeUser' => $targetUser,
            'messages' => $messages,
            'suggestedUsers' => $suggestedUsers,
        ]);
    }

    public function store(Request $request, string $username): JsonResponse|RedirectResponse
    {
        $targetUser = User::where('username', strtolower($username))->firstOrFail();
        abort_if($targetUser->id === Auth::id(), 404);

        $validated = $request->validate([
            'content' => ['required', 'string', 'max:3000'],
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $targetUser->id,
            'content' => $validated['content'],
            'is_read' => false,
        ]);

        // Trigger notification
        SocialNotification::create([
            'user_id' => $targetUser->id,
            'actor_id' => Auth::id(),
            'type' => 'message',
            'title' => 'Pesan Baru',
            'message' => Auth::user()->name . ': "' . Str::limit($message->content, 45) . '"',
            'link' => route('messages.show', Auth::user()->username),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'content' => $message->content,
                    'sender_id' => $message->sender_id,
                    'is_me' => true,
                    'created_at_human' => $message->created_at->format('H:i'),
                ],
            ]);
        }

        return redirect()->route('messages.show', $targetUser->username);
    }

    public function poll(Request $request, string $username): JsonResponse
    {
        $targetUser = User::where('username', strtolower($username))->firstOrFail();
        abort_if($targetUser->id === Auth::id(), 404);
        $afterId = (int) $request->input('after_id', 0);
        $userId = Auth::id();

        $newMessages = Message::where('id', '>', $afterId)
            ->where(function ($q) use ($userId, $targetUser) {
                $q->where(fn ($query) => $query->where('sender_id', $targetUser->id)->where('receiver_id', $userId))
                    ->orWhere(fn ($query) => $query->where('sender_id', $userId)->where('receiver_id', $targetUser->id));
            })
            ->oldest()
            ->get();

        // Mark as read
        Message::whereIn('id', $newMessages->pluck('id'))
            ->where('receiver_id', $userId)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'messages' => $newMessages->map(fn ($m) => [
                'id' => $m->id,
                'content' => $m->content,
                'sender_id' => $m->sender_id,
                'is_me' => $m->sender_id === $userId,
                'created_at_human' => $m->created_at->format('H:i'),
            ]),
        ]);
    }
}
