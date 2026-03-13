@extends('layouts.admin')

@section('title', 'Utilisateurs')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Utilisateurs</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $utilisateurs->count() }} utilisateur(s) enregistré(s).</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow border border-gray-100 overflow-hidden">
    @if($utilisateurs->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <p class="text-sm font-medium">Aucun utilisateur trouvé.</p>
        </div>
    @else
    <table class="min-w-full divide-y divide-gray-100">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Utilisateur</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Rôle</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Inscrit le</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($utilisateurs as $user)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white font-bold text-sm">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <p class="font-semibold text-gray-900 text-sm">{{ $user->name }}</p>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                <td class="px-6 py-4">
                    @php
                        $colors = ['admin' => 'bg-purple-100 text-purple-700', 'responsible' => 'bg-blue-100 text-blue-700', 'user' => 'bg-gray-100 text-gray-700'];
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $colors[$user->role] ?? 'bg-gray-100 text-gray-700' }} capitalize">
                        {{ $user->role }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $user->created_at->format('d/m/Y') }}</td>
                <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                    <a href="{{ route('admin.utilisateurs.edit', $user) }}" class="text-sm text-green-600 hover:text-green-800 font-medium">Modifier</a>
                    @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.utilisateurs.destroy', $user) }}" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-medium">Supprimer</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
