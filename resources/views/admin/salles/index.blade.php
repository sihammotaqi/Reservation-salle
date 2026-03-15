@extends('layouts.admin')

@section('title', 'Gestion des Salles')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Gestion des Salles</h1>
            <p class="text-[15px] text-gray-500 mt-2 max-w-2xl leading-relaxed">
                Gérez les espaces de réunion, leur capacité et leurs équipements disponibles pour la réservation.
            </p>
        </div>
        <div class="flex items-center gap-4 shrink-0 mt-2 md:mt-0">
            <!-- Search Input -->
            <div class="relative hidden sm:block">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" placeholder="Rechercher des salles"
                       class="pl-10 pr-4 py-2.5 w-64 text-sm font-medium bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm placeholder-gray-400">
            </div>

            <!-- Add Button -->
            <a href="{{ route('admin.salles.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#00c950] hover:bg-[#00b046] text-white text-sm font-bold rounded-lg shadow-sm transition-all shadow-[#00c950]/20">
                <svg class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Ajouter une salle
            </a>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100/80 overflow-hidden">
        @if($salles->isEmpty())
            <div class="text-center py-20 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-4 opacity-40 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <p class="text-[15px] font-medium text-gray-500">Aucune salle trouvée. Commencez par en ajouter une.</p>
            </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest w-1/4">Nom de la salle</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest w-24">Capacité</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest w-1/4">Localisation</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Équipements</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest w-32">Statut</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50/80">
                    @foreach($salles as $salle)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <!-- Nom -->
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0 {{ $salle->disponible ? 'bg-green-50' : 'bg-gray-50' }}">
                                    <svg class="w-5 h-5 {{ $salle->disponible ? 'text-green-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-gray-900 text-[15px] truncate">{{ $salle->nom }}</p>
                                    <p class="text-xs font-medium text-gray-400 mt-0.5 truncate uppercase tracking-wide">ID: SAL-{{ str_pad($salle->id, 3, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Capacité -->
                        <td class="px-6 py-5">
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-gray-50 text-gray-600 border border-gray-100">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
                                <span class="text-sm font-semibold">{{ $salle->capacite }}</span>
                            </div>
                        </td>

                        <!-- Localisation -->
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-1.5 text-[14px] text-gray-500 font-medium">
                                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                <span class="truncate">{{ $salle->localisation ?: 'Non renseignée' }}</span>
                            </div>
                        </td>

                        <!-- Équipements badges -->
                        <td class="px-6 py-5">
                            <div class="flex flex-wrap items-center gap-1.5">
                                @forelse($salle->equipements->take(3) as $eq)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-gray-100 text-gray-600 border border-gray-200/60 shadow-sm">
                                        {{ $eq->nom }} <span class="ml-1 text-gray-400">(x{{ $eq->pivot->quantite }})</span>
                                    </span>
                                @empty
                                    <span class="text-xs text-gray-400 italic">Aucun équipement</span>
                                @endforelse
                                @if($salle->equipements->count() > 3)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[11px] font-bold bg-gray-50 text-gray-400 border border-gray-200/60">
                                        +{{ $salle->equipements->count() - 3 }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- Statut (Toggle UI) -->
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-2.5">
                                <button type="button" class="relative inline-flex h-5 w-9 shrink-0 cursor-not-allowed items-center justify-center rounded-full focus:outline-none" role="switch">
                                    <span class="sr-only">Statut</span>
                                    <!-- Track -->
                                    <span class="pointer-events-none absolute h-full w-full rounded-full transition-colors duration-200 ease-in-out {{ $salle->disponible ? 'bg-green-100' : 'bg-gray-200' }}"></span>
                                    <!-- Thumb -->
                                    <span class="pointer-events-none absolute left-0 inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition-transform duration-200 ease-in-out border {{ $salle->disponible ? 'translate-x-4 border-green-200 bg-green-500' : 'translate-x-0 border-gray-300' }}"></span>
                                </button>
                                <span class="text-[13px] font-bold {{ $salle->disponible ? 'text-green-500' : 'text-gray-400' }}">
                                    {{ $salle->disponible ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </td>

                        <!-- Actions (Edit / Delete Icons) -->
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-3 transition-opacity">
                                <a href="{{ route('admin.salles.edit', $salle) }}" class="text-gray-400 hover:text-[#00c950] transition-colors" title="Modifier">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.salles.destroy', $salle) }}" onsubmit="return confirm('Confirmer la suppression de cette salle ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Supprimer">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Footer / Pagination Area -->
        @if($salles->count() > 8)
        <div class="px-6 py-4 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-[13px] text-gray-500 font-medium">
                Affichage de <span class="font-bold text-gray-900">1 à {{ min($salles->count(), 8) }}</span> sur <span class="font-bold text-gray-900">{{ $salles->count() }}</span> résultats
            </p>
            <div class="flex items-center gap-1.5">
                <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-400 hover:bg-gray-50 disabled:opacity-50" disabled>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button class="w-8 h-8 rounded-lg bg-[#00c950] text-white font-bold text-sm flex items-center justify-center shadow-sm">1</button>
                <button class="w-8 h-8 rounded-lg border border-gray-200 text-gray-600 font-medium text-sm flex items-center justify-center hover:bg-gray-50 transition-colors">2</button>
                <button class="w-8 h-8 rounded-lg border border-gray-200 text-gray-600 font-medium text-sm flex items-center justify-center hover:bg-gray-50 transition-colors">3</button>
                <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-400 hover:bg-gray-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
        @endif
        @endif
    </div>
</div>
@endsection
