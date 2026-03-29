<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tableau de bord (Admin) - SallePro</title>
    <link rel="icon" href="{{ asset('Salle-Pro.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-gray-50 flex flex-col">

    <!-- Main Container -->
    <div class="w-full flex-1 flex flex-col">

        <!-- Navbar -->
        <nav class="border-b border-gray-100 px-6">
            <div class="flex items-center justify-between h-16">

                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 shrink-0">
                    <img src="{{ asset('Salle-Pro.png') }}" alt="SallePro Logo" class="h-8 w-auto drop-shadow-sm">
                    <span class="text-base font-bold text-gray-900">SallePro</span>
                </a>

                @if(Auth::user()->role === 'admin')
                <!-- Centered Nav Links -->
                <div class="hidden md:flex items-end h-16 gap-1">
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center px-4 h-full text-sm font-medium border-b-2 transition-colors text-green-600 border-green-500">
                        Tableau de bord
                    </a>
                    <a href="{{ route('admin.salles.index') }}"
                       class="flex items-center px-4 h-full text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-800 hover:border-gray-300 transition-colors">
                        Salles
                    </a>
                    <a href="{{ route('admin.utilisateurs.index') }}"
                       class="flex items-center px-4 h-full text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-800 hover:border-gray-300 transition-colors">
                        Utilisateurs
                    </a>
                    <a href="{{ route('admin.planning.index') }}"
                       class="flex items-center px-4 h-full text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-800 hover:border-gray-300 transition-colors">
                        Salles planning
                    </a>
                    <a href="{{ route('admin.equipements.index') }}"
                       class="flex items-center px-4 h-full text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-800 hover:border-gray-300 transition-colors">
                        Équipements
                    </a>
                </div>
                @endif

                <!-- Right: Profile Dropdown -->
                <div class="flex items-center gap-3">
                    @if(Auth::user()->role === 'admin')
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

                        <!-- Dropdown Panel -->
                        <div id="profile-dropdown" class="hidden absolute right-0 mt-1 w-52 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                            <div class="px-4 py-2.5 border-b border-gray-100">
                                <p class="text-xs text-gray-400">Connecté en tant que</p>
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.show') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Mon Profil
                            </a>
                            <a href="#" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Paramètres
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
                    @else
                    <!-- Regular user: simple logout -->
                    <span class="text-sm text-gray-600">Bonjour, {{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors border border-red-200">Déconnexion</button>
                    </form>
                    @endif
                </div>
            </div>
        </nav>

        <!-- Page Body -->
        <div class="flex-1 w-full max-w-[1400px] mx-auto p-6 md:p-8 space-y-6">

            <!-- Page Header Row -->
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tableau de Bord Global</h1>
                    <p class="text-sm text-gray-500 mt-1">Aperçu général du système de réservation et de son utilisation.</p>
                </div>

            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Stat 1: Total Salles -->
                <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Total Salles</p>
                        <p class="text-4xl font-bold text-gray-900 mt-1" id="count-salles">—</p>
                        <p class="text-xs text-green-600 font-medium mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                            Gestion des salles
                        </p>
                    </div>
                    <div class="w-11 h-11 bg-green-50 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>

                <!-- Stat 2: Réservations Totales -->
                <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Réservations Totales</p>
                        <p class="text-4xl font-bold text-gray-900 mt-1" id="count-reservations">—</p>
                        <p class="text-xs text-green-600 font-medium mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                            Toutes les réservations
                        </p>
                    </div>
                    <div class="w-11 h-11 bg-green-50 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>

                <!-- Stat 3: En Attente -->
                <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">En Attente</p>
                        <p class="text-4xl font-bold text-gray-900 mt-1" id="count-attente">—</p>
                        <p class="text-xs text-orange-500 font-medium mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Demandes en attente
                        </p>
                    </div>
                    <div class="w-11 h-11 bg-orange-50 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Requests Table -->
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <!-- Table Header -->
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <h2 class="text-base font-bold text-gray-900">Demandes en attente</h2>
                    <div class="flex items-center gap-2">
                        <button class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            Filtrer
                        </button>
                        <button class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            Trier
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Demandeur</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Salle</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Horaire</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Type</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Status</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-gray-400 uppercase tracking-wide">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="pending-tbody" class="divide-y divide-gray-50">
                        @php
                            $pending = \App\Models\Planning::with(['salle', 'user'])
                            ->where('statut', 'en_attente')
                            ->latest()
                            ->paginate(5);
                        @endphp

                        @forelse($pending as $p)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <!-- Demandeur -->
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0"
                                         style="background: {{ ['#22c55e','#3b82f6','#f97316','#8b5cf6','#ec4899'][crc32($p->user->name) % 5] }}">
                                        {{ strtoupper(substr($p->user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $p->user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $p->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <!-- Salle -->
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-1.5 text-sm text-gray-700">
                                    <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    {{ $p->salle->nom }}
                                </div>
                            </td>
                            <!-- Horaire -->
                            <td class="px-5 py-4">
                                <p class="text-sm text-gray-700">{{ $p->date_debut->format('H:i') }} - {{ $p->date_fin->format('H:i') }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ $p->date_debut->isToday() ? "Aujourd'hui" : ($p->date_debut->isTomorrow() ? 'Demain' : $p->date_debut->format('d/m/Y')) }}
                                </p>
                            </td>
                            <!-- Type/Titre -->
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-green-100 text-green-700">
                                    {{ strtoupper($p->titre) }}
                                </span>
                            </td>
                            <!-- Status -->
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-yellow-600">
                                    <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full"></span>
                                    En attente
                                </span>
                            </td>
                            <!-- Actions -->
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <form method="POST" action="{{ route('admin.planning.update', $p) }}" class="flex m-0 p-0">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="statut" value="approuve">
                                        <button type="submit" class="text-green-500 hover:text-green-700 transition-colors flex items-center" title="Approuver">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </button>
                                    </form>
                                    <button type="button" onclick="openRejectModal('{{ route('admin.planning.update', $p) }}')" class="text-red-400 hover:text-red-600 transition-colors flex items-center" title="Rejeter">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-gray-400 text-sm">
                                Aucune demande en attente.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Table Footer -->
                <div class="flex items-center justify-between px-5 py-4 border-t border-gray-100">
    
    <p class="text-sm text-gray-500">
        Affichage de {{ $pending->firstItem() }} à {{ $pending->lastItem() }} sur {{ $pending->total() }} demande(s) en attente
    </p>

    {{ $pending->links() }}

</div>
                        
                    </div>
                </div>
            </div>

        </div>
    </div><!-- /Main Container -->

    <!-- Reject Confirmation Modal -->
    <div id="reject-modal" class="fixed inset-0 z-[100] hidden bg-gray-900/40 flex items-center justify-center backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-[20px] shadow-2xl w-full max-w-md mx-4 overflow-hidden transform transition-transform" onclick="event.stopPropagation()">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Confirmer le refus</h3>
                </div>
                <button type="button" onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Body & Form -->
            <form id="reject-form" method="POST" action="">
                @csrf @method('PUT')
                <input type="hidden" name="statut" value="rejete">
                
                <div class="p-6">
                    <p class="text-[15px] text-gray-600 mb-6 leading-relaxed">
                        Êtes-vous sûr de vouloir refuser cette demande de réservation ? Cette action informera l'utilisateur de votre décision.
                    </p>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Motif du refus <span class="font-normal text-gray-400">(optionnel)</span></label>
                        <textarea name="motif_refus" rows="3" class="w-full px-4 py-3 text-sm bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all resize-none placeholder-gray-400" placeholder="Veuillez indiquer le motif du refus pour aider l'utilisateur..."></textarea>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeRejectModal()" class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition-all shadow-sm">
                        Annuler
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-[#ef4444] hover:bg-[#dc2626] rounded-xl transition-all shadow-sm shadow-red-500/20">
                        Confirmer le refus
                    </button>
                </div>
            </form>
        </div>
    </div>

    @php
        $countSalles       = \App\Models\Salle::count();
        $countReservations = \App\Models\Planning::count();
        $countAttente      = \App\Models\Planning::where('statut', 'en_attente')->count();
    @endphp
    <script>
        document.getElementById('count-salles').textContent        = '{{ $countSalles }}';
        document.getElementById('count-reservations').textContent  = '{{ $countReservations }}';
        document.getElementById('count-attente').textContent       = '{{ $countAttente }}';

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

        function openRejectModal(formActionUrl) {
            const modal = document.getElementById('reject-modal');
            const form = document.getElementById('reject-form');
            form.action = formActionUrl;
            modal.classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('reject-modal').classList.add('hidden');
        }

        document.getElementById('reject-modal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });
    </script>
</body>
</html>
