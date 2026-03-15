@extends('layouts.admin')

@section('title', 'Ajouter une nouvelle salle')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex items-center gap-2 text-sm">
        <a href="{{ route('admin.salles.index') }}" class="text-gray-500 hover:text-green-600 transition-colors">Gestion des salles</a>
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="font-semibold text-gray-900">Ajouter une nouvelle salle</span>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sm:p-10">
        <form method="POST" action="{{ route('admin.salles.store') }}" class="space-y-10">
            @csrf

            <!-- Section 1: Top Grid (4 inputs) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <!-- Nom de la salle -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nom de la salle</label>
                    <input type="text" name="nom" value="{{ old('nom') }}" required
                        class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm font-medium transition-all"
                        placeholder="ex: Salle de Réunion A">
                    @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Localisation -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Localisation</label>
                    <div class="relative">
                        <select name="localisation"
                            class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm font-medium transition-all appearance-none cursor-pointer">
                            <option value="">Sélectionner un bâtiment/étage</option>
                            <option value="Bloc A, Etage 2" {{ old('localisation') == 'Bloc A, Etage 2' ? 'selected' : '' }}>Bloc A, Etage 2</option>
                            <option value="Bâtiment principal, 1er étage" {{ old('localisation') == 'Bâtiment principal, 1er étage' ? 'selected' : '' }}>Bâtiment principal, 1er étage</option>
                            <option value="Bloc B, Etage 1" {{ old('localisation') == 'Bloc B, Etage 1' ? 'selected' : '' }}>Bloc B, Etage 1</option>
                            <option value="Bâtiment principal, 2ème étage" {{ old('localisation') == 'Bâtiment principal, 2ème étage' ? 'selected' : '' }}>Bâtiment principal, 2ème étage</option>
                        </select>
                        <svg class="w-4 h-4 text-gray-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                    @error('localisation') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- ID / Description -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Description</label>
                    <input type="text" name="description" value="{{ old('description') }}"
                        class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm font-medium transition-all"
                        placeholder="Brève description de la salle">
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Statut -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Statut Initial</label>
                    <label class="relative inline-flex items-center cursor-pointer mt-2">
                        <input type="checkbox" name="disponible" value="1" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                        <span class="ml-3 text-sm font-medium text-gray-700 peer-checked:text-green-600">Actif</span>
                    </label>
                </div>

                <!-- Capacité -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Capacité (Personnes)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
                        </div>
                        <input type="number" name="capacite" value="{{ old('capacite', 0) }}" min="1" required
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm font-medium transition-all">
                    </div>
                    @error('capacite') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>



            <!-- Diviseur -->
            <div class="border-t border-gray-100"></div>

            <!-- Section 2: Équipements -->
            <div>
                <h3 class="flex items-center gap-2 text-lg font-bold text-gray-900 mb-6">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Équipements de la salle
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($equipements as $eq)
                    <div class="flex items-center justify-between p-4 rounded-xl border border-gray-100 hover:border-green-200 transition-colors bg-white">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-700">{{ $eq->nom }}</span>
                                <span class="text-[11px] text-gray-400">Total dispo: {{ $eq->quantite }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-medium text-gray-400">Qté:</span>
                            <input type="number" name="equipements[{{ $eq->id }}]" value="0" min="0" max="{{ $eq->quantite }}" class="w-16 h-10 px-2 text-center text-sm font-bold bg-gray-50 border border-gray-200 rounded-lg focus:ring-1 focus:ring-green-500 focus:bg-white outline-none transition-all">
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-8 text-center text-sm text-gray-400 border-2 border-dashed border-gray-100 rounded-xl">
                        Aucun équipement disponible dans le système. Allez dans "Gestion des Équipements" pour en ajouter.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="flex items-center justify-end gap-4 pt-6 mt-4">
                <a href="{{ route('admin.salles.index') }}" class="px-6 py-2.5 text-sm font-semibold text-gray-600 hover:text-gray-900 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-2.5 bg-[#00c950] hover:bg-[#00b046] text-white font-semibold text-sm rounded-lg transition-colors shadow-sm focus:ring-4 focus:ring-[#00c950]/20 outline-none">
                    Enregistrer la salle
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
