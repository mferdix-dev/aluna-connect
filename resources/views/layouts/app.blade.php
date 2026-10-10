<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Aluna Connect' }} - Modern Platform</title>

    <!-- Instant Dark Mode Init Script (Prevents FOUC & Defaults to Local Computer Time) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const currentHour = new Date().getHours();
            // Default: if between 18:00 (6 PM) and 06:00 (6 AM) -> dark mode
            const isNightTime = currentHour >= 18 || currentHour < 6;

            if (savedTheme === 'dark' || (!savedTheme && isNightTime) || (savedTheme === 'auto' && isNightTime)) {
                document.documentElement.classList.add('dark');
            } else if (savedTheme === 'light' || (!savedTheme && !isNightTime) || (savedTheme === 'auto' && !isNightTime)) {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        html { scroll-behavior: smooth; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .animate-slide-in {
            animation: slideInRight 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body class="min-h-full bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 antialiased selection:bg-indigo-500 selection:text-white pb-20 md:pb-6 transition-colors duration-200">

    <!-- Floating Toast Notification Container (Top-Right) -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-2.5 max-w-sm w-full pointer-events-none px-3 sm:px-0"></div>

    <!-- Top Mobile Navigation Bar -->
    <header class="md:hidden sticky top-0 z-40 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800 px-4 py-3 flex items-center justify-between">
        <a href="{{ route('feed') }}" class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-500 flex items-center justify-center text-white shadow-md shadow-indigo-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <span class="font-extrabold text-lg tracking-tight bg-gradient-to-r from-slate-900 to-slate-700 dark:from-white dark:to-slate-300 bg-clip-text text-transparent">Aluna Connect</span>
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ route('settings.index') }}" aria-label="Pengaturan aplikasi" class="p-2 text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15a3 3 0 100-6 3 3 0 000 6zM19 12a7 7 0 01-.2 1.7l1.4 1.1-2 3.4-1.7-.7a7 7 0 01-3 1.7l-.3 1.8h-4l-.3-1.8a7 7 0 01-3-1.7l-1.7.7-2-3.4 1.4-1.1a7 7 0 010-3.4L2.2 9.2l2-3.4 1.7.7a7 7 0 013-1.7L9.2 3h4l.3 1.8a7 7 0 013 1.7l1.7-.7 2 3.4-1.4 1.1A7 7 0 0119 12z"/></svg>
            </a>

            <!-- Notifications Icon (Mobile) -->
            <a href="{{ route('notifications.index') }}" class="relative p-2 text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span id="mobile-notification-badge" class="{{ Auth::user()->unreadNotificationsCount() > 0 ? '' : 'hidden' }} absolute top-1 right-1 w-4 h-4 rounded-full bg-rose-500 text-white text-[9px] font-bold flex items-center justify-center">
                    {{ Auth::user()->unreadNotificationsCount() }}
                </span>
            </a>

            <!-- User Avatar & Logout (Mobile) -->
            <a href="{{ route('profile.show', Auth::user()->username) }}" class="flex items-center p-0.5 rounded-full hover:ring-2 hover:ring-indigo-500 transition">
                <img src="{{ Auth::user()->avatar_url }}" class="w-7 h-7 rounded-full object-cover border border-slate-200 dark:border-slate-700" alt="Avatar">
            </a>
        </div>
    </header>

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-12 md:gap-6 lg:gap-8">

            <!-- Left Desktop Sidebar (Cols 3) -->
            <aside class="hidden md:block md:col-span-3 lg:col-span-3">
                <div class="sticky top-6 space-y-5">
                    <!-- Brand Logo -->
                    <div class="px-3 pt-2">
                        <a href="{{ route('feed') }}" class="flex items-center gap-3 group">
                            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-500 flex items-center justify-center text-white shadow-lg shadow-indigo-500/25 group-hover:scale-105 transition duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </div>
                            <div>
                                <span class="block text-xl font-extrabold tracking-tight bg-gradient-to-r from-slate-900 to-slate-700 dark:from-white dark:to-slate-300 bg-clip-text text-transparent">Aluna Connect</span>
                                <span class="block text-[11px] font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider">Laravel Edition</span>
                            </div>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <nav class="space-y-1 bg-white/70 dark:bg-slate-900/70 backdrop-blur-md border border-slate-200/70 dark:border-slate-800 rounded-2xl p-2.5 shadow-sm">
                        <!-- Beranda -->
                        <a href="{{ route('feed') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('feed') ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Beranda</span>
                        </a>

                        <!-- Pesan / Chat -->
                        <a href="{{ route('messages.index') }}"
                            class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('messages.*') ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                                <span>Pesan Chat</span>
                            </div>
                            <span id="nav-message-badge" class="{{ Auth::user()->unreadMessagesCount() > 0 ? '' : 'hidden' }} px-2 py-0.5 rounded-full text-[11px] font-bold bg-indigo-600 text-white">
                                {{ Auth::user()->unreadMessagesCount() }}
                            </span>
                        </a>

                        <!-- Notifikasi -->
                        <a href="{{ route('notifications.index') }}"
                            class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('notifications.*') ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <span>Notifikasi</span>
                            </div>
                            <span id="nav-notification-badge" class="{{ Auth::user()->unreadNotificationsCount() > 0 ? '' : 'hidden' }} px-2 py-0.5 rounded-full text-[11px] font-bold bg-rose-500 text-white">
                                {{ Auth::user()->unreadNotificationsCount() }}
                            </span>
                        </a>

                        <!-- Postingan Saya -->
                        <a href="{{ route('posts.mine') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('posts.mine') ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <span>Postingan Saya</span>
                        </a>

                        <!-- Profil Saya -->
                        <a href="{{ route('profile.show', Auth::user()->username) }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->is('u/' . Auth::user()->username) ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Profil Saya</span>
                        </a>

                        <!-- Edit Profil -->
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('profile.edit') ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Edit Profil</span>
                        </a>
                        <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('settings.*') ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            <span aria-hidden="true">&#9881;</span><span>Pengaturan Aplikasi</span>
                        </a>
                    </nav>

                    <!-- User Account Card & Logout -->
                    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-200/80 dark:border-slate-800 rounded-2xl p-3.5 shadow-sm flex items-center justify-between">
                        <a href="{{ route('profile.show', Auth::user()->username) }}" class="flex items-center gap-3 min-w-0 group">
                            <img src="{{ Auth::user()->avatar_url }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-700 shrink-0 group-hover:scale-105 transition" alt="Avatar">
                            <div class="min-w-0">
                                <span class="block text-sm font-bold text-slate-800 dark:text-slate-100 truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">{{ Auth::user()->name }}</span>
                                <span class="block text-xs text-slate-400 dark:text-slate-500 truncate">@<span>{{ Auth::user()->username }}</span></span>
                            </div>
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" title="Logout"
                                class="p-2 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 rounded-xl hover:bg-rose-50 dark:hover:bg-rose-950/40 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Center Column - Main Feed / Content -->
            <main class="col-span-1 md:col-span-9 lg:col-span-6 py-4 md:py-6 space-y-5">
                <!-- Flash messages -->
                @if (session('success'))
                    <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 flex items-center justify-between gap-3 text-sm shadow-sm transition">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm shadow-sm">
                        <div class="flex items-center gap-2 font-semibold mb-1">
                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Perhatian:
                        </div>
                        <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700 dark:text-rose-400">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Right Column - Search & Community -->
            <aside class="hidden lg:block lg:col-span-3 py-6">
                <div class="sticky top-6 space-y-5">
                    <!-- Search Widget -->
                    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3">Cari Postingan</h3>
                        <form action="{{ route('feed') }}" method="GET" class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kata kunci / user..."
                                class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        </form>
                    </div>

                    <!-- Direct Message Quick Contacts -->
                    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Mulai Obrolan / Chat</h3>
                            <a href="{{ route('messages.index') }}" class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400 hover:underline">Semua</a>
                        </div>
                        <div class="space-y-2.5">
                            @php
                                $chatPartners = \App\Models\User::where('id', '!=', Auth::id())->take(5)->get();
                            @endphp

                            @forelse ($chatPartners as $cUser)
                                <div class="flex items-center justify-between gap-2 text-xs">
                                    <a href="{{ route('profile.show', $cUser->username) }}" class="flex items-center gap-2.5 min-w-0 group">
                                        <img src="{{ $cUser->avatar_url }}" class="w-8 h-8 rounded-full object-cover border border-slate-200 dark:border-slate-700 shrink-0 group-hover:scale-105 transition" alt="{{ $cUser->name }}">
                                        <div class="min-w-0">
                                            <span class="block font-bold text-slate-800 dark:text-slate-200 truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">{{ $cUser->name }}</span>
                                            <span class="block text-[11px] text-slate-400 dark:text-slate-500 truncate">@<span>{{ $cUser->username }}</span></span>
                                        </div>
                                    </a>
                                    <a href="{{ route('messages.show', $cUser->username) }}"
                                        title="Kirim Pesan"
                                        class="p-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 transition shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                        </svg>
                                    </a>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400">Belum ada pengguna lain.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Footer Info -->
                    <div class="px-2 text-xs text-slate-400 dark:text-slate-500 space-y-1">
                        <p>&copy; {{ date('Y') }} Aluna Connect. Modern Laravel Framework.</p>
                    </div>
                </div>
            </aside>

        </div>
    </div>

    <!-- Chat stays on top of the current page (desktop corner / mobile fullscreen). -->
    <button type="button" onclick="openChat()" aria-label="Buka pesan" class="hidden md:flex fixed bottom-5 right-5 z-40 items-center gap-2 px-4 py-3 rounded-full bg-indigo-600 text-white shadow-lg hover:bg-indigo-700 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
            <span class="text-sm font-semibold">Pesan</span>
    </button>
    <div id="chat-widget" class="hidden fixed bottom-0 right-0 z-50 w-full h-dvh md:bottom-5 md:right-5 md:w-[400px] md:h-[min(580px,calc(100dvh-2.5rem))] md:rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl overflow-hidden flex-col" role="dialog" aria-label="Pesan" aria-hidden="true">
        <div class="flex items-center justify-between px-4 py-2 border-b border-slate-200 dark:border-slate-800 text-sm font-bold">Pesan
            <button type="button" onclick="closeChat()" aria-label="Tutup pesan" class="text-xl text-slate-500 hover:text-indigo-600">&times;</button>
        </div>
        <iframe id="chat-frame" title="Percakapan" class="w-full flex-1 min-h-0 border-0"></iframe>
    </div>
    <!-- Mobile Bottom Navigation Bar -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-lg border-t border-slate-200/80 dark:border-slate-800 px-3 py-2 flex items-center justify-around shadow-lg">
        <a href="{{ route('feed') }}" class="flex flex-col items-center gap-1 p-2 rounded-xl {{ request()->routeIs('feed') ? 'text-indigo-600 dark:text-indigo-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="text-[10px]">Beranda</span>
        </a>

        <button type="button" onclick="openChat()" class="relative flex flex-col items-center gap-1 p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
            </svg>
            <span class="text-[10px]">Pesan</span>
            <span id="mobile-bottom-message-badge" class="{{ Auth::user()->unreadMessagesCount() > 0 ? '' : 'hidden' }} absolute top-1 right-3 w-4 h-4 rounded-full bg-indigo-600 text-white text-[9px] font-bold flex items-center justify-center">
                {{ Auth::user()->unreadMessagesCount() }}
            </span>
        </button>

        <a href="{{ route('notifications.index') }}" class="relative flex flex-col items-center gap-1 p-2 rounded-xl {{ request()->routeIs('notifications.*') ? 'text-indigo-600 dark:text-indigo-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span class="text-[10px]">Notifikasi</span>
            <span id="mobile-bottom-notif-badge" class="{{ Auth::user()->unreadNotificationsCount() > 0 ? '' : 'hidden' }} absolute top-1 right-3 w-4 h-4 rounded-full bg-rose-500 text-white text-[9px] font-bold flex items-center justify-center">
                {{ Auth::user()->unreadNotificationsCount() }}
            </span>
        </a>

        <a href="{{ route('profile.show', Auth::user()->username) }}" class="flex flex-col items-center gap-1 p-2 rounded-xl {{ request()->is('u/' . Auth::user()->username) ? 'text-indigo-600 dark:text-indigo-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="text-[10px]">Profil</span>
        </a>
    </nav>

    <!-- Global JavaScript for Theme Management, Async Likes, and Real-time Notification Alert Polling -->
    <script>
        // ------------------ Dark Mode Management ------------------
        function applyTheme(theme) {
            const currentHour = new Date().getHours();
            const isNight = currentHour >= 18 || currentHour < 6;
            const dark = theme === 'dark' || (theme === 'auto' && isNight);
            document.documentElement.classList.toggle('dark', dark);
            const frame = document.getElementById('chat-frame');
            if (frame.contentDocument) frame.contentDocument.documentElement.classList.toggle('dark', dark);
        }

        let chatTrigger = null;

        function openChat(url = @json(route('messages.index'))) {
            const widget = document.getElementById('chat-widget');
            const frame = document.getElementById('chat-frame');
            const destination = new URL(url, window.location.origin);
            if (destination.origin !== window.location.origin || !/^\/messages(?:\/[^/]+)?$/.test(destination.pathname)) return;
            chatTrigger = document.activeElement;
            destination.searchParams.set('panel', '1');
            if (!frame.src || new URL(frame.src).pathname !== destination.pathname) frame.src = destination.href;
            widget.classList.remove('hidden');
            widget.classList.add('flex');
            widget.setAttribute('aria-hidden', 'false');
            document.querySelector('[aria-label="Tutup pesan"]').focus();
        }

        function closeChat() {
            document.getElementById('chat-widget').classList.add('hidden');
            document.getElementById('chat-widget').classList.remove('flex');
            document.getElementById('chat-widget').setAttribute('aria-hidden', 'true');
            if (chatTrigger?.isConnected) chatTrigger.focus();
        }

        // Initialize UI labels on DOM load
        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme') || 'auto';
            applyTheme(savedTheme);

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            document.addEventListener('click', event => {
                const anchor = event.target.closest('a[href]');
                if (!anchor || event.defaultPrevented || event.button !== 0 || anchor.target === '_blank' || anchor.hasAttribute('download') || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
                const url = new URL(anchor.href);
                if (url.origin === window.location.origin && /^\/messages(?:\/[^/]+)?$/.test(url.pathname)) {
                    event.preventDefault();
                    openChat(url.href);
                }
            });

            document.getElementById('chat-frame').addEventListener('load', () => applyTheme(localStorage.getItem('theme') || 'auto'));
            setInterval(() => { if ((localStorage.getItem('theme') || 'auto') === 'auto') applyTheme('auto'); }, 60000);

            // Handle Like Buttons via AJAX
            document.querySelectorAll('.btn-like').forEach(button => {
                button.addEventListener('click', async (e) => {
                    e.preventDefault();
                    const code = button.getAttribute('data-code');
                    const countEl = button.querySelector('.like-count');
                    const iconEl = button.querySelector('.like-icon');

                    try {
                        const response = await fetch(`/posts/${code}/like`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            countEl.textContent = data.count;
                            if (data.liked) {
                                iconEl.classList.remove('text-slate-400');
                                iconEl.classList.add('text-rose-500', 'fill-current');
                                button.classList.add('text-rose-500');
                            } else {
                                iconEl.classList.remove('text-rose-500', 'fill-current');
                                iconEl.classList.add('text-slate-400');
                                button.classList.remove('text-rose-500');
                            }
                        }
                    } catch (err) {
                        console.error('Like toggle error:', err);
                    }
                });
            });

            // ------------------ Real-time Notification & Message Polling ------------------
            let lastToastId = null;

            async function pollNotifications() {
                try {
                    const res = await fetch('/api/notifications/unread-count', {
                        headers: { 'Accept': 'application/json' }
                    });
                    if (res.ok) {
                        const data = await res.json();

                        // Update Notification badges
                        updateBadge('nav-notification-badge', data.notifications_count);
                        updateBadge('mobile-notification-badge', data.notifications_count);
                        updateBadge('mobile-bottom-notif-badge', data.notifications_count);

                        // Update Message badges
                        updateBadge('nav-message-badge', data.messages_count);
                        updateBadge('mobile-bottom-message-badge', data.messages_count);

                        // Check for new toast alert
                        if (data.latest && data.latest.id !== lastToastId) {
                            // If this is the first poll, save id without popping toast to avoid annoying on refresh
                            if (lastToastId !== null) {
                                showNotificationToast(data.latest);
                            }
                            lastToastId = data.latest.id;
                        }
                    }
                } catch (e) {
                    // silently fail on network disconnect
                }
            }

            function updateBadge(id, count) {
                const el = document.getElementById(id);
                if (el) {
                    if (count > 0) {
                        el.textContent = count;
                        el.classList.remove('hidden');
                    } else {
                        el.classList.add('hidden');
                    }
                }
            }

            function showNotificationToast(item) {
                const container = document.getElementById('toast-container');
                if (!container) return;

                const toast = document.createElement('div');
                toast.className = 'pointer-events-auto bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xl shadow-slate-900/10 rounded-2xl p-3.5 flex items-start gap-3 animate-slide-in transition transform duration-200';
                const avatar = document.createElement('img');
                avatar.src = item.actor_avatar;
                avatar.alt = 'Avatar';
                avatar.className = 'w-9 h-9 rounded-full object-cover shrink-0 border border-slate-200 dark:border-slate-700';
                const body = document.createElement('div');
                body.className = 'flex-1 min-w-0';
                const title = document.createElement('strong');
                title.className = 'block text-xs text-slate-900 dark:text-white';
                title.textContent = item.title;
                const message = document.createElement('p');
                message.className = 'text-xs text-slate-600 dark:text-slate-300 leading-snug line-clamp-2';
                message.textContent = item.message;
                body.append(title, message);
                if (item.link) {
                    const link = document.createElement('a');
                    link.href = item.link;
                    link.className = 'inline-block text-[11px] font-bold text-indigo-600 dark:text-indigo-400 hover:underline mt-1.5';
                    link.textContent = 'Buka →';
                    body.append(link);
                }
                const close = document.createElement('button');
                close.type = 'button';
                close.className = 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition';
                close.setAttribute('aria-label', 'Tutup notifikasi');
                close.textContent = '×';
                close.addEventListener('click', () => toast.remove());
                toast.append(avatar, body, close);

                container.appendChild(toast);

                // Auto remove after 6 seconds
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.classList.add('opacity-0', 'translate-x-full');
                        setTimeout(() => toast.remove(), 250);
                    }
                }, 6000);
            }

            // Start polling every 4 seconds
            pollNotifications();
            setInterval(pollNotifications, 4000);
        });

        function copyShareLink(url) {
            navigator.clipboard.writeText(url).then(() => {
                alert('Tautan postingan berhasil disalin ke clipboard!');
            });
        }
    </script>
</body>
</html>
