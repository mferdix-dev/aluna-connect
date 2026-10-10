@extends('layouts.app')

@section('content')
    <!-- Back to Feed Header -->
    <div class="flex items-center gap-2 mb-2">
        <a href="{{ route('feed') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali ke Beranda</span>
        </a>
    </div>

    <!-- Main Post Component -->
    @include('components.post-card', ['post' => $post])

    <!-- Comments Section Container -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-sm space-y-5">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <span>Komentar</span>
                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">{{ $post->comments->count() }}</span>
            </h3>
        </div>

        <!-- Add Comment Form -->
        <form action="{{ route('comments.store', $post->code) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <div class="flex items-start gap-3">
                <img src="{{ Auth::user()->avatar_url }}" class="w-8 h-8 rounded-full object-cover border border-slate-200 dark:border-slate-700 shrink-0" alt="Avatar">
                <div class="flex-1 min-w-0">
                    <textarea name="content" rows="2" required
                        placeholder="Tulis komentar Anda..."
                        class="w-full bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition resize-none"></textarea>

                    <!-- Preview Container -->
                    <div id="comment-preview-wrapper" class="hidden mt-2 relative rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 max-h-48 bg-slate-100 dark:bg-slate-800">
                        <img id="comment-image-preview" src="" class="w-full h-full object-cover" alt="Comment Image Preview">
                        <button type="button" onclick="clearCommentImagePreview()"
                            class="absolute top-2 right-2 p-1 rounded-full bg-slate-900/70 text-white hover:bg-slate-900 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <label class="cursor-pointer inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 transition">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Lampirkan Gambar</span>
                            <input type="file" name="image" id="comment-image-input" accept="image/*" class="hidden" onchange="previewCommentImage(event)">
                        </label>

                        <button type="submit"
                            class="px-4 py-1.5 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition">
                            Kirim Komentar
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Comments List -->
        <div class="space-y-4 pt-2">
            @forelse ($post->comments as $comment)
                <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50/70 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
                    <a href="{{ route('profile.show', $comment->user->username) }}" class="shrink-0">
                        <img src="{{ $comment->user->avatar_url }}" class="w-8 h-8 rounded-full object-cover border border-slate-200 dark:border-slate-700" alt="{{ $comment->user->name }}">
                    </a>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <a href="{{ route('profile.show', $comment->user->username) }}" class="font-bold text-xs text-slate-800 dark:text-slate-200 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                    {{ $comment->user->name }}
                                </a>
                                @if ($comment->user->isAdmin())
                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">Admin</span>
                                @endif
                                <span class="text-[11px] text-slate-400 dark:text-slate-500">&middot; {{ $comment->created_at->diffForHumans() }}</span>
                            </div>

                            @if (Auth::id() === $comment->user_id || Auth::user()->isAdmin())
                                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Hapus komentar ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus Komentar" class="text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>

                        @if ($comment->content)
                            <p class="text-xs text-slate-700 dark:text-slate-300 mt-1 break-words whitespace-pre-line leading-relaxed">
                                {{ $comment->content }}
                            </p>
                        @endif

                        @if ($comment->image_url)
                            <div class="mt-2 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 max-w-sm">
                                <a href="{{ $comment->image_url }}" target="_blank">
                                    <img src="{{ $comment->image_url }}" loading="lazy" class="w-full max-h-48 object-cover hover:scale-105 transition duration-200" alt="Comment Image">
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-6 text-xs text-slate-400 dark:text-slate-500">
                    Belum ada komentar pada postingan ini. Jadilah yang pertama berkomentar!
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function previewCommentImage(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('comment-image-preview').src = e.target.result;
                    document.getElementById('comment-preview-wrapper').classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function clearCommentImagePreview() {
            document.getElementById('comment-image-input').value = '';
            document.getElementById('comment-image-preview').src = '';
            document.getElementById('comment-preview-wrapper').classList.add('hidden');
        }
    </script>
@endsection
