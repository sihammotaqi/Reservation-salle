@extends('layouts.admin')

@section('title', 'Historique des réservations')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">Historique des réservations</h1>
</div>

<div class="bg-white rounded-xl shadow border p-4">
    <table class="w-full text-sm">
        <thead class="text-gray-500 border-b">
            <tr>
                <th class="text-left py-2">Salle</th>
                <th class="text-left py-2">Utilisateur</th>
                <th class="text-left py-2">Date</th>
                <th class="text-left py-2">Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($plannings as $p)
                <tr class="border-b">
                    <td class="py-2">{{ $p->salle->nom }}</td>
                    <td class="py-2">{{ $p->user->name }}</td>
                    <td class="py-2">
                        {{ $p->date_debut }} → {{ $p->date_fin }}
                    </td>
                    <td class="py-2">
                        {{ $p->statut }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $plannings->links() }}
    </div>
</div>
@endsection