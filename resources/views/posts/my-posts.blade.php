@extends('layouts.app')

@section('content')
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-sm flex items-center justify-between">
        <div>
            <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Postingan Saya</h2>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Kelola dan lihat seluruh postingan yang telah Anda publikasikan.</p>
        </div>
        <div class="px-3 py-1 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-100 dark:border-indigo-800 text-xs font-bold text-indigo-600 dark:text-indigo-400">
            Total: {{ $posts->total() }} Post
        </div>
    </div>

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
                <h3 class="text-sm font-bold text-slate-800 dark:text-white">Anda belum membuat postingan</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 mb-4">Mulai bagikan momen atau cerita Anda kepada teman-teman.</p>
                <a href="{{ route('feed') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition">
                    Buat Post Sekarang
                </a>
            </div>
        @endforelse
    </div>

    <div class="pt-2">
        {{ $posts->links() }}
    </div>
@endsection
