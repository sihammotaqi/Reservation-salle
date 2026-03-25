@extends('layouts.responsable')

@section('title', 'Tableau de bord')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Aperçu des réservations</h1>
        <p class="text-gray-500 mt-1">Gérez les demandes de salles pour votre département.</p>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div id="flash-success" class="flex items-center gap-3 bg-white border border-green-200 text-green-800 px-4 py-3 rounded-xl shadow-sm mb-6">
            <div class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <span class="text-sm font-medium">{{ session('success') }}</span>
            <button onclick="document.getElementById('flash-success').remove()" class="ml-auto text-green-400 hover:text-green-600">✕</button>
        </div>
    @endif

    @if(session('error'))
        <div id="flash-error" class="flex items-center gap-3 bg-white border border-red-200 text-red-800 px-4 py-3 rounded-xl shadow-sm mb-6">
            <div class="w-5 h-5 rounded-full bg-red-500 flex items-center justify-center flex-shrink-0">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <span class="text-sm font-medium">{{ session('error') }}</span>
            <button onclick="document.getElementById('flash-error').remove()" class="ml-auto text-red-400 hover:text-red-600">✕</button>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">

        {{-- En attente --}}
        <div class="bg-white rounded-2xl shadow-sm border-l-4 border-orange-400 p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Réservations en attente</span>
                <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-4xl font-bold text-gray-800">{{ $enAttente }}</p>
            @if($enAttente > 0)
                <span class="mt-2 inline-block text-xs font-semibold bg-orange-100 text-orange-600 px-2 py-0.5 rounded-full">Action requise</span>
            @endif
        </div>

        {{-- Validations --}}
        <div class="bg-white rounded-2xl shadow-sm border-l-4 border-green-400 p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Validations (Ce mois)</span>
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-4xl font-bold text-gray-800">{{ $validations }}</p>
            @if($evolutionValidations != 0)
                <p class="mt-2 text-xs {{ $evolutionValidations > 0 ? 'text-green-500' : 'text-red-500' }}">
                    {{ $evolutionValidations > 0 ? '+' : '' }}{{ $evolutionValidations }}% vs le mois dernier
                </p>
            @endif
        </div>

        {{-- Refus --}}
        <div class="bg-white rounded-2xl shadow-sm border-l-4 border-red-400 p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Refus (Ce mois)</span>
                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-4xl font-bold text-gray-800">{{ $refus }}</p>
            <p class="mt-2 text-xs text-gray-400">Taux de refus: <span class="font-semibold text-gray-600">{{ $tauxRefus }}%</span></p>
        </div>
    </div>

    {{-- Table demandes en attente --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-700">Demandes en attente</h2>
            <span class="text-sm text-gray-400">{{ $demandes->total() }} demande(s)</span>
        </div>

        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 text-xs text-gray-400 uppercase tracking-wide">
                    <th class="text-left px-5 py-3">Demandeur</th>
                    <th class="text-left px-5 py-3">Salle</th>
                    <th class="text-left px-5 py-3">Horaire</th>
                    <th class="text-left px-5 py-3">Type</th>
                    <th class="text-left px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($demandes as $demande)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-green-100 text-green-700 flex items-center justify-center text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($demande->user->name ?? 'U', 0, 2)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">{{ $demande->user->name ?? '—' }}</div>
                                    <div class="text-xs text-gray-400">{{ $demande->user->email ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="font-medium text-gray-700">{{ $demande->salle->nom ?? '—' }}</div>
                            @if($demande->salle?->localisation)
                                <div class="text-xs text-gray-400">{{ $demande->salle->localisation }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-gray-600">
                            <div>{{ $demande->date_debut->format('H:i') }} - {{ $demande->date_fin->format('H:i') }}</div>
                            <div class="text-xs text-gray-400">
                                {{ $demande->date_debut->isToday() ? "Aujourd'hui" : $demande->date_debut->format('d M Y') }}
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            @php
                                $colors = [
                                    'Réunion'               => 'bg-blue-100 text-blue-700',
                                    'Formation'             => 'bg-purple-100 text-purple-700',
                                    'Conférence'            => 'bg-indigo-100 text-indigo-700',
                                    'Présentation client'   => 'bg-green-100 text-green-700',
                                    'Atelier'               => 'bg-orange-100 text-orange-700',
                                    'Entretien professionnel' => 'bg-gray-100 text-gray-700',
                                ];
                                $type = $demande->type_evenement ?? $demande->titre;
                                $colorClass = $colors[$type] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $colorClass }}">
                                {{ $type }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                {{-- Approuver --}}
                                <form method="POST" action="{{ route('responsable.reservations.approuver', $demande) }}">
                                    @csrf
                                    <button type="submit" title="Approuver"
                                        class="w-8 h-8 rounded-full flex items-center justify-center border-2 border-green-400 text-green-500 hover:bg-green-500 hover:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                </form>

                                {{-- Refuser --}}
                                <button type="button" title="Refuser"
                                    onclick="openRefusModal({{ $demande->id }})"
                                    class="w-8 h-8 rounded-full flex items-center justify-center border-2 border-red-400 text-red-400 hover:bg-red-500 hover:text-white transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-16 text-center text-gray-400">
                            Aucune demande en attente pour le moment. 🎉
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($demandes->hasPages())
            <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between text-sm text-gray-500">
                <span>Affichage de {{ $demandes->firstItem() }} à {{ $demandes->lastItem() }} sur {{ $demandes->total() }} demandes</span>
                {{ $demandes->links() }}
            </div>
        @endif
    </div>
</div>

{{-- ===== MODAL REFUS ===== --}}
<div id="refus-modal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden px-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 relative">

        <button onclick="closeRefusModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-base font-bold text-gray-800">Confirmer le refus</h2>
                <p class="text-sm text-gray-500">Cette action informera l'utilisateur de votre décision.</p>
            </div>
        </div>

        <form id="refus-form" method="POST">
            @csrf
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Motif du refus <span class="text-gray-400 font-normal">(optionnel)</span>
                </label>
                <textarea name="motif" rows="4" placeholder="Veuillez indiquer le motif du refus pour aider l'utilisateur..."
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-300 resize-none"></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeRefusModal()"
                    class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                    Annuler
                </button>
                <button type="submit"
                    class="px-4 py-2 text-sm font-semibold text-white bg-red-500 hover:bg-red-600 rounded-xl transition">
                    Confirmer le refus
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openRefusModal(id) {
    document.getElementById('refus-modal').classList.remove('hidden');
    document.getElementById('refus-form').action = '/responsable/reservations/' + id + '/rejeter';
}
function closeRefusModal() {
    document.getElementById('refus-modal').classList.add('hidden');
}

setTimeout(() => {
    ['flash-success', 'flash-error'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.remove();
    });
}, 4000);
</script>
@endsection
