@extends('layouts.admin')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Gestion des Utilisateurs</h1>
            <p class="text-[15px] text-gray-500 mt-2 max-w-2xl leading-relaxed">
                Gérez les accès des utilisateurs, attribuez des rôles et supervisez l'activité de la plateforme.
            </p>
        </div>
        <div class="flex items-center gap-4 shrink-0 mt-2 md:mt-0">
            <!-- Search Input -->
            <div class="relative hidden sm:block">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" placeholder="Rechercher un utilisateur"
                       class="pl-10 pr-4 py-2.5 w-64 text-sm font-medium bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00c950] focus:border-[#00c950] transition-all shadow-sm placeholder-gray-400">
            </div>

            <!-- Add Button -->
            <a href="{{ route('admin.utilisateurs.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#00c950] hover:bg-[#00b046] text-white text-sm font-bold rounded-lg shadow-sm transition-all shadow-[#00c950]/20">
                <svg class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                Ajouter un utilisateur
            </a>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100/80 overflow-hidden">
        @if($utilisateurs->isEmpty())
            <div class="text-center py-20 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-4 opacity-40 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <p class="text-[15px] font-medium text-gray-500">Aucun utilisateur trouvé. Commencez par en ajouter un.</p>
            </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100 bg-white">
                        <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">Nom complet</th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">Email</th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest w-40">Rôle</th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">Date de création</th>
                        <th class="px-6 py-4 text-right text-[11px] font-bold text-gray-400 uppercase tracking-widest w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50/80">
                    @foreach($utilisateurs as $user)
                    @php
                        // Initials
                        $names = explode(' ', $user->name);
                        $initials = '';
                        foreach ($names as $n) { $initials .= substr($n, 0, 1); }
                        $initials = strtoupper(substr($initials, 0, 2));

                        // Colors
                        switch($user->role) {
                            case 'admin':
                                $avatarClass = 'bg-[#d1fae5] text-[#059669]';
                                $badgeClass = 'bg-[#d1fae5] text-[#059669]';
                                break;
                            case 'responsible':
                                $avatarClass = 'bg-blue-100 text-blue-600';
                                $badgeClass = 'bg-blue-100 text-blue-600';
                                break;
                            default:
                                $avatarClass = 'bg-gray-100 text-gray-600';
                                $badgeClass = 'bg-gray-100 text-gray-600';
                                break;
                        }
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <!-- Nom complet -->
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 font-bold text-xs {{ $avatarClass }}">
                                    {{ $initials }}
                                </div>
                                <p class="font-bold text-gray-900 text-[14px]">{{ $user->name }}</p>
                            </div>
                        </td>

                        <!-- Email -->
                        <td class="px-6 py-5">
                            <p class="text-[14px] text-gray-500 font-medium">{{ $user->email }}</p>
                        </td>

                        <!-- Rôle -->
                        <td class="px-6 py-5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $badgeClass }}">
                                {{ $user->role }}
                            </span>
                        </td>

                        <!-- Date de création -->
                        <td class="px-6 py-5">
                            <p class="text-[14px] text-gray-500 font-medium">{{ $user->created_at->format('d/m/Y') }}</p>
                        </td>

                        <!-- Actions (Edit / Delete Icons permanently visible) -->
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-3 transition-opacity">
                                <a href="{{ route('admin.utilisateurs.edit', $user) }}" class="text-gray-400 hover:text-[#00c950] transition-colors" title="Modifier">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                                </a>
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.utilisateurs.destroy', $user) }}" onsubmit="return confirm('Confirmer la suppression de cet utilisateur ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Supprimer">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Footer / Pagination Area -->
        @if($utilisateurs->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-[13px] text-gray-500 font-medium">
                Affichage de <span class="font-bold text-gray-900">{{ $utilisateurs->firstItem() ?? 0 }} à {{ $utilisateurs->lastItem() ?? 0 }}</span> sur <span class="font-bold text-gray-900">{{ $utilisateurs->total() }}</span> utilisateurs
            </p>
            <div class="flex items-center gap-1.5">
                <!-- Use actual pagination links but styled -->
                @if ($utilisateurs->onFirstPage())
                    <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-400 disabled:opacity-50" disabled>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                @else
                    <a href="{{ $utilisateurs->previousPageUrl() }}" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                @endif

                <!-- We just show current page directly for mockup simplicity -->
                <button class="w-8 h-8 rounded-lg bg-[#00c950] text-white font-bold text-sm flex items-center justify-center shadow-sm">{{ $utilisateurs->currentPage() }}</button>

                @if ($utilisateurs->hasMorePages())
                    <a href="{{ $utilisateurs->nextPageUrl() }}" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @else
                    <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-400 disabled:opacity-50" disabled>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                @endif
            </div>
        </div>
        @else
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
             <p class="text-[13px] text-gray-500 font-medium">
                Affichage de <span class="font-bold text-gray-900">1 à {{ $utilisateurs->count() }}</span> sur <span class="font-bold text-gray-900">{{ $utilisateurs->total() }}</span> utilisateurs
            </p>
        </div>
        @endif
        @endif
    </div>
</div>
@endsection
