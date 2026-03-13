@extends('layouts.admin')

@section('title', 'Salles')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Salles</h1>
        <p class="text-sm text-gray-500 mt-1">Gérez les salles disponibles dans votre établissement.</p>
    </div>
    <a href="{{ route('admin.salles.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-xl shadow transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Ajouter une salle
    </a>
</div>

<div class="bg-white rounded-2xl shadow border border-gray-100 overflow-hidden">
    @if($salles->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            <p class="text-sm font-medium">Aucune salle trouvée. Commencez par en ajouter une.</p>
        </div>
    @else
    <table class="min-w-full divide-y divide-gray-100">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nom</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Capacité</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Équipements</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Statut</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($salles as $salle)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                    <p class="font-semibold text-gray-900">{{ $salle->nom }}</p>
                    @if($salle->description)
                    <p class="text-xs text-gray-500 mt-0.5 truncate max-w-xs">{{ $salle->description }}</p>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-gray-700">{{ $salle->capacite }} pers.</td>
                <td class="px-6 py-4 text-sm text-gray-700">{{ $salle->equipements_count }}</td>
                <td class="px-6 py-4">
                    @if($salle->disponible)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Disponible</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Indisponible</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                    <a href="{{ route('admin.salles.edit', $salle) }}" class="text-sm text-green-600 hover:text-green-800 font-medium">Modifier</a>
                    <form method="POST" action="{{ route('admin.salles.destroy', $salle) }}" onsubmit="return confirm('Supprimer cette salle ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-medium">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
