@extends('layouts.guest')

@section('content')
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Masuk ke Akun Anda</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Gunakan username atau email yang telah terdaftar.</p>
    </div>

    <form action="{{ route('login') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="login" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Username / Email</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <input type="text" name="login" id="login" value="{{ old('login') }}" required autofocus
                    placeholder="ferdiadit12 atau email@example.com"
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
            </div>
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400">Password</label>
                <a href="{{ route('password.request') }}" class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:underline">Lupa password?</a>
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <input type="password" name="password" id="password" required
                    placeholder="••••••••"
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
            </div>
        </div>

        <div class="flex items-center justify-between pt-1">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                <span class="text-xs font-medium text-slate-600 dark:text-slate-400">Ingat saya</span>
            </label>
        </div>

        <button type="submit"
            class="w-full py-3 px-4 rounded-xl font-semibold text-sm text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 shadow-md shadow-indigo-500/25 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-150">
            Masuk
        </button>
    </form>

    <!-- Quick Demo Accounts -->
    <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-800">
        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-2.5 text-center">Akun Demo (Klik untuk isi otomatis):</p>
        <div class="grid grid-cols-2 gap-2 text-xs">
            <button type="button" onclick="fillDemo('ferdiadit12', 'password123')"
                class="p-2 bg-slate-100 dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-indigo-950/60 hover:text-indigo-700 dark:hover:text-indigo-300 rounded-lg text-left transition font-medium text-slate-700 dark:text-slate-300">
                👤 ferdiadit12 <span class="block text-[10px] text-slate-400">User</span>
            </button>
            <button type="button" onclick="fillDemo('useradmin', 'admin123')"
                class="p-2 bg-slate-100 dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-indigo-950/60 hover:text-indigo-700 dark:hover:text-indigo-300 rounded-lg text-left transition font-medium text-slate-700 dark:text-slate-300">
                🛡️ useradmin <span class="block text-[10px] text-slate-400">Admin</span>
            </button>
        </div>
    </div>

    <div class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Daftar sekarang</a>
    </div>

    <script>
        function fillDemo(user, pass) {
            document.getElementById('login').value = user;
            document.getElementById('password').value = pass;
        }
    </script>
@endsection
