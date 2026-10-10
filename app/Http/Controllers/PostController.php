<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $query = Post::with(['user', 'comments.user', 'likes', 'media'])
            ->withCount(['comments', 'likes'])
            ->latest();

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%");
                    });
            });
        }

        $posts = $query->paginate(10)->withQueryString();

        return view('posts.index', [
            'posts' => $posts,
            'search' => $search,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'content' => ['nullable', 'string', 'max:5000'],
            'media' => ['nullable', 'array', 'max:10'],
            'media.*' => ['required', 'file', 'mimetypes:image/jpeg,image/png,image/webp,image/gif,video/mp4,video/webm,video/quicktime', 'max:20480'],
        ]);

        if (blank($validated['content'] ?? null) && ! $request->hasFile('media')) {
            return back()->withErrors(['content' => 'Postingan harus memiliki teks, gambar, atau video.'])->withInput();
        }

        $stored = [];
        try {
            DB::transaction(function () use ($request, $validated, &$stored): void {
                $post = Post::create([
                    'user_id' => Auth::id(),
                    'code' => Str::random(20),
                    'content' => $validated['content'] ?? null,
                ]);

                foreach ($request->file('media', []) as $position => $file) {
                    $path = $file->store('posts', 'public');
                    $stored[] = $path;
                    $post->media()->create([
                        'path' => $path,
                        'type' => str_starts_with($file->getMimeType(), 'video/') ? 'video' : 'image',
                        'position' => $position,
                    ]);
                }
            });
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($stored);
            throw $exception;
        }

        return redirect()->route('feed')->with('success', 'Postingan baru berhasil dibagikan!');
    }

    public function show(string $code): View
    {
        $post = Post::with(['user', 'media', 'comments' => function ($q) {
            $q->with('user')->oldest();
        }, 'likes'])
            ->withCount(['comments', 'likes'])
            ->where('code', $code)
            ->firstOrFail();

        return view('posts.show', [
            'post' => $post,
        ]);
    }

    public function destroy(int $id): RedirectResponse
    {
        $post = Post::with('media')->findOrFail($id);

        if ($post->user_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki hak untuk menghapus postingan ini.');
        }

        if ($post->image && file_exists(public_path('uploads/posts/' . $post->image))) {
            @unlink(public_path('uploads/posts/' . $post->image));
        }

        $paths = $post->media->pluck('path')->all();
        $post->delete();
        Storage::disk('public')->delete($paths);

        return redirect()->route('feed')->with('success', 'Postingan berhasil dihapus.');
    }

    public function myPosts(): View
    {
        $posts = Post::with(['user', 'comments', 'likes', 'media'])
            ->withCount(['comments', 'likes'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('posts.my-posts', [
            'posts' => $posts,
        ]);
    }
}
