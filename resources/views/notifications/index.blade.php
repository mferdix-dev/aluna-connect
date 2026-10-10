@extends('layouts.app')

@section('content')
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-5 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4">
            <div>
                <h1 class="text-base sm:text-lg font-extrabold text-slate-900 dark:text-white">Notifikasi Aktivitas</h1>
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Pemberitahuan like, komentar, dan pesan masuk terbaru</p>
            </div>

            @if ($notifications->total() > 0)
                <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="px-3 py-1.5 rounded-xl text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/50 transition">
                        Tandai Semua Dibaca
                    </button>
                </form>
            @endif
        </div>

        <div class="divide-y divide-slate-100 dark:divide-slate-800">
            @forelse ($notifications as $notif)
                <div class="py-3.5 flex items-start gap-3.5 group">
                    <!-- Actor Avatar & Type Badge -->
                    <div class="relative shrink-0">
                        <img src="{{ $notif->actor->avatar_url }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-700" alt="{{ $notif->actor->name }}">
                        <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full flex items-center justify-center text-white text-[10px] shadow-sm
                            {{ $notif->type === 'like' ? 'bg-rose-500' : ($notif->type === 'comment' ? 'bg-indigo-500' : 'bg-violet-600') }}">
                            @if ($notif->type === 'like')
                                <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            @elseif ($notif->type === 'comment')
                                <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                            @else
                                <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                            @endif
                        </span>
                    </div>

                    <!-- Text details -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $notif->title }}</span>
                            <span class="text-[11px] text-slate-400 dark:text-slate-500">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5 leading-snug">{{ $notif->message }}</p>
                        @if ($notif->link)
                            <a href="{{ $notif->link }}" class="inline-block text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline mt-1">
                                Lihat Selengkapnya &rarr;
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="py-12 text-center text-xs text-slate-400 dark:text-slate-500">
                    <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    Belum ada notifikasi baru untuk Anda.
                </div>
            @endforelse
        </div>

        <div class="pt-2">
            {{ $notifications->links() }}
        </div>
    </div>
@endsection
