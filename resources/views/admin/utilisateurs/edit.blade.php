@extends('layouts.admin')

@section('title', 'Modifier utilisateur')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.utilisateurs.index') }}" class="text-sm text-green-600 hover:underline">&larr; Retour aux utilisateurs</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Modifier : {{ $utilisateur->name }}</h1>
    </div>
    <div class="bg-white rounded-2xl shadow border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.utilisateurs.update', $utilisateur) }}" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nom *</label>
                <input type="text" name="name" value="{{ old('name', $utilisateur->name) }}" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email *</label>
                <input type="email" name="email" value="{{ old('email', $utilisateur->email) }}" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Rôle *</label>
                <select name="role" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm bg-white">
                    <option value="user" {{ $utilisateur->role === 'user' ? 'selected' : '' }}>Utilisateur</option>
                    <option value="responsible" {{ $utilisateur->role === 'responsible' ? 'selected' : '' }}>Responsable</option>
                    <option value="admin" {{ $utilisateur->role === 'admin' ? 'selected' : '' }}>Administrateur</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold text-sm rounded-xl transition-colors shadow">Enregistrer</button>
                <a href="{{ route('admin.utilisateurs.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl transition-colors">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
