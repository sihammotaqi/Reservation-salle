@extends('layouts.admin')

@section('title', 'Nouvelle réservation')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.planning.index') }}" class="text-sm text-green-600 hover:underline">&larr; Retour au planning</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Nouvelle réservation</h1>
    </div>
    <div class="bg-white rounded-2xl shadow border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.planning.store') }}" class="space-y-5">
            @csrf
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm">
                   {{ session('error') }}
                </div>
            @endif
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Titre *</label>
                <input type="text" name="titre" value="{{ old('titre') }}" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm">
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                @error('titre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Salle *</label>
                <select name="salle_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none text-sm bg-white" required>
                    <option value="">-- Sélectionner une salle --</option>
                    @foreach($salles as $salle)
                    <option value="{{ $salle->id }}" {{ old('salle_id') == $salle->id ? 'selected' : '' }}>
                        {{ $salle->nom }} ({{ $salle->capacite }} pers.)
                    </option>
                    @endforeach
                </select>
                @error('salle_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
    <label class="text-sm text-gray-500">Utilisateur</label>
    <input type="text" value="{{ auth()->user()->name }}" disabled class="w-full bg-gray-100">
</div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Début *</label>
                    <input type="datetime-local" name="date_debut" value="{{ old('date_debut') }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none text-sm">
                    @error('date_debut') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Fin *</label>
                    <input type="datetime-local" name="date_fin" value="{{ old('date_fin') }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none text-sm">
                    @error('date_fin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold text-sm rounded-xl transition-colors shadow">Créer</button>
                <a href="{{ route('admin.planning.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl transition-colors">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
