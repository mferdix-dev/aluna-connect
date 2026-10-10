<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Models\SocialNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Request $request, string $code): JsonResponse|RedirectResponse
    {
        $post = Post::where('code', $code)->firstOrFail();
        $user = Auth::user();

        $like = Like::where('post_id', $post->id)->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            Like::create([
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]);
            $liked = true;

            // Trigger notification if not liking own post
            if ($post->user_id !== $user->id) {
                SocialNotification::create([
                    'user_id' => $post->user_id,
                    'actor_id' => $user->id,
                    'type' => 'like',
                    'title' => 'Suka Baru',
                    'message' => "{$user->name} menyukai postingan Anda.",
                    'link' => route('posts.show', $post->code),
                ]);
            }
        }

        $count = $post->likes()->count();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'liked' => $liked,
                'count' => $count,
            ]);
        }

        return back();
    }
}
