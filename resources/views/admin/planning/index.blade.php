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
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Titre</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Salle</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Utilisateur</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Période</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Statut</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($plannings as $planning)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 font-semibold text-gray-900 text-sm">{{ $planning->titre }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $planning->salle->nom }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $planning->user->name }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">
                    {{ $planning->date_debut->format('d/m/Y H:i') }} → {{ $planning->date_fin->format('d/m/Y H:i') }}
                </td>
                <td class="px-6 py-4">
                    @php
                        $statuts = ['en_attente' => 'bg-yellow-100 text-yellow-700', 'approuve' => 'bg-green-100 text-green-700', 'rejete' => 'bg-red-100 text-red-700'];
                        $labels  = ['en_attente' => 'En attente', 'approuve' => 'Approuvé', 'rejete' => 'Rejeté'];
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
    @endif
</div>
@endsection
