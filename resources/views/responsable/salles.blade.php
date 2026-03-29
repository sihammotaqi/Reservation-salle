@extends('layouts.responsable')

@section('title', 'Planning des Salles')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">
        <div class="flex-1">
            <div class="flex items-center gap-3">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Planning Global des Salles Gérées</h1>
                <span class="px-3 py-1 bg-green-50 text-green-700 text-sm font-bold rounded-md border border-green-200/60 shadow-sm mt-1">
                    {{ ($selectedDate ?? $today)->format('d/m/Y') }}
                </span>
            </div>
            <p class="text-[15px] text-gray-500 mt-2 max-w-2xl leading-relaxed">
                Visualisez et gérez l'occupation des salles sur une frise chronologique pour la date sélectionnée.
            </p>
        </div>
        
        <div class="flex items-center gap-3 overflow-x-auto pb-2 lg:pb-0 shrink-0">
            <!-- Date Filter Form -->
            <form method="GET" action="{{ route('responsable.salles.index') }}" class="flex items-center gap-2">
                <input type="date" name="date" value="{{ request('date', ($selectedDate ?? $today)->toDateString()) }}"
                       class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 h-10 transition-colors">
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold rounded-lg shadow-sm transition-colors border border-gray-200 h-10">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Filtrer
                </button>
            </form>

            <div class="hidden sm:block w-px h-8 bg-gray-200 mx-1"></div>

            <!-- View Toggle & Actions -->
            <div class="flex items-center gap-3">
                <a href="{{ route('responsable.salles.list') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-bold rounded-lg shadow-sm transition-colors h-10 focus:ring-2 focus:ring-green-500 focus:outline-none">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    Vue Liste
                </a>

                <button onclick="openReservationModal(null)"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-[#00c950] hover:bg-[#00b046] text-white text-sm font-bold rounded-lg shadow-sm transition-colors h-10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                    Nouvelle réservation
                </button>
            </div>
        </div>
    </div>

    {{-- Légende --}}
    <div class="flex items-center gap-4 mb-5">
        <div class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded-full bg-green-400"></span>
            <span class="text-sm text-gray-600">Approuvé</span>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded-full bg-orange-400"></span>
            <span class="text-sm text-gray-600">En attente</span>
        </div>
    </div>

    {{-- Planning table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 text-xs text-gray-400 uppercase tracking-wide">
                        <th class="text-left px-5 py-3 min-w-[180px]">Salles</th>
                        @foreach(['08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00'] as $h)
                            <th class="text-center px-2 py-3 min-w-[80px]">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($salles as $salle)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-4">
                                <div class="font-medium text-gray-800">{{ $salle->nom }}</div>
                                @if($salle->localisation)
                                    <div class="text-xs text-gray-400">{{ $salle->localisation }}</div>
                                @endif
                            </td>
                            @foreach(['08','09','10','11','12','13','14','15','16','17'] as $hour)
                                <td class="px-1 py-2 text-center">
                                    @php
                                        $reservation = $salle->plannings->first(function($p) use ($hour) {
                                            return $p->date_debut->format('H') <= $hour && $p->date_fin->format('H:i') > $hour.':00';
                                        });
                                    @endphp
                                    @if($reservation)
                                        <div class="rounded-lg px-1 py-1.5 text-xs font-medium
                                            {{ $reservation->statut === 'approuve' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                            <div class="truncate max-w-[70px]">{{ $reservation->type_evenement ?? $reservation->titre }}</div>
                                            <div class="text-xs opacity-70">{{ $reservation->date_debut->format('H:i') }}-{{ $reservation->date_fin->format('H:i') }}</div>
                                        </div>
                                    @else
                                        <div class="h-8 rounded-lg bg-gray-50 border border-dashed border-gray-200"></div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-5 py-16 text-center text-gray-400">
                                Aucune salle disponible.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($salles->hasPages())
            <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between text-sm text-gray-500">
                <span>Affichage de {{ $salles->firstItem() }} à {{ $salles->lastItem() }} sur {{ $salles->total() }} salles</span>
                {{ $salles->links() }}
            </div>
        @endif
    </div>
</div>
{{-- Flash --}}
@if(session('success'))
    <div id="flash-success" class="fixed top-4 right-4 z-50 flex items-center gap-3 bg-white border border-green-200 text-green-800 px-4 py-3 rounded-xl shadow">
        <div class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
        </div>
        <span class="text-sm font-medium">{{ session('success') }}</span>
        <button onclick="document.getElementById('flash-success').remove()" class="ml-auto text-green-400 hover:text-green-600">✕</button>
    </div>
@endif

{{-- Modal Nouvelle Réservation --}}
<div id="reservation-modal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden px-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 relative">
        <button onclick="closeReservationModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <h2 class="text-lg font-bold text-gray-800 mb-5">Créer une Demande de Réservation</h2>

        <form method="POST" action="{{ route('responsable.reservations.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Salle</label>
                <select name="salle_id" id="modal-salle-id" required
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                    <option value="">Sélectionner une salle</option>
                    @foreach($salles->getCollection() as $s)
                        <option value="{{ $s->id }}">{{ $s->nom }} ({{ $s->capacite }} places)</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
                <input type="text" name="titre" required placeholder="Ex: Réunion d'équipe"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Type d'événement</label>
                <select name="type_evenement" required
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                    <option value="">Sélectionner un type</option>
                    <option value="Réunion">Réunion</option>
                    <option value="Formation">Formation</option>
                    <option value="Conférence">Conférence</option>
                    <option value="Présentation client">Présentation client</option>
                    <option value="Atelier">Atelier</option>
                    <option value="Entretien professionnel">Entretien professionnel</option>
                    <option value="Autre">Autre</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                    <input type="datetime-local" name="date_debut" required
                        min="{{ now()->format('Y-m-d\TH:i') }}"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                    <input type="datetime-local" name="date_fin" required
                        min="{{ now()->format('Y-m-d\TH:i') }}"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeReservationModal()"
                    class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                    Retour
                </button>
                <button type="submit"
                    class="px-4 py-2 text-sm font-semibold text-white bg-green-500 hover:bg-green-600 rounded-xl transition">
                    Soumettre la demande
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openReservationModal(salleId) {
    document.getElementById('reservation-modal').classList.remove('hidden');
    if (salleId) document.getElementById('modal-salle-id').value = salleId;
}
function closeReservationModal() {
    document.getElementById('reservation-modal').classList.add('hidden');
}
setTimeout(() => {
    const el = document.getElementById('flash-success');
    if (el) el.remove();
}, 4000);
</script>
@endsection
