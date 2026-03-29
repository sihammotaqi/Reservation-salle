@extends('layouts.responsable')

@section('title', 'Tableau de bord')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Aperçu des réservations</h1>
            <p class="text-sm text-gray-500 mt-1">Gérez les demandes de salles pour votre département.</p>
        </div>
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
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">

        {{-- En attente --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Réservations en attente</p>
                <p class="text-4xl font-bold text-gray-900 mt-1">{{ $enAttente }}</p>
                @if($enAttente > 0)
                    <p class="text-xs text-orange-500 font-medium mt-2 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Action requise
                    </p>
                @endif
            </div>
            <div class="w-11 h-11 bg-orange-50 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        {{-- Validations --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Validations (Ce mois)</p>
                <p class="text-4xl font-bold text-gray-900 mt-1">{{ $validations }}</p>
                @if($evolutionValidations != 0)
                    <p class="text-xs {{ $evolutionValidations > 0 ? 'text-green-600' : 'text-red-500' }} font-medium mt-2 flex items-center gap-1">
                        @if($evolutionValidations > 0)
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                        @endif
                        {{ $evolutionValidations > 0 ? '+' : '' }}{{ $evolutionValidations }}% vs le mois dernier
                    </p>
                @endif
            </div>
            <div class="w-11 h-11 bg-green-50 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        {{-- Refus --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Refus (Ce mois)</p>
                <p class="text-4xl font-bold text-gray-900 mt-1">{{ $refus }}</p>
                <p class="text-xs text-gray-500 font-medium mt-2 flex items-center gap-1">
                    <span class="font-semibold text-gray-600">{{ $tauxRefus }}%</span> Taux de refus
                </p>
            </div>
            <div class="w-11 h-11 bg-red-50 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Table demandes en attente --}}
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

        <table class="min-w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Demandeur</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Salle</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Horaire</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Type</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-400 uppercase tracking-wide">Actions</th>
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
                            <div class="flex items-center justify-end gap-2">
                                {{-- Approuver --}}
                                <form method="POST" action="{{ route('responsable.reservations.approuver', $demande) }}" class="flex m-0 p-0">
                                    @csrf
                                    <button type="submit" title="Approuver"
                                        class="text-green-500 hover:text-green-700 transition-colors flex items-center">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                </form>

                                {{-- Refuser --}}
                                <button type="button" title="Refuser"
                                    onclick="openRefusModal({{ $demande->id }})"
                                    class="text-red-400 hover:text-red-600 transition-colors flex items-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-gray-400 text-sm">
                            Aucune demande en attente.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="flex items-center justify-between px-5 py-4 border-t border-gray-100">
            <p class="text-sm text-gray-500">
                Affichage de {{ $demandes->firstItem() ?? 0 }} à {{ $demandes->lastItem() ?? 0 }} sur {{ $demandes->total() }} demande(s) en attente
            </p>
            {{ $demandes->links() }}
        </div>
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
