@extends('layouts.admin')

@section('title', 'Modifier l\'utilisateur')

@section('content')
<!-- We use a flex container to center the "modal-like" card -->
<div class="flex items-center justify-center min-h-[70vh]">
    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.08)] border border-gray-100 p-8 w-full max-w-lg relative">
        <!-- Close Button (X) -->
        <a href="{{ route('admin.utilisateurs.index') }}" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </a>

        <!-- Header -->
        <div class="mb-6 flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            </div>
            <div>
                <h1 class="text-[18px] font-bold text-gray-900 tracking-tight">Modifier l'utilisateur</h1>
                <p class="text-[13px] text-gray-500 mt-0.5">Mettre à jour les informations et permissions</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.utilisateurs.update', $utilisateur) }}">
            @csrf
            @method('PUT')

            <!-- Profile Overview (Read-only) -->
            <div class="flex items-center gap-4 p-4 rounded-xl bg-gray-50/80 border border-gray-100 mb-6">
                @php
                    // Extract initials (e.g., "Marwan Charif" -> "MC")
                    $names = explode(' ', $utilisateur->name);
                    $initials = '';
                    foreach ($names as $n) {
                        $initials .= substr($n, 0, 1);
                    }
                    $initials = strtoupper(substr($initials, 0, 2));

                    // Choose colors based on role
                    $roleColors = [
                        'admin' => 'bg-green-100 text-green-600',
                        'responsible' => 'bg-blue-100 text-blue-600',
                        'user' => 'bg-gray-200 text-gray-600',
                    ];
                    $avatarColor = $roleColors[$utilisateur->role] ?? 'bg-gray-200 text-gray-600';
                @endphp
                <div class="w-11 h-11 rounded-full {{ $avatarColor }} flex items-center justify-center font-bold text-sm shrink-0">
                    {{ $initials }}
                </div>
                <div>
                    <p class="font-bold text-[14px] text-gray-900">{{ $utilisateur->name }}</p>
                    <p class="text-[13px] text-gray-500">{{ $utilisateur->email }}</p>
                </div>
            </div>

            <!-- Rôle Dropdown -->
            <div class="mb-8">
                <label class="block text-[13px] font-bold text-gray-700 mb-2">Rôle de l'utilisateur</label>
                <div class="relative">
                    <select name="role" required
                        class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00c950] focus:border-[#00c950] outline-none text-[14px] transition-all appearance-none cursor-pointer text-gray-700 font-medium">
                        <option value="user" {{ old('role', $utilisateur->role) == 'user' ? 'selected' : '' }}>Utilisateur</option>
                        <option value="responsible" {{ old('role', $utilisateur->role) == 'responsible' ? 'selected' : '' }}>Responsable</option>
                        <option value="admin" {{ old('role', $utilisateur->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
                @error('role') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <!-- Footer Buttons -->
            <div class="flex items-center justify-center gap-4">
                <a href="{{ route('admin.utilisateurs.index') }}" class="px-5 py-2 text-[13px] font-semibold text-gray-600 hover:text-gray-900 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="px-5 py-2 bg-[#00c950] hover:bg-[#00b046] text-white font-bold text-[13px] rounded-lg transition-all shadow-sm focus:ring-4 focus:ring-[#00c950]/20 outline-none">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
