@extends('layouts.admin')

@section('title', 'Gestion des Équipements')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Gestion des Équipements</h1>
            <p class="text-[15px] text-gray-500 mt-2 max-w-2xl leading-relaxed">
                Gérez la liste de l'équipement disponible dans votre établissement.
            </p>
        </div>
        <div class="flex items-center gap-4 shrink-0 mt-2 md:mt-0">
            <!-- Add Button (Triggers Modal) -->
            <button onclick="openAddModal()"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#00c950] hover:bg-[#00b046] text-white text-sm font-bold rounded-lg shadow-sm transition-all shadow-[#00c950]/20">
                <svg class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Ajouter un équipement
            </button>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100/80 overflow-hidden">
        @if($equipements->isEmpty())
            <div class="text-center py-20 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-4 opacity-40 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <p class="text-[15px] font-medium text-gray-500">Aucun équipement enregistré.</p>
            </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100 bg-white">
                        <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest w-1/3">Nom de l'équipement</th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest w-32">Qté Totale</th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">Description</th>
                        <th class="px-6 py-4 text-right text-[11px] font-bold text-gray-400 uppercase tracking-widest w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50/80">
                    @foreach($equipements as $eq)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <!-- Nom -->
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 bg-gray-50 border border-gray-100/50 text-gray-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                                </div>
                                <p class="font-bold text-gray-900 text-[14px]">{{ $eq->nom }}</p>
                            </div>
                        </td>

                        <!-- Quantité -->
                        <td class="px-6 py-5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-gray-50 text-gray-700 border border-gray-200/60 shadow-sm">
                                {{ $eq->quantite }} Unité(s)
                            </span>
                        </td>

                        <!-- Description -->
                        <td class="px-6 py-5">
                            <p class="text-[14px] text-gray-500 font-medium line-clamp-2">{{ $eq->description ?: '--' }}</p>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-3 transition-opacity">
                                <button onclick="openEditModal({{ $eq->id }}, '{{ addslashes($eq->nom) }}', {{ $eq->quantite }}, '{{ addslashes($eq->description) }}')" class="text-gray-400 hover:text-[#00c950] transition-colors" title="Modifier">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                                </button>
                                <form method="POST" action="{{ route('admin.equipements.destroy', $eq) }}" onsubmit="return confirm('Confirmer la suppression de cet équipement ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Supprimer">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.08)] border border-gray-100 p-8 w-full max-w-lg relative animate-[fadeIn_0.2s_ease-out]">
        <button onclick="closeAddModal()" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <div class="mb-6 flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-[#00c950]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            </div>
            <div>
                <h2 class="text-[18px] font-bold text-gray-900 tracking-tight">Ajouter un équipement</h2>
                <p class="text-[13px] text-gray-500 mt-0.5">Renseignez les informations de base de l'équipement.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.equipements.store') }}" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-[13px] font-bold text-gray-700 mb-2">Nom de l'équipement</label>
                    <div class="relative">
                        <input type="text" name="nom" required
                            class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#00c950] focus:border-[#00c950] outline-none text-[14px] transition-all placeholder-gray-400"
                            placeholder="Ex: Projecteur 4K Sony">
                    </div>
                </div>

                <div>
                    <label class="block text-[13px] font-bold text-gray-700 mb-2">Quantité Totale</label>
                    <div class="relative">
                        <input type="number" name="quantite" required min="1" value="1"
                            class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#00c950] focus:border-[#00c950] outline-none text-[14px] transition-all">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-[13px] font-bold text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#00c950] focus:border-[#00c950] outline-none text-[14px] transition-all placeholder-gray-400 resize-none"
                    placeholder="Brève description de l'équipement (optionnel)"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeAddModal()" class="px-5 py-2.5 text-[13px] font-semibold text-gray-600 hover:text-gray-900 transition-colors">
                    Annuler
                </button>
                <button type="submit" class="px-6 py-2.5 bg-[#00c950] hover:bg-[#00b046] text-white font-bold text-[13px] rounded-lg transition-all shadow-sm focus:ring-4 focus:ring-[#00c950]/20 outline-none">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.08)] border border-gray-100 p-8 w-full max-w-lg relative">
        <button onclick="closeEditModal()" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <div class="mb-6 flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            </div>
            <div>
                <h2 class="text-[18px] font-bold text-gray-900 tracking-tight">Modifier l'équipement</h2>
                <p class="text-[13px] text-gray-500 mt-0.5">Mettez à jour les informations.</p>
            </div>
        </div>

        <form id="editForm" method="POST" action="" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-[13px] font-bold text-gray-700 mb-2">Nom de l'équipement</label>
                    <div class="relative">
                        <input type="text" id="editNom" name="nom" required
                            class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#00c950] focus:border-[#00c950] outline-none text-[14px] transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-[13px] font-bold text-gray-700 mb-2">Quantité Totale</label>
                    <div class="relative">
                        <input type="number" id="editQuantite" name="quantite" required min="0"
                            class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#00c950] focus:border-[#00c950] outline-none text-[14px] transition-all">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-[13px] font-bold text-gray-700 mb-2">Description</label>
                <textarea id="editDescription" name="description" rows="3"
                    class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#00c950] focus:border-[#00c950] outline-none text-[14px] transition-all resize-none"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 text-[13px] font-semibold text-gray-600 hover:text-gray-900 transition-colors">
                    Annuler
                </button>
                <button type="submit" class="px-6 py-2.5 bg-[#00c950] hover:bg-[#00b046] text-white font-bold text-[13px] rounded-lg transition-all shadow-sm focus:ring-4 focus:ring-[#00c950]/20 outline-none">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95) translateY(10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
</style>

<script>
    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
    }
    
    function closeAddModal() {
        document.getElementById('addModal').classList.add('hidden');
    }

    function openEditModal(id, nom, quantite, description) {
        document.getElementById('editForm').action = `/admin/equipements/${id}`;
        document.getElementById('editNom').value = nom;
        document.getElementById('editQuantite').value = quantite;
        document.getElementById('editDescription').value = description;
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
@endsection
