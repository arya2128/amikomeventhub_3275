<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tugas Praktikum')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans antialiased">

    <nav class="bg-white shadow-sm mb-8 border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-4 py-4 flex flex-wrap justify-center gap-3">
            <a href="/" class="px-5 py-2 bg-slate-200 text-slate-700 font-medium rounded-lg hover:bg-slate-300 hover:text-slate-900 transition duration-200">Home</a>
            <a href="/profil" class="px-5 py-2 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 shadow-sm transition duration-200">Profil</a>
            <a href="/katalog" class="px-5 py-2 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 shadow-sm transition duration-200">Katalog</a>
            <a href="/bantuan" class="px-5 py-2 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 shadow-sm transition duration-200">Bantuan</a>
            <a href="/kontak" class="px-5 py-2 bg-slate-200 text-slate-700 font-medium rounded-lg hover:bg-slate-300 hover:text-slate-900 transition duration-200">Kontak</a>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 pb-10">
        @yield('content')
    </main>

</body>
</html>