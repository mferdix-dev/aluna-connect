<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pesan - Aluna Connect</title>
    <script>
        const mode = localStorage.getItem('theme') || 'auto';
        if (mode === 'dark' || (mode === 'auto' && (new Date().getHours() >= 18 || new Date().getHours() < 6))) {
            document.documentElement.classList.add('dark');
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' };</script>
</head>
<body class="h-full bg-white dark:bg-slate-900 text-slate-900 dark:text-white">
    @yield('content')
</body>
</html>
