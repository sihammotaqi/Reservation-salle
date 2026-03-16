@extends('layouts.user')

@section('title', 'Tableau de bord')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Bonjour, {{ Auth::user()->name }}</h1>
            <p class="text-sm text-gray-500 mt-1">Bienvenue sur votre portail. Réservez une salle ou gérez vos demandes.</p>
        </div>
        <div>
            <a href="{{ route('user.salles.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#00c950] text-white text-sm font-semibold rounded-xl hover:bg-[#00b046] transition-all shadow-sm shadow-[#00c950]/30 hover:shadow-[#00c950]/40">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nouvelle Réservation
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Stat 1: Total -->
        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Mes Réservations</p>
                <p class="text-4xl font-bold text-gray-900 mt-1">{{ $totalReservations }}</p>
                <p class="text-xs text-gray-500 font-medium mt-2 flex items-center gap-1">
                    Total historique
                </p>
            </div>
            <div class="w-11 h-11 bg-gray-50 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>

        <!-- Stat 2: Approuvées -->
        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Approuvées</p>
                <p class="text-4xl font-bold text-gray-900 mt-1">{{ $approvedReservations }}</p>
                <p class="text-xs text-green-600 font-medium mt-2 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    Validées par l'admin
                </p>
            </div>
            <div class="w-11 h-11 bg-green-50 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
        </div>

        <!-- Stat 3: En Attente -->
        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">En Attente</p>
                <p class="text-4xl font-bold text-gray-900 mt-1">{{ $pendingReservations }}</p>
                <p class="text-xs text-orange-500 font-medium mt-2 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    En attente de validation
                </p>
            </div>
            <div class="w-11 h-11 bg-orange-50 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <!-- Recent Reservations -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden mt-6">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h2 class="text-base font-bold text-gray-900">Mes 5 dernières demandes</h2>
        </div>
        <table class="min-w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Salle</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Événement</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Date & Heure</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Statut</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($recentReservations as $res)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-1.5 text-sm font-semibold text-gray-900">
                            {{ $res->salle->nom }}
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-gray-100 text-gray-700">
                            {{ mb_strtoupper($res->titre) }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-sm font-medium text-gray-900">{{ $res->date_debut->format('d/m/Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $res->date_debut->format('H:i') }} - {{ $res->date_fin->format('H:i') }}</p>
                    </td>
                    <td class="px-5 py-4">
                        @if($res->statut === 'approuve')
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-green-600">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Approuvée
                            </span>
                        @elseif($res->statut === 'en_attente')
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-orange-500">
                                <span class="w-1.5 h-1.5 bg-orange-500 rounded-full"></span> En attente
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-red-600">
                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> Rejetée
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-5 py-12 text-center text-gray-400 text-sm">
                        Vous n'avez effectué aucune réservation pour le moment.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t border-gray-100">
            <a href="{{ route('user.reservations.index') }}" class="text-sm font-semibold text-[#00c950] hover:text-[#00b046] transition-colors">
                Voir toutes mes réservations &rarr;
            </a>
        </div>
    </div>
</div>
@endsection
