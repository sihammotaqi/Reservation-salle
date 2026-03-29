@extends('layouts.admin')

@section('title', 'Planning')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <p>{{ ($selectedDate ?? $today)->format('d/m/Y') }}</p>
        <h1 class="text-2xl font-bold text-gray-900">Salle Planning</h1>
        <form method="GET" action="{{ route('admin.planning.index') }}">
    <input type="date" name="date" value="{{ request('date', $today->toDateString()) }}">
    <button type="submit">Filtrer</button>
    </form>
    <a href="{{ route('admin.planning.list') }}"
   class="ml-3 px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-900">
    Liste
</a>
        <p class="text-sm text-gray-500 mt-1">Visualisez les réservations des salles.</p>
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
