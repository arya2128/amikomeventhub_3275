<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AmikomEventHub - Temukan Event Seru!</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900">

    <nav
        class="glass sticky top-8 z-40 mx-4 mt-4 px-6 py-4 rounded-2xl border border-white/20 shadow-lg flex justify-between items-center">
        
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <div
                class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-xl">
                AH</div>
            <span class="text-xl font-bold tracking-tight">AmikomEventHub</span>
        </a>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center gap-8 font-medium">
            <a href="{{ route('katalog') }}" class="hover:text-indigo-600 transition">Jelajahi</a>
            <a href="{{ route('profil') }}" class="hover:text-indigo-600 transition">Profil</a>
            <a href="{{ route('bantuan') }}" class="hover:text-indigo-600 transition">Bantuan</a>
            <a href="{{ route('kontak') }}" class="hover:text-indigo-600 transition">Kontak</a>
            @if(Auth::check())
                <a href="{{ route('my-ticket') }}" class="hover:text-indigo-600 transition">Tiket Saya</a>
                @if(in_array(Auth::user()->role, ['admin', 'organizer']))
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition">Panel Admin</a>
                @endif
                <div class="flex items-center gap-4 pl-4 border-l border-slate-200">
                    <span class="text-slate-600 text-sm">Hi, <strong class="text-slate-800">{{ Auth::user()->name }}</strong></span>
                    <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white text-sm font-bold transition">Logout</button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">Masuk</a>
            @endif
        </div>

        <!-- Mobile Action Buttons -->
        <div class="flex items-center gap-3 md:hidden">
            @if(Auth::check())
                <a href="{{ route('my-ticket') }}" class="px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-xl text-xs font-bold">Tiket Saya</a>
                @if(in_array(Auth::user()->role, ['admin', 'organizer']))
                    <a href="{{ route('admin.dashboard') }}" class="px-3 py-1.5 bg-indigo-600 text-white rounded-xl text-xs font-bold">Admin</a>
                @endif
                <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-600 rounded-xl text-xs font-bold">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-bold shadow-md shadow-indigo-100 hover:bg-indigo-700 transition">
                    Masuk
                </a>
            @endif
            <button id="mobile-menu-btn" class="p-2 text-slate-600 hover:text-indigo-600 focus:outline-none" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
        
    </nav>

    <!-- Mobile Dropdown Menu -->
    <div id="mobile-menu" class="hidden md:hidden mx-4 mt-2 p-6 glass rounded-2xl border border-white/20 shadow-lg space-y-4 font-medium sticky top-28 z-30">
        <a href="{{ route('katalog') }}" class="block py-2 text-slate-700 hover:text-indigo-600 font-bold">🔍 Jelajahi Event</a>
        <a href="{{ route('profil') }}" class="block py-2 text-slate-700 hover:text-indigo-600 font-bold">ℹ️ Profil Kampus</a>
        <a href="{{ route('bantuan') }}" class="block py-2 text-slate-700 hover:text-indigo-600 font-bold">❓ Cara Pesan & Bantuan</a>
        <a href="{{ route('kontak') }}" class="block py-2 text-slate-700 hover:text-indigo-600 font-bold">📞 Hubungi Kami</a>
    </div>

    @yield('content')

    <footer class="bg-indigo-900 text-indigo-100 py-20 px-6 mt-20">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
            
            <div class="space-y-4 col-span-2">
                <a href="{{ route('home') }}" class="flex items-center gap-2 inline-flex">
                    <div
                        class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-900 font-bold text-xl">
                        AH</div>
                    <span class="text-2xl font-bold text-white">AmikomEventHub</span>
                </a>
                <p class="max-w-xs text-indigo-300">Platform reservasi tiket event online terbaik untuk mahasiswa dan
                    penyelenggara profesional.</p>
            </div>

            <div>
                <h4 class="text-white font-bold mb-6">Navigasi</h4>
                <ul class="space-y-4">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition">Home</a></li>
                    <li><a href="{{ route('katalog') }}" class="hover:text-white transition">Semua Event</a></li>
                    <li><a href="{{ route('bantuan') }}" class="hover:text-white transition">Cara Bayar</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold mb-6">Hubungi Kami</h4>
                <ul class="space-y-4">
                    <li><a href="{{ route('kontak') }}" class="hover:text-white transition">Hubungi CS</a></li>
                    <li>support@eventtiket.com</li>
                    <li>+62 812 3456 7890</li>
                </ul>
            </div>

        </div>
        <div class="max-w-7xl mx-auto pt-12 mt-12 border-t border-indigo-800 text-center text-indigo-400 text-sm">
            &copy; 2024 AmikomEventHub. Built with Laravel & Tailwind CSS.
        </div>
    </footer>

</body>

</html>