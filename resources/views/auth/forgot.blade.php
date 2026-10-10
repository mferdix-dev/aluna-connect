@extends('layouts.guest')

@section('content')
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Pemulihan Password</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kirim informasi akun Anda kepada Admin untuk proses reset password.</p>
    </div>

    <form action="{{ route('password.inquiry') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Nama Lengkap</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                placeholder="Nama sesuai akun Anda"
                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
        </div>

        <div>
            <label for="username" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Username Akun</label>
            <input type="text" name="username" id="username" value="{{ old('username') }}" required
                placeholder="Username yang lupa password"
                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
        </div>

        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Email Aktif</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                placeholder="Email untuk menerima instruksi reset"
                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
        </div>

        <div>
            <label for="note" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Catatan Tambahan (Opsional)</label>
            <textarea name="note" id="note" rows="3"
                placeholder="Jelaskan masalah akun Anda..."
                class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">{{ old('note') }}</textarea>
        </div>

        <button type="submit"
            class="w-full py-3 px-4 rounded-xl font-semibold text-sm text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 shadow-md shadow-indigo-500/25 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-150">
            Kirim Permintaan Pemulihan
        </button>
    </form>

    <div class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
        Sudah ingat password?
        <a href="{{ route('login') }}" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Kembali ke login</a>
    </div>
@endsection
