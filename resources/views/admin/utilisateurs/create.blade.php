@extends('layouts.admin')

@section('title', 'Ajouter un utilisateur')

@section('content')
<div class="max-w-xl mx-auto mt-10">
    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 p-8 sm:p-10">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-[22px] font-bold text-gray-900 tracking-tight">Ajouter un utilisateur</h1>
            <p class="text-[14px] text-gray-500 mt-1.5">Veuillez renseigner les informations pour créer un nouvel accès.</p>
        </div>

        <form method="POST" action="{{ route('admin.utilisateurs.store') }}" class="space-y-6">
            @csrf

            <!-- Nom complet -->
            <div>
                <label class="block text-[13px] font-bold text-gray-700 mb-2">Nom complet</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full pl-11 pr-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#00c950] focus:border-[#00c950] outline-none text-[14px] transition-all"
                        placeholder="Ex: Jalil Dalil">
                </div>
                @error('name') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-[13px] font-bold text-gray-700 mb-2">Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                    </div>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full pl-11 pr-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#00c950] focus:border-[#00c950] outline-none text-[14px] transition-all"
                        placeholder="jalildalil@email.com">
                </div>
                @error('email') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <!-- Rôle -->
            <div>
                <label class="block text-[13px] font-bold text-gray-700 mb-2">Rôle</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <select name="role" required
                        class="w-full pl-11 pr-10 py-3 bg-gray-50/50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#00c950] focus:border-[#00c950] outline-none text-[14px] transition-all appearance-none cursor-pointer">
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Utilisateur</option>
                        <option value="responsible" {{ old('role') == 'responsible' ? 'selected' : '' }}>Responsable</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    <svg class="w-4 h-4 text-gray-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
                @error('role') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <!-- Footer Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 mt-2">
                <a href="{{ route('admin.utilisateurs.index') }}" class="px-5 py-2.5 text-[13px] font-semibold text-gray-600 hover:text-gray-900 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-2.5 bg-[#00c950] hover:bg-[#00b046] text-white font-bold text-[13px] rounded-lg transition-all shadow-sm focus:ring-4 focus:ring-[#00c950]/20 outline-none">
                    Enregistrer l'utilisateur
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
