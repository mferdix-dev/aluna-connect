<article class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-sm hover:shadow-md transition duration-200">
    <!-- Header: User info & Options -->
    <div class="flex items-start justify-between gap-3 mb-3">
        <a href="{{ route('profile.show', $post->user->username) }}" class="flex items-center gap-3 group min-w-0">
            <img src="{{ $post->user->avatar_url }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-700 shrink-0 group-hover:scale-105 transition" alt="{{ $post->user->name }}">
            <div class="min-w-0">
                <div class="flex items-center gap-1.5 flex-wrap">
                    <span class="font-bold text-sm text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition truncate">{{ $post->user->name }}</span>
                    @if ($post->user->isAdmin())
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200/60 dark:border-indigo-800">Admin</span>
                    @endif
                </div>
                <div class="flex items-center gap-1.5 text-xs text-slate-400 dark:text-slate-500">
                    <span>@<span>{{ $post->user->username }}</span></span>
                    <span>&middot;</span>
                    <time datetime="{{ $post->created_at }}">{{ $post->created_at->diffForHumans() }}</time>
                </div>
            </div>
        </a>

        @if (Auth::id() === $post->user_id || Auth::user()->isAdmin())
            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus postingan ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" title="Hapus Postingan" class="p-1.5 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/40 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </form>
        @endif
    </div>

    <!-- Post Content -->
    @if ($post->content)
        <div class="text-sm text-slate-800 dark:text-slate-200 leading-relaxed mb-3 break-words whitespace-pre-line">
            {{ $post->content }}
        </div>
    @endif

    <!-- Legacy single-image attachment -->
    @if ($post->image_url)
        <div class="mb-3 rounded-xl overflow-hidden border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 flex items-center justify-center">
            <a href="{{ $post->image_url }}" target="_blank" class="block w-full">
                <img src="{{ $post->image_url }}" loading="lazy" class="w-full max-h-[500px] object-cover hover:scale-[1.01] transition duration-200" alt="Attachment">
            </a>
        </div>
    @endif

    @if ($post->media->isNotEmpty())
        <div class="mb-3 grid {{ $post->media->count() > 1 ? 'grid-cols-2' : 'grid-cols-1' }} gap-2">
            @foreach ($post->media as $attachment)
                <div class="rounded-xl overflow-hidden border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800">
                    @if ($attachment->type === 'video')
                        <video controls preload="metadata" playsinline class="w-full max-h-[420px] object-contain" src="{{ $attachment->url }}" aria-label="Video postingan {{ $loop->iteration }}"></video>
                    @else
                        <a href="{{ $attachment->url }}" target="_blank" rel="noopener noreferrer">
                            <img src="{{ $attachment->url }}" loading="lazy" class="w-full max-h-[420px] object-cover" alt="Gambar postingan {{ $loop->iteration }}">
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <!-- Actions / Interaction Footer -->
    <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
        <div class="flex items-center gap-6">
            <!-- Like Button -->
            <button type="button" data-code="{{ $post->code }}"
                class="btn-like flex items-center gap-1.5 hover:text-rose-600 dark:hover:text-rose-400 transition {{ $post->isLikedBy(Auth::user()) ? 'text-rose-500' : '' }}">
                <svg class="w-4 h-4 like-icon {{ $post->isLikedBy(Auth::user()) ? 'text-rose-500 fill-current' : 'text-slate-400 dark:text-slate-500' }} transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <span class="font-medium like-count">{{ $post->likes_count ?? $post->likes()->count() }}</span>
            </button>

            <!-- Comment Button -->
            <a href="{{ route('posts.show', $post->code) }}" class="flex items-center gap-1.5 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span class="font-medium">{{ $post->comments_count ?? $post->comments()->count() }}</span>
            </a>

            <!-- Share Button -->
            <button type="button" onclick="copyShareLink('{{ route('posts.show', $post->code) }}')"
                title="Salin Tautan" class="flex items-center gap-1.5 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                </svg>
            </button>
        </div>

        <span class="text-[11px] font-mono text-slate-400 dark:text-slate-500">#{{ substr($post->code, 0, 8) }}</span>
    </div>
</article>
