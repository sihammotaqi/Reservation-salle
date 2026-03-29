@extends('layouts.admin')

@section('title', 'Planning')

@section('content')
<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">
    <div class="flex-1">
        <div class="flex items-center gap-3">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Salle Planning</h1>
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
        <form method="GET" action="{{ route('admin.planning.index') }}" class="flex items-center gap-2">
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
            <a href="{{ route('admin.planning.list') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-bold rounded-lg shadow-sm transition-colors h-10 focus:ring-2 focus:ring-green-500 focus:outline-none">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                Vue Liste
            </a>

            <a href="{{ route('admin.planning.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-[#00c950] hover:bg-[#00b046] text-white text-sm font-bold rounded-lg shadow-sm transition-colors h-10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                Nouvelle réservation
            </a>
        </div>
    </div>
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

{{-- Légende --}}
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

{{-- Timeline --}}
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
                                $reservation = $salle->plannings
                                ->where('date_debut', '>=', $selectedDate->startOfDay())
                                ->where('date_debut', '<=', $selectedDate->endOfDay())
                                ->first(function($p) use ($hour) {
                                return $p->date_debut->format('H') <= $hour 
                                && $p->date_fin->format('H:i') > $hour.':00';
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

<script>
setTimeout(() => {
    const el = document.getElementById('flash-success');
    if (el) el.remove();
}, 4000);
</script>
@endsection
