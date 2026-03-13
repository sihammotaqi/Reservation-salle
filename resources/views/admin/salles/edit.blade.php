@extends('layouts.admin')

@section('title', 'Modifier la salle')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.salles.index') }}" class="text-sm text-green-600 hover:underline">&larr; Retour aux salles</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Modifier : {{ $salle->nom }}</h1>
    </div>

    <div class="bg-white rounded-2xl shadow border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.salles.update', $salle) }}" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nom de la salle *</label>
                <input type="text" name="nom" value="{{ old('nom', $salle->nom) }}" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm">
                @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Capacité (personnes) *</label>
                <input type="number" name="capacite" value="{{ old('capacite', $salle->capacite) }}" min="1" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm resize-none">{{ old('description', $salle->description) }}</textarea>
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="disponible" id="disponible" {{ $salle->disponible ? 'checked' : '' }}
                    class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                <label for="disponible" class="text-sm font-medium text-gray-700">Disponible à la réservation</label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold text-sm rounded-xl transition-colors shadow">
                    Enregistrer
                </button>
                <a href="{{ route('admin.salles.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
