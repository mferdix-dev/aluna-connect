<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\SocialNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CommentController extends Controller
{
    public function store(Request $request, string $code): RedirectResponse
    {
        $post = Post::where('code', $code)->firstOrFail();

        $validated = $request->validate([
            'content' => ['nullable', 'string', 'max:1500'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
        ]);

        if (empty($validated['content']) && ! $request->hasFile('image')) {
            return back()->withErrors(['content' => 'Komentar tidak boleh kosong.'])->withInput();
        }

        $imageName = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/comments'), $imageName);
        }

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'content' => $validated['content'] ?? null,
            'image' => $imageName,
        ]);

        // Trigger notification if not commenting on own post
        if ($post->user_id !== Auth::id()) {
            SocialNotification::create([
                'user_id' => $post->user_id,
                'actor_id' => Auth::id(),
                'type' => 'comment',
                'title' => 'Komentar Baru',
                'message' => Auth::user()->name . ' mengomentari postingan Anda: "' . Str::limit($comment->content ?? 'Lampiran gambar', 45) . '"',
                'link' => route('posts.show', $post->code),
            ]);
        }

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki hak untuk menghapus komentar ini.');
        }

        if ($comment->image && file_exists(public_path('uploads/comments/' . $comment->image))) {
            @unlink(public_path('uploads/comments/' . $comment->image));
        }

        $comment->delete();

        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}
