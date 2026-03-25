<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Portail Responsable') - SallePro</title>
    <link rel="icon" href="{{ asset('Salle-Pro.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gray-50 flex flex-col">

    <div class="w-full flex-1 flex flex-col">

        <!-- Navbar -->
        <nav class="border-b border-gray-100 px-6 bg-white">
            <div class="flex items-center justify-between h-16">

                <!-- Logo -->
                <a href="{{ route('responsable.dashboard') }}" class="flex items-center gap-2.5 shrink-0">
                    <img src="{{ asset('Salle-Pro.png') }}" alt="SallePro Logo" class="h-8 w-auto drop-shadow-sm">
                    <span class="text-base font-bold text-gray-900">SallePro</span>
                </a>

                <!-- Nav Links -->
                <div class="hidden md:flex items-end h-16 gap-1">
                    <a href="{{ route('responsable.dashboard') }}"
                       class="flex items-center px-4 h-full text-sm font-medium border-b-2 transition-colors
                              {{ request()->routeIs('responsable.dashboard') ? 'text-green-600 border-green-500' : 'text-gray-500 border-transparent hover:text-gray-800 hover:border-gray-300' }}">
                        Tableau de bord
                    </a>
                    <a href="{{ route('responsable.salles.index') }}"
                       class="flex items-center px-4 h-full text-sm font-medium border-b-2 transition-colors
                              {{ request()->routeIs('responsable.salles.*') ? 'text-green-600 border-green-500' : 'text-gray-500 border-transparent hover:text-gray-800 hover:border-gray-300' }}">
                        Salles
                    </a>
                </div>

                <!-- Right: Profile -->
                <div class="relative" id="profile-menu-wrapper">
                    <button onclick="toggleProfileMenu()"
                        class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg hover:bg-gray-50 transition-colors focus:outline-none">
                        <span class="text-sm font-medium text-gray-700 hidden sm:block">{{ Auth::user()->name }}</span>
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white text-sm font-bold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <svg class="w-3.5 h-3.5 text-gray-400 transition-transform" id="profile-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div id="profile-dropdown" class="hidden absolute right-0 mt-1 w-52 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                        <div class="px-4 py-2.5 border-b border-gray-100">
                            <p class="text-xs text-gray-400">Connecté en tant que</p>
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ Auth::user()->email }}</p>
                            <span class="text-xs text-green-600 font-medium capitalize">{{ Auth::user()->role }}</span>
                        </div>
                        <a href="{{ route('profile.show') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Mon Profil
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Se déconnecter
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Mobile hamburger -->
                <button onclick="toggleMobileMenu()" class="md:hidden p-2 rounded-md text-gray-500 hover:bg-gray-100 ml-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-3 space-y-1">
                <a href="{{ route('responsable.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('responsable.dashboard') ? 'text-green-600 bg-green-50' : 'text-gray-600 hover:bg-gray-50' }}">Tableau de bord</a>
                <a href="{{ route('responsable.salles.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('responsable.salles.*') ? 'text-green-600 bg-green-50' : 'text-gray-600 hover:bg-gray-50' }}">Salles</a>
            </div>
        </nav>

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="mx-6 mt-4">
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-2 text-sm">
                <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        </div>
        @endif
        @if(session('error'))
        <div class="mx-6 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-center gap-2 text-sm">
                <svg class="w-4 h-4 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                {{ session('error') }}
            </div>
        </div>
        @endif

        <!-- Page Content -->
        <main class="flex-1 w-full max-w-[1400px] mx-auto p-6 md:p-8">
            @yield('content')
        </main>

    </div>

    <script>
        function toggleProfileMenu() {
            document.getElementById('profile-dropdown').classList.toggle('hidden');
            document.getElementById('profile-chevron').classList.toggle('rotate-180');
        }
        document.addEventListener('click', function(e) {
            const w = document.getElementById('profile-menu-wrapper');
            if (w && !w.contains(e.target)) {
                document.getElementById('profile-dropdown')?.classList.add('hidden');
                document.getElementById('profile-chevron')?.classList.remove('rotate-180');
            }
        });
        function toggleMobileMenu() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        }
    </script>
</body>
</html>
