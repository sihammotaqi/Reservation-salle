@extends('layouts.user')

@section('title', 'Réserver ' . $salle->nom)

@section('content')
<div class="space-y-6">

    <!-- Back Button -->
    <div>
        <a href="{{ route('user.salles.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Retour aux salles
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Salle Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-6 opacity-5">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/></svg>
                </div>
                
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $salle->nom }}</h1>
                <div class="flex items-center gap-4 text-sm text-gray-500 mb-6">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Capacité: {{ $salle->capacite }} personnes
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $salle->localisation ?: 'Localisation non spécifiée' }}
                    </div>
                </div>

                <div class="prose prose-sm text-gray-600 max-w-none mb-8">
                    <p>{{ $salle->description ?: "Aucune description détaillée n'est disponible pour cette salle." }}</p>
                </div>

                <!-- Équipements inclus -->
                <div>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-4">Équipements inclus</h3>
                    @if($salle->equipements->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($salle->equipements as $eq)
                        <div class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 bg-gray-50/50">
                            <div class="w-8 h-8 rounded bg-white border border-gray-100 shadow-sm flex items-center justify-center text-gray-400 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-700 leading-tight">{{ $eq->nom }}</span>
                                <span class="text-[11px] text-gray-400">Quantité: {{ $eq->pivot->quantite }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                        <p class="text-sm text-gray-500 italic">Cette salle ne dispose d'aucun équipement spécifique enregistré.</p>
                    @endif
                </div>
            </div>

            <!-- Existing Reservations Timeline -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <h3 class="font-bold text-gray-900">Réservations à venir (Approuvées)</h3>
                </div>
                
                @if($plannings->count() > 0)
                <div class="divide-y divide-gray-50">
                    @foreach($plannings as $plan)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ mb_strtoupper($plan->titre) }}</p>
                            <p class="text-xs text-gray-500 mt-1">Par: {{ $plan->user->name ?? 'Utilisateur' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">{{ $plan->date_debut->format('d/m/Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $plan->date_debut->format('H:i') }} - {{ $plan->date_fin->format('H:i') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="p-8 text-center bg-gray-50/50">
                    <p class="text-sm text-gray-500 font-medium">Aucune réservation approuvée à venir. La voie est libre !</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Right: Booking Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-6">
                <h3 class="flex items-center gap-2 text-lg font-bold text-gray-900 mb-6">
                    <svg class="w-5 h-5 text-[#00c950]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Réserver cet espace
                </h3>

                <form action="{{ route('user.reservations.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="salle_id" value="{{ $salle->id }}">

                    <div>
                        <label class="block text-[13px] font-bold text-gray-700 mb-2">Titre de l'événement</label>
                        <input type="text" name="titre" value="{{ old('titre') }}" required
                            class="w-full px-4 py-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#00c950] focus:border-[#00c950] outline-none transition-all placeholder-gray-400"
                            placeholder="Ex: Réunion d'équipe hebdomadaire">
                        @error('titre') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[13px] font-bold text-gray-700 mb-2">Début</label>
                        <input type="datetime-local" name="date_debut" value="{{ old('date_debut') }}" required
                            class="w-full px-4 py-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#00c950] focus:border-[#00c950] outline-none transition-all">
                        @error('date_debut') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[13px] font-bold text-gray-700 mb-2">Fin</label>
                        <input type="datetime-local" name="date_fin" value="{{ old('date_fin') }}" required
                            class="w-full px-4 py-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#00c950] focus:border-[#00c950] outline-none transition-all">
                        @error('date_fin') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-4 mt-2 border-t border-gray-100">
                        <button type="submit" class="w-full flex items-center justify-center py-3.5 px-4 bg-[#00c950] hover:bg-[#00b046] text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-[#00c950]/30 hover:shadow-[#00c950]/40">
                            Envoyer la demande
                        </button>
                        <p class="text-center text-[11px] text-gray-400 mt-3">
                            Votre réservation sera soumise à validation (statut: <span class="font-medium text-orange-400">En attente</span>).
                        </p>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
