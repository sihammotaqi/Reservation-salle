@extends('layouts.admin')

@section('title', 'Planning')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Salle Planning</h1>
        <p class="text-sm text-gray-500 mt-1">Gérez et approuvez les réservations de salles.</p>
    </div>
    <a href="{{ route('admin.planning.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-xl shadow transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nouvelle réservation
    </a>
</div>

{{-- Flash --}}
@if(session('success'))
    <div id="flash-success" class="flex items-center gap-3 bg-white border border-green-200 text-green-800 px-4 py-3 rounded-xl shadow-sm mb-5">
        <div class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
        </div>
        <span class="text-sm font-medium">{{ session('success') }}</span>
        <button onclick="document.getElementById('flash-success').remove()" class="ml-auto text-green-400 hover:text-green-600">✕</button>
    </div>
@endif

{{-- Tabs --}}
<div class="flex gap-2 mb-5">
    @php $view = request('view', 'timeline'); @endphp
    <a href="{{ request()->fullUrlWithQuery(['view' => 'timeline']) }}"
        class="px-4 py-1.5 rounded-full text-sm font-medium transition {{ $view === 'timeline' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
        Timeline
    </a>
    <a href="{{ request()->fullUrlWithQuery(['view' => 'liste']) }}"
        class="px-4 py-1.5 rounded-full text-sm font-medium transition {{ $view === 'liste' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
        Liste
    </a>
</div>

@if($view === 'timeline')
{{-- ===== TIMELINE VIEW ===== --}}
<div class="mb-4 flex items-center gap-4">
    <div class="flex items-center gap-1.5">
        <span class="w-3 h-3 rounded-full bg-green-400"></span>
        <span class="text-sm text-gray-600">Approuvé</span>
    </div>
    <div class="flex items-center gap-1.5">
        <span class="w-3 h-3 rounded-full bg-orange-400"></span>
        <span class="text-sm text-gray-600">En attente</span>
    </div>
    <div class="flex items-center gap-1.5">
        <span class="w-3 h-3 rounded-full bg-red-300"></span>
        <span class="text-sm text-gray-600">Rejeté</span>
    </div>
</div>

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
                                        return $p->date_debut->format('H') <= $hour && $p->date_fin->format('H') > $hour;
                                    });
                                @endphp
                                @if($reservation)
                                    @php
                                        $colors = [
                                            'approuve'   => 'bg-green-100 text-green-700',
                                            'en_attente' => 'bg-orange-100 text-orange-700',
                                            'rejete'     => 'bg-red-100 text-red-500',
                                            'annule'     => 'bg-gray-100 text-gray-400',
                                        ];
                                    @endphp
                                    <div class="rounded-lg px-1 py-1.5 text-xs font-medium {{ $colors[$reservation->statut] ?? 'bg-gray-100 text-gray-500' }}">
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
                        <td colspan="11" class="px-5 py-16 text-center text-gray-400">Aucune salle disponible.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($salles->hasPages())
        <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between text-sm text-gray-500">
            <span>Affichage de {{ $salles->firstItem() }} à {{ $salles->lastItem() }} sur {{ $salles->total() }} salles</span>
            {{ $salles->appends(request()->query())->links() }}
        </div>
    @endif
</div>

@else
{{-- ===== LISTE VIEW ===== --}}
<div class="bg-white rounded-2xl shadow border border-gray-100 overflow-hidden">
    @if($plannings->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <p class="text-sm font-medium">Aucune réservation trouvée.</p>
        </div>
    @else
    <table class="min-w-full divide-y divide-gray-100">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Demandeur</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Salle</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Horaire</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Type</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Statut</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($plannings as $planning)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-semibold text-xs flex-shrink-0">
                            {{ strtoupper(substr($planning->user->name ?? 'U', 0, 2)) }}
                        </div>
                        <div>
                            <div class="font-medium text-gray-800 text-sm">{{ $planning->user->name ?? '—' }}</div>
                            <div class="text-xs text-gray-400">{{ $planning->titre }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    <div class="font-medium">{{ $planning->salle->nom ?? '—' }}</div>
                    @if($planning->salle?->localisation)
                        <div class="text-xs text-gray-400">{{ $planning->salle->localisation }}</div>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                    <div>{{ $planning->date_debut->format('H:i') }} - {{ $planning->date_fin->format('H:i') }}</div>
                    <div class="text-xs text-gray-400">{{ $planning->date_debut->format('d M Y') }}</div>
                </td>
                <td class="px-6 py-4">
                    @if($planning->type_evenement)
                        <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">{{ $planning->type_evenement }}</span>
                    @else
                        <span class="text-gray-400 text-xs">—</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    @php
                        $statuts = [
                            'en_attente' => 'bg-orange-100 text-orange-700',
                            'approuve'   => 'bg-green-100 text-green-700',
                            'rejete'     => 'bg-red-100 text-red-600',
                            'annule'     => 'bg-gray-100 text-gray-500',
                        ];
                        $labels = [
                            'en_attente' => 'En attente',
                            'approuve'   => 'Approuvé',
                            'rejete'     => 'Rejeté',
                            'annule'     => 'Annulé',
                        ];
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statuts[$planning->statut] ?? '' }}">
                        {{ $labels[$planning->statut] ?? $planning->statut }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        @if($planning->statut !== 'approuve')
                        <form method="POST" action="{{ route('admin.planning.update', $planning) }}">
                            @csrf @method('PUT')
                            <input type="hidden" name="statut" value="approuve">
                            <button type="submit" class="text-xs font-semibold text-green-600 hover:text-green-800">Approuver</button>
                        </form>
                        @endif
                        @if($planning->statut !== 'rejete')
                        <form method="POST" action="{{ route('admin.planning.update', $planning) }}">
                            @csrf @method('PUT')
                            <input type="hidden" name="statut" value="rejete">
                            <button type="submit" class="text-xs font-semibold text-orange-500 hover:text-orange-700">Rejeter</button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('admin.planning.destroy', $planning) }}" onsubmit="return confirm('Supprimer cette réservation ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-700">Supprimer</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if($plannings->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between text-sm text-gray-500">
            <span>Affichage de {{ $plannings->firstItem() }} à {{ $plannings->lastItem() }} sur {{ $plannings->total() }} réservations</span>
            {{ $plannings->appends(request()->query())->links() }}
        </div>
    @endif
    @endif
</div>
@endif

<script>
setTimeout(() => {
    const el = document.getElementById('flash-success');
    if (el) el.remove();
}, 4000);
</script>
@endsection
