@extends(Auth::user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('title', 'Mon Profil')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Mon Profil</h1>
        <p class="text-sm text-gray-500 mt-1">Mettez à jour vos informations personnelles.</p>
    </div>

    <!-- Avatar Card -->
    <div class="bg-white rounded-2xl shadow border border-gray-100 p-6 mb-6 flex items-center gap-5">
        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white text-2xl font-bold shadow-md">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div>
            <p class="text-lg font-bold text-gray-900">{{ $user->name }}</p>
            <p class="text-sm text-gray-500">{{ $user->email }}</p>
            <span class="inline-flex items-center mt-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700 capitalize">{{ $user->role }}</span>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-2xl shadow border border-gray-100 p-6">
        <h2 class="text-base font-bold text-gray-800 mb-5">Modifier mes informations</h2>
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nom complet *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Adresse e-mail *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <hr class="border-gray-100">

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nouveau mot de passe <span class="font-normal text-gray-400">(laisser vide pour ne pas changer)</span></label>
                <div class="relative">
                    <input id="password" type="password" name="password"
                        class="w-full px-4 py-2.5 pr-10 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm"
                        placeholder="••••••••">
                    <button type="button" onclick="togglePassword('password', 'eye1')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-green-500">
                        <svg id="eye1" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirmer le mot de passe</label>
                <div class="relative">
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        class="w-full px-4 py-2.5 pr-10 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm"
                        placeholder="••••••••">
                    <button type="button" onclick="togglePassword('password_confirmation', 'eye2')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-green-500">
                        <svg id="eye2" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold text-sm rounded-xl transition-colors shadow">
                    Enregistrer les modifications
                </button>
                <a href="{{ route('dashboard') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
        } else {
            input.type = 'password';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
        }
    }
</script>
@endsection
