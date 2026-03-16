@extends('layouts.user')

@section('title', 'Mes Réservations')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Filters -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Mes Réservations</h1>
            <p class="text-sm text-gray-500 mt-1">Consultez l'historique et le statut de vos demandes de réservation.</p>
        </div>
        
        <!-- Filter Tabs -->
        <div class="flex items-center gap-1 p-1 bg-white border border-gray-200 rounded-xl shadow-sm self-start md:self-auto">
            <a href="{{ route('user.reservations.index') }}" 
               class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors {{ !request('statut') ? 'bg-[#00c950] text-white' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                Toutes
            </a>
            <a href="{{ route('user.reservations.index', ['statut' => 'en_attente']) }}" 
               class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors {{ request('statut') === 'en_attente' ? 'bg-[#00c950] text-white' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                En Attente
            </a>
            <a href="{{ route('user.reservations.index', ['statut' => 'approuve']) }}" 
               class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors {{ request('statut') === 'approuve' ? 'bg-[#00c950] text-white' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                Approuvées
            </a>
            <a href="{{ route('user.reservations.index', ['statut' => 'rejete']) }}" 
               class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors {{ request('statut') === 'rejete' ? 'bg-[#00c950] text-white' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                Rejetées
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="min-w-full">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/50">
                    <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest w-1/4">Salle</th>
                    <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">Événement</th>
                    <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">Horaire</th>
                    <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest w-32">Statut</th>
                    <th class="px-6 py-4 text-right text-[11px] font-bold text-gray-400 uppercase tracking-widest w-24">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($reservations as $res)
                <tr class="hover:bg-gray-50/80 transition-colors">
                    
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gray-100 border border-gray-200/60 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[14px] font-bold text-gray-900">{{ $res->salle->nom }}</p>
                                <p class="text-[12px] text-gray-500 flex items-center gap-1 mt-0.5">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                    {{ $res->salle->localisation ?: 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-5">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-gray-100 text-gray-700 uppercase tracking-wider shadow-sm border border-gray-200/60">
                            {{ $res->titre }}
                        </span>
                    </td>

                    <td class="px-6 py-5">
                        <p class="text-[14px] font-semibold text-gray-900">{{ $res->date_debut->format('d/m/Y') }}</p>
                        <p class="text-[12px] text-gray-500 font-medium flex items-center gap-1 mt-0.5">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $res->date_debut->format('H:i') }} - {{ $res->date_fin->format('H:i') }}
                        </p>
                    </td>

                    <td class="px-6 py-5">
                        @if($res->statut === 'approuve')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-green-50 text-green-700 border border-green-200/60 shadow-sm">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                Approuvée
                            </span>
                        @elseif($res->statut === 'en_attente')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-orange-50 text-orange-600 border border-orange-200/60 shadow-sm">
                                <span class="w-1.5 h-1.5 bg-orange-500 rounded-full animate-pulse"></span>
                                En attente
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-red-50 text-red-600 border border-red-200/60 shadow-sm">
                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                Rejetée
                            </span>
                        @endif
                    </td>

                    <td class="px-6 py-5 text-right">
                        @if($res->statut === 'en_attente')
                            <form method="POST" action="{{ route('user.reservations.destroy', $res) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette demande de réservation ?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1 text-[13px] font-semibold text-red-500 hover:text-red-700 hover:underline transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Annuler
                                </button>
                            </form>
                        @else
                            <span class="text-[12px] text-gray-400 italic">Non annulable</span>
                        @endif
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center bg-gray-50/30">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-[14px] font-bold text-gray-900">Aucune réservation trouvée</h3>
                        <p class="text-[13px] text-gray-500 mt-1 mb-4">Vous n'avez pas de réservations correspondant à vos critères.</p>
                        <a href="{{ route('user.salles.index') }}" class="inline-flex items-center px-4 py-2 bg-[#00c950] text-white text-sm font-semibold rounded-lg hover:bg-[#00b046] transition-colors shadow-sm">
                            Réserver une salle
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
