@extends('layouts.user')

@section('title', 'Salles')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Salles</h1>
            <p class="text-gray-500 mt-1">Consultez les salles et soumettez une demande de réservation.</p>
        </div>
        <button onclick="openReservationModal(null)"
            class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvelle Réservation
        </button>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div id="flash-success" class="flex items-center gap-3 bg-white border border-green-200 text-green-800 px-4 py-3 rounded-xl shadow mb-6">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
            <button onclick="document.getElementById('flash-success').remove()" class="ml-auto text-green-400 hover:text-green-600">✕</button>
        </div>
    @endif

    @if(session('error'))
        <div id="flash-error" class="flex items-center gap-3 bg-white border border-red-200 text-red-800 px-4 py-3 rounded-xl shadow mb-6">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
            <button onclick="document.getElementById('flash-error').remove()" class="ml-auto text-red-400 hover:text-red-600">✕</button>
        </div>
    @endif

    {{-- Grille des salles --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($salles as $salle)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <div class="flex items-start justify-between mb-3">
                        <h2 class="text-base font-semibold text-gray-800">{{ $salle->nom }}</h2>
                        @if($salle->disponible)
                            <span class="text-xs font-medium bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Disponible</span>
                        @else
                            <span class="text-xs font-medium bg-red-100 text-red-600 px-2 py-0.5 rounded-full">Indisponible</span>
                        @endif
                    </div>

                    <div class="space-y-1 text-sm text-gray-500 mb-4">
                        @if($salle->localisation)
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $salle->localisation }}
                            </div>
                        @endif
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $salle->capacite }} places
                        </div>
                        @if($salle->equipements->count() > 0)
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2v-4M9 21H5a2 2 0 01-2-2v-4m0 0h18"/>
                                </svg>
                                {{ $salle->equipements->pluck('nom')->join(', ') }}
                            </div>
                        @endif
                    </div>
                </div>

                <button
                    @if($salle->disponible)
                        onclick="openReservationModal({{ $salle->id }}, '{{ addslashes($salle->nom) }}', {{ $salle->capacite }})"
                        class="w-full bg-green-500 hover:bg-green-600 text-white text-sm font-semibold py-2 rounded-xl transition"
                    @else
                        disabled
                        class="w-full bg-gray-100 text-gray-400 text-sm font-semibold py-2 rounded-xl cursor-not-allowed"
                    @endif
                >
                    @if($salle->disponible) Réserver @else Indisponible @endif
                </button>
            </div>
        @empty
            <div class="col-span-3 text-center text-gray-400 py-16">
                Aucune salle disponible pour le moment.
            </div>
        @endforelse
    </div>
    {{-- Pagination --}}
    @if($salles->hasPages())
    <div class="mt-6 px-4">
        {{ $salles->links() }}
    </div>
    @endif

</div>

{{-- ===== MODAL NOUVELLE RÉSERVATION ===== --}}
<div id="reservation-modal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden px-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 relative">

        <button onclick="closeReservationModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <h2 class="text-lg font-bold text-gray-800 mb-5">Créer une Demande de Réservation</h2>

        {{-- Conflit error --}}
        @if(session('error') && session('conflict'))
            <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                <div>
                    <p class="font-semibold">Conflit d'horaire détecté</p>
                    <p class="text-red-600 mt-0.5">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('user.reservations.store') }}">
            @csrf

            {{-- Salle --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Salle</label>
                <select name="salle_id" id="modal-salle-id" required
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 @error('salle_id') border-red-400 @enderror">
                    <option value="">Sélectionner une salle</option>
                    @foreach($salles as $s)
                        @if($s->disponible)
                            <option value="{{ $s->id }}" {{ old('salle_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->nom }} ({{ $s->capacite }} places)
                            </option>
                        @endif
                    @endforeach
                </select>
                @error('salle_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Titre --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
                <input type="text" name="titre" value="{{ old('titre') }}" required
                    placeholder="Ex: Réunion d'équipe"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 @error('titre') border-red-400 @enderror">
                @error('titre')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Type d'événement --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Type d'événement</label>
                <select name="type_evenement" required
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 @error('type_evenement') border-red-400 @enderror">
                    <option value="">Sélectionner un type</option>
                    <option value="Réunion" {{ old('type_evenement') == 'Réunion' ? 'selected' : '' }}>Réunion</option>
                    <option value="Formation" {{ old('type_evenement') == 'Formation' ? 'selected' : '' }}>Formation</option>
                    <option value="Conférence" {{ old('type_evenement') == 'Conférence' ? 'selected' : '' }}>Conférence</option>
                    <option value="Présentation client" {{ old('type_evenement') == 'Présentation client' ? 'selected' : '' }}>Présentation client</option>
                    <option value="Atelier" {{ old('type_evenement') == 'Atelier' ? 'selected' : '' }}>Atelier</option>
                    <option value="Entretien professionnel" {{ old('type_evenement') == 'Entretien professionnel' ? 'selected' : '' }}>Entretien professionnel</option>
                    <option value="Autre" {{ old('type_evenement') == 'Autre' ? 'selected' : '' }}>Autre</option>
                </select>
                @error('type_evenement')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Dates --}}
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                    <input type="datetime-local" name="date_debut" min="{{ now()->format('Y-m-d\TH:i') }}" value="{{ old('date_debut') }}" required
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 @error('date_debut') border-red-400 @enderror">
                    @error('date_debut')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                    <input type="datetime-local" name="date_fin" min="{{ now()->format('Y-m-d\TH:i') }}" value="{{ old('date_fin') }}" required
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 @error('date_fin') border-red-400 @enderror">
                    @error('date_fin')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Buttons --}}
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
function openReservationModal(salleId, salleNom, capacite) {
    document.getElementById('reservation-modal').classList.remove('hidden');
    if (salleId) {
        document.getElementById('modal-salle-id').value = salleId;
    }
}

function closeReservationModal() {
    document.getElementById('reservation-modal').classList.add('hidden');
}
// Réouvrir modal si erreur de validation
@if($errors->any() || session('error'))
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('reservation-modal').classList.remove('hidden');
    });
@endif
</script>

@endsection
