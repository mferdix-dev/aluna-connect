@extends('layouts.app')

@section('content')
    <section class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 sm:p-7 shadow-sm">
        <h1 class="text-xl font-bold text-slate-900 dark:text-white">Pengaturan Aplikasi</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Sesuaikan tampilan Aluna Connect di perangkat ini.</p>

        <fieldset class="mt-7 space-y-3" id="theme-settings">
            <legend class="text-sm font-bold text-slate-800 dark:text-slate-200 mb-3">Mode tampilan</legend>
            <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 dark:border-slate-700 cursor-pointer hover:border-indigo-500">
                <input type="radio" name="theme" value="auto" class="accent-indigo-600">
                <span class="text-sm"><strong class="block">Otomatis</strong><span class="text-slate-500 dark:text-slate-400">Terang pukul 06.00–17.59, gelap pukul 18.00–05.59 sesuai jam perangkat.</span></span>
            </label>
            <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 dark:border-slate-700 cursor-pointer hover:border-indigo-500">
                <input type="radio" name="theme" value="light" class="accent-indigo-600">
                <span class="text-sm"><strong class="block">Terang</strong><span class="text-slate-500 dark:text-slate-400">Selalu gunakan tampilan terang.</span></span>
            </label>
            <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 dark:border-slate-700 cursor-pointer hover:border-indigo-500">
                <input type="radio" name="theme" value="dark" class="accent-indigo-600">
                <span class="text-sm"><strong class="block">Gelap</strong><span class="text-slate-500 dark:text-slate-400">Selalu gunakan tampilan gelap.</span></span>
            </label>
        </fieldset>
        <p id="theme-saved" role="status" class="mt-4 text-sm text-emerald-600 dark:text-emerald-400" hidden>Pengaturan tampilan tersimpan di perangkat ini.</p>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const current = localStorage.getItem('theme') || 'auto';
            const input = document.querySelector(`input[name="theme"][value="${current}"]`);
            if (input) input.checked = true;
            document.querySelectorAll('input[name="theme"]').forEach(radio => {
                radio.addEventListener('change', () => {
                    localStorage.setItem('theme', radio.value);
                    window.applyTheme(radio.value);
                    document.getElementById('theme-saved').hidden = false;
                });
            });
        });
    </script>
@endsection
