@extends('layouts.admin')

@section('title', 'Historique des réservations')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="flex flex-col xl:flex-row xl:items-start justify-between gap-6 mb-8">
        <div class="flex-1">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Historique des réservations</h1>
            <p class="text-[15px] text-gray-500 mt-2 max-w-2xl leading-relaxed">
                Consultez toutes les réservations, leur statut et les détails associés dans une liste complète.
            </p>
        </div>
        
        <form method="GET" action="{{ route('admin.planning.list') }}" class="flex flex-wrap items-center gap-2 shrink-0 border border-gray-200 p-2 rounded-xl bg-gray-50/50">
            <!-- Salle Filter -->
            <select name="salle_id" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 h-10 transition-colors">
                <option value="">Toutes les salles</option>
                @foreach($salles as $salle)
                    <option value="{{ $salle->id }}" {{ request('salle_id') == $salle->id ? 'selected' : '' }}>
                        {{ $salle->nom }}
                    </option>
                @endforeach
            </select>

            <!-- Date Filter -->
            <input type="date" name="date" value="{{ request('date') }}"
                   class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 h-10 transition-colors">

            <!-- Responsable Filter -->
            <select name="responsable_id" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 h-10 transition-colors">
                <option value="">Tous les responsables</option>
                @foreach($responsables as $respo)
                    <option value="{{ $respo->id }}" {{ request('responsable_id') == $respo->id ? 'selected' : '' }}>
                        {{ $respo->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold rounded-lg shadow-sm transition-colors border border-gray-200 h-10">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filtrer
            </button>
            
            @if(request()->filled('salle_id') || request()->filled('date') || request()->filled('responsable_id'))
                <a href="{{ route('admin.planning.list') }}" class="inline-flex items-center justify-center px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 text-sm font-bold rounded-lg shadow-sm transition-colors h-10">
                    Effacer
                </a>
            @endif

            <div class="hidden xl:block w-px h-8 bg-gray-200 mx-1"></div>

            <a href="{{ route('admin.planning.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-bold rounded-lg shadow-sm transition-all h-10 focus:ring-2 focus:ring-green-500 focus:outline-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Planning
            </a>
        </form>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100/80 overflow-hidden">
        @if($plannings->isEmpty())
            <div class="text-center py-20 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-4 opacity-40 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p class="text-[15px] font-medium text-gray-500">Aucune réservation trouvée dans l'historique.</p>
            </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100 bg-white">
                        <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">Demande / Détails</th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">Demandeur</th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">Salle Assignée</th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest w-32">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50/80">
                    @foreach($plannings as $p)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <!-- Demande / Time -->
                        <td class="px-6 py-5">
                            <div class="flex flex-col">
                                <p class="text-[14px] font-semibold text-gray-900 capitalize">{{ \Carbon\Carbon::parse($p->date_debut)->translatedFormat('d M Y') }}</p>
                                <p class="text-[12px] text-gray-500 font-medium flex items-center gap-1 mt-0.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ \Carbon\Carbon::parse($p->date_debut)->format('H:i') }} - {{ \Carbon\Carbon::parse($p->date_fin)->format('H:i') }}
                                </p>
                                <span class="inline-flex mt-2 items-center px-2 py-0.5 rounded text-[10.5px] font-bold bg-gray-100 text-gray-600 uppercase w-max tracking-wider border border-gray-200/60">
                                    {{ $p->titre }}
                                </span>
                            </div>
                        </td>

                        <!-- Utilisateur -->
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0 shadow-sm"
                                     style="background: {{ ['#22c55e','#3b82f6','#f97316','#8b5cf6','#ec4899'][crc32($p->user->name) % 5] }}">
                                    {{ strtoupper(substr($p->user->name, 0, 2)) }}
                                </div>
                                <div class="flex flex-col">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[14px] font-bold text-gray-900">{{ $p->user->name }}</span>
                                        @if($p->user->role === 'admin')
                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-700 uppercase tracking-widest border border-purple-200/60 shadow-sm">Admin</span>
                                        @elseif($p->user->role === 'responsible')
                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700 uppercase tracking-widest border border-blue-200/60 shadow-sm">Responsable</span>
                                        @else
                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600 uppercase tracking-widest border border-gray-200/60 shadow-sm">User</span>
                                        @endif
                                    </div>
                                    <span class="text-[12px] text-gray-500">{{ $p->user->email }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Salle -->
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gray-100 border border-gray-200/60 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[14px] font-bold text-gray-900">{{ $p->salle->nom }}</p>
                                    <p class="text-[12px] text-gray-500 flex items-center gap-1 mt-0.5">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                        {{ $p->salle->localisation ?: 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <!-- Statut -->
                        <td class="px-6 py-5">
                            @if($p->statut === 'approuve')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-green-50 text-green-700 border border-green-200/60 shadow-sm">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                    Approuvée
                                </span>
                            @elseif($p->statut === 'en_attente')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-orange-50 text-orange-600 border border-orange-200/60 shadow-sm">
                                    <span class="w-1.5 h-1.5 bg-orange-500 rounded-full animate-pulse"></span>
                                    En attente
                                </span>
                            @elseif($p->statut === 'rejete')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-red-50 text-red-600 border border-red-200/60 shadow-sm">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                    Rejetée
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200/60 shadow-sm">
                                    <span class="w-1.5 h-1.5 bg-gray-500 rounded-full"></span>
                                    Annulée
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($plannings->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $plannings->links() }}
        </div>
        @endif
        @endif
    </div>
</div>
@endsection