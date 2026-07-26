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

        <div class="hidden md:flex items-center gap-8 font-medium">
            <a href="{{ route('katalog') }}" class="hover:text-indigo-600 transition">Jelajahi</a>
            <a href="{{ route('admin.categories.index') }}" class="hover:text-indigo-600 transition">Kategori</a>
            <a href="{{ route('bantuan') }}" class="hover:text-indigo-600 transition">Bantuan</a>
            
            <!-- TOMBOL LOGIN / PROFIL USER -->
            @guest
                <a href="{{ route('login') }}" class="px-5 py-2.5 bg-indigo-600 text-white font-bold text-sm rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                    Masuk
                </a>
            @endguest

            @auth
                <div class="flex items-center gap-4">
                    <span class="text-sm font-bold text-slate-700">
                        Halo, {{ explode(' ', Auth::user()->name)[0] }}!
                    </span>
                    
                    <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="text-sm font-bold text-red-500 hover:text-red-700 transition">
                            Keluar
                        </button>
                    </form>
                </div>
            @endauth
        </div>
        
    </nav>

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