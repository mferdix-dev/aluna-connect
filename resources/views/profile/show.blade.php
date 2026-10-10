@extends('layouts.app')

@section('content')
    <!-- Profile Header Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm">
        <!-- Banner Header -->
        <div class="h-32 sm:h-40 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 relative">
            <div class="absolute inset-0 bg-black/10"></div>
        </div>

        <!-- Profile Details -->
        <div class="px-5 sm:px-6 pb-6 pt-0 relative">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 -mt-14 sm:-mt-16 mb-4">
                <div class="relative">
                    <img src="{{ $user->avatar_url }}"
                        class="w-24 h-24 sm:w-28 sm:h-28 rounded-full object-cover border-4 border-white dark:border-slate-900 shadow-md bg-white dark:bg-slate-800"
                        alt="{{ $user->name }}">
                </div>

                @if (Auth::id() === $user->id)
                    <div>
                        <a href="{{ route('profile.edit') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200/80 dark:border-slate-700 shadow-sm transition">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            <span>Edit Profil</span>
                        </a>
                    </div>
                @else
                    <div>
                        <a href="{{ route('messages.show', $user->username) }}"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm shadow-indigo-500/30 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                            <span>Kirim Pesan</span>
                        </a>
                    </div>
                @endif
            </div>

            <!-- Name and badges -->
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h1 class="text-xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $user->name }}</h1>
                    @if ($user->isAdmin())
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                            Administrator
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-400 dark:text-slate-500 font-medium mt-0.5">@<span>{{ $user->username }}</span></p>
            </div>

            <!-- Bio / Info Section -->
            <div class="mt-4 space-y-2 text-xs text-slate-600 dark:text-slate-300">
                @if ($user->hobby)
                    <div class="flex items-center gap-2">
                        <span class="text-slate-400 dark:text-slate-500">🎨 Hobi/Minat:</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200">{{ $user->hobby }}</span>
                    </div>
                @endif

                <div class="flex flex-wrap gap-4 pt-1 text-slate-500 dark:text-slate-400">
                    @if ($user->city)
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ $user->city }}</span>
                        </div>
                    @endif

                    @if ($user->born_date)
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Lahir: {{ \Carbon\Carbon::parse($user->born_date)->format('d M Y') }}</span>
                        </div>
                    @endif

                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Bergabung {{ $user->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-3 gap-2 mt-5 pt-4 border-t border-slate-100 dark:border-slate-800 text-center">
                <div class="p-2 rounded-xl bg-slate-50 dark:bg-slate-800/60">
                    <span class="block text-base font-extrabold text-slate-900 dark:text-white">{{ $user->posts_count }}</span>
                    <span class="block text-[11px] font-medium text-slate-400 dark:text-slate-500 uppercase tracking-wider">Postingan</span>
                </div>
                <div class="p-2 rounded-xl bg-slate-50 dark:bg-slate-800/60">
                    <span class="block text-base font-extrabold text-slate-900 dark:text-white">{{ $user->comments_count }}</span>
                    <span class="block text-[11px] font-medium text-slate-400 dark:text-slate-500 uppercase tracking-wider">Komentar</span>
                </div>
                <div class="p-2 rounded-xl bg-slate-50 dark:bg-slate-800/60">
                    <span class="block text-base font-extrabold text-slate-900 dark:text-white">{{ $user->likes_count }}</span>
                    <span class="block text-[11px] font-medium text-slate-400 dark:text-slate-500 uppercase tracking-wider">Suka</span>
                </div>
            </div>
        </div>
    </div>

    <!-- User's Posts Feed -->
    <div class="space-y-4">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-1">Postingan oleh {{ $user->name }}</h3>
        @forelse ($posts as $post)
            @include('components.post-card', ['post' => $post])
        @empty
            <div class="text-center py-10 px-4 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl text-xs text-slate-400 dark:text-slate-500">
                Pengguna ini belum mempublikasikan postingan apapun.
            </div>
        @endforelse
    </div>

    <div class="pt-2">
        {{ $posts->links() }}
    </div>
@endsection
