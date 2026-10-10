@extends('layouts.app')

@section('content')
    <!-- Create Post Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-sm">
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex gap-3">
                <img src="{{ Auth::user()->avatar_url }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-700 shrink-0" alt="Avatar">
                <div class="flex-1 min-w-0">
                    <textarea name="content" rows="3"
                        placeholder="Apa yang sedang Anda pikirkan, {{ Auth::user()->name }}?"
                        class="w-full bg-slate-50/70 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition resize-none">{{ old('content') }}</textarea>

                    <div id="media-preview" class="hidden mt-3 grid grid-cols-2 gap-2" aria-live="polite"></div>

                    <!-- Footer Controls -->
                    <div class="flex items-center justify-between pt-3 mt-1 border-t border-slate-100 dark:border-slate-800">
                        <label class="cursor-pointer inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/50 transition">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Foto / Video (maks. 10)</span>
                            <input type="file" name="media[]" id="post-media-input" accept="image/jpeg,image/png,image/webp,image/gif,video/mp4,video/webm,video/quicktime" multiple class="hidden" onchange="previewPostMedia(event)">
                        </label>

                        <span class="hidden sm:inline text-[11px] text-slate-400 dark:text-slate-500">Maks. 20 MB per file</span>

                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm shadow-indigo-500/30 transition">
                            <span>Kirim Post</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Active Search Filter Indicator -->
    @if ($search)
        <div class="p-3 bg-indigo-50/70 dark:bg-indigo-950/50 border border-indigo-100 dark:border-indigo-900 rounded-2xl flex items-center justify-between text-xs text-indigo-900 dark:text-indigo-200">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span>Hasil pencarian untuk: <strong>"{{ $search }}"</strong> ({{ $posts->total() }} postingan)</span>
            </div>
            <a href="{{ route('feed') }}" class="font-bold text-indigo-600 dark:text-indigo-400 hover:underline">Hapus Filter</a>
        </div>
    @endif

    <!-- Posts Feed List -->
    <div class="space-y-4">
        @forelse ($posts as $post)
            @include('components.post-card', ['post' => $post])
        @empty
            <div class="text-center py-12 px-4 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl">
                <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-white">Belum ada postingan</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Jadilah yang pertama untuk membagikan sesuatu di feed!</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pt-2">
        {{ $posts->links() }}
    </div>

    <script>
        let previewUrls = [];
        function previewPostMedia(event) {
            const preview = document.getElementById('media-preview');
            previewUrls.forEach(url => URL.revokeObjectURL(url));
            previewUrls = [];
            preview.replaceChildren();
            const files = Array.from(event.target.files);
            if (files.length > 10) {
                alert('Maksimal 10 lampiran per postingan.');
                event.target.value = '';
                preview.classList.add('hidden');
                return;
            }
            files.forEach(file => {
                const url = URL.createObjectURL(file);
                previewUrls.push(url);
                const element = document.createElement(file.type.startsWith('video/') ? 'video' : 'img');
                element.src = url;
                element.className = 'w-full h-32 object-cover rounded-xl border border-slate-200 dark:border-slate-700';
                if (element.tagName === 'VIDEO') element.controls = true;
                else element.alt = file.name;
                preview.appendChild(element);
            });
            preview.classList.toggle('hidden', files.length === 0);
        }
    </script>
@endsection
