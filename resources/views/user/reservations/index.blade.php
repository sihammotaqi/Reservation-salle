@extends('layouts.user')

@section('title', 'Mes Réservations')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-6 mb-8">
        <div class="flex-1">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Historique des Réservations</h1>
            <p class="text-[15px] text-gray-500 mt-2 max-w-2xl leading-relaxed">
                Consultez et gérez vos réservations de salles passées et à venir.
            </p>
        </div>

        <form method="GET" action="{{ route('user.reservations.index') }}" class="flex flex-wrap items-center gap-2 shrink-0">
            <!-- Salle Filter -->
            <select name="salle_id" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 h-10 transition-colors">
                <option value="">Toutes les salles</option>
                @foreach($salles as $salle)
                    <option value="{{ $salle->id }}" {{ request('salle_id') == $salle->id ? 'selected' : '' }}>
                        {{ $salle->nom }}
                    </option>
                @endforeach
            </select>

            <!-- Date Filter -->
            <input type="date" name="date" value="{{ request('date') }}"
                   class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 h-10 transition-colors">

            <!-- Statut Filter -->
            <select name="statut" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 h-10 transition-colors">
                <option value="">Tous les statuts</option>
                <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="approuve" {{ request('statut') === 'approuve' ? 'selected' : '' }}>Approuvées</option>
                <option value="rejete" {{ request('statut') === 'rejete' ? 'selected' : '' }}>Refusées</option>
                <option value="annule" {{ request('statut') === 'annule' ? 'selected' : '' }}>Annulées</option>
            </select>

            <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold rounded-lg shadow-sm transition-colors border border-gray-200 h-10">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filtrer
            </button>
            
            @if(request()->filled('salle_id') || request()->filled('date') || request()->filled('statut'))
                <a href="{{ route('user.reservations.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 text-sm font-bold rounded-lg shadow-sm transition-colors h-10">
                    Effacer
                </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 text-xs text-gray-400 uppercase tracking-wide">
                    <th class="text-left px-5 py-3">Salle</th>
                    <th class="text-left px-5 py-3">Début</th>
                    <th class="text-left px-5 py-3">Fin</th>
                    <th class="text-left px-5 py-3">Type</th>
                    <th class="text-left px-5 py-3">Statut</th>
                    <th class="text-left px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($reservations as $reservation)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-4">
                            <div class="font-medium text-gray-800">{{ $reservation->salle->nom ?? '—' }}</div>
                            @if($reservation->salle?->localisation)
                                <div class="text-xs text-gray-400">{{ $reservation->salle->localisation }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-gray-600">
                            {{ $reservation->date_debut->format('d M, H:i') }}
                        </td>
                        <td class="px-5 py-4 text-gray-600">
                            {{ $reservation->date_fin->format('d M, H:i') }}
                        </td>
                        <td class="px-5 py-4 text-gray-600">
                            {{ $reservation->type_evenement ?? $reservation->titre }}
                        </td>
                        <td class="px-5 py-4">
                            @php
                                $badges = [
                                    'en_attente' => 'bg-orange-100 text-orange-600',
                                    'approuve'   => 'bg-green-100 text-green-700',
                                    'rejete'     => 'bg-red-100 text-red-600',
                                    'annule'     => 'bg-gray-100 text-gray-500',
                                ];
                                $labels = [
                                    'en_attente' => 'En attente',
                                    'approuve'   => 'Approuvé',
                                    'rejete'     => 'Refusé',
                                    'annule'     => 'Annulé',
                                ];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $badges[$reservation->statut] ?? 'bg-gray-100 text-gray-500' }}">
                                <span class="w-1.5 h-1.5 rounded-full
                                    {{ $reservation->statut === 'en_attente' ? 'bg-orange-400' : '' }}
                                    {{ $reservation->statut === 'approuve' ? 'bg-green-500' : '' }}
                                    {{ $reservation->statut === 'rejete' ? 'bg-red-500' : '' }}
                                    {{ $reservation->statut === 'annule' ? 'bg-gray-400' : '' }}
                                "></span>
                                {{ $labels[$reservation->statut] ?? $reservation->statut }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            @if($reservation->statut === 'en_attente')
                                <button onclick="openCancelModal({{ $reservation->id }}, '{{ addslashes($reservation->salle->nom ?? '') }}', '{{ $reservation->date_debut->format('H:i') }} - {{ $reservation->date_fin->format('H:i') }}, {{ $reservation->date_debut->format('d M') }}', '{{ addslashes($reservation->type_evenement ?? $reservation->titre) }}')"
                                    class="text-red-400 hover:text-red-600 transition" title="Annuler">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center text-gray-400">
                            Vous n'avez effectué aucune réservation pour le moment.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($reservations->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
         {{ $reservations->links() }}
         </div>
       @endif

    </div>
</div>

{{-- ===== MODAL ANNULATION ===== --}}
<div id="cancel-modal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden px-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 relative">

        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-base font-bold text-gray-800">Annuler la réservation</h2>
                <p id="cancel-subtitle" class="text-sm text-gray-500 mt-0.5"></p>
            </div>
        </div>

        <div class="bg-gray-50 rounded-xl p-4 mb-5 space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-400">Salle</span>
                <span id="cancel-salle" class="font-medium text-gray-700"></span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Horaire</span>
                <span id="cancel-horaire" class="font-medium text-gray-700"></span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Type</span>
                <span id="cancel-type" class="font-medium text-gray-700"></span>
            </div>
        </div>

        <form id="cancel-form" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeCancelModal()"
                    class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                    Garder la réservation
                </button>
                <button type="submit"
                    class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-red-500 hover:bg-red-600 rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Confirmer l'annulation
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openCancelModal(id, salle, horaire, type) {
    document.getElementById('cancel-modal').classList.remove('hidden');
    document.getElementById('cancel-subtitle').textContent = 'Cette action est irréversible.';
    document.getElementById('cancel-salle').textContent = salle;
    document.getElementById('cancel-horaire').textContent = horaire;
    document.getElementById('cancel-type').textContent = type;
    document.getElementById('cancel-form').action = '/user/reservations/' + id;
}

function closeCancelModal() {
    document.getElementById('cancel-modal').classList.add('hidden');
}

// Auto-hide flash after 4s
setTimeout(() => {
    ['flash-success', 'flash-error'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.remove();
    });
}, 4000);
</script>
@endsection
