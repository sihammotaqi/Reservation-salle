@extends('layouts.admin')

@section('title', 'Équipements')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Équipements</h1>
        <p class="text-sm text-gray-500 mt-1">Gérez le matériel disponible dans les salles.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Add Equipment Form -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow border border-gray-100 p-6">
            <h2 class="text-base font-bold text-gray-800 mb-4">Ajouter un équipement</h2>
            <form method="POST" action="{{ route('admin.equipements.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nom *</label>
                    <input type="text" name="nom" value="{{ old('nom') }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none text-sm"
                        placeholder="Ex : Projecteur">
                    @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Quantité *</label>
                    <input type="number" name="quantite" value="{{ old('quantite', 1) }}" min="1" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none text-sm">
                    @error('quantite') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Salle associée</label>
                    <select name="salle_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none text-sm bg-white">
                        <option value="">-- Aucune --</option>
                        @foreach($salles as $salle)
                        <option value="{{ $salle->id }}" {{ old('salle_id') == $salle->id ? 'selected' : '' }}>{{ $salle->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold text-sm rounded-xl transition-colors shadow">
                    Ajouter
                </button>
            </form>
        </div>
    </div>

    <!-- Equipments List -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow border border-gray-100 overflow-hidden">
            @if($equipements->isEmpty())
                <div class="text-center py-16 text-gray-400">
                    <p class="text-sm font-medium">Aucun équipement trouvé.</p>
                </div>
            @else
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Qté</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Salle</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($equipements as $eq)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-semibold text-gray-900 text-sm">{{ $eq->nom }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $eq->quantite }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $eq->salle?->nom ?? '—' }}</td>
                        <td class="px-6 py-4 text-right">
                            <form method="POST" action="{{ route('admin.equipements.destroy', $eq) }}" onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-sm font-semibold text-red-500 hover:text-red-700">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>
@endsection
