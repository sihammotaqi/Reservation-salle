<?php

namespace App\Services;

use App\Models\Planning;

class ReservationService
{
    public function checkConflict($salleId, $start, $end)
    {
        return Planning::where('salle_id', $salleId)
            ->whereIn('statut', ['en_attente', 'approuve'])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('date_debut', [$start, $end])
                  ->orWhereBetween('date_fin', [$start, $end])
                  ->orWhere(function ($q) use ($start, $end) {
                      $q->where('date_debut', '<=', $start)
                        ->where('date_fin', '>=', $end);
                  });
            })
            ->first(); // ❗ بدل exists
    }

    public function create($data)
    {
        $conflict = $this->checkConflict(
            $data['salle_id'],
            $data['date_debut'],
            $data['date_fin']
        );

        if ($conflict) {
            throw new \Exception(
                'Conflit avec "' . $conflict->titre . '" de ' .
                $conflict->date_debut->format('H:i') .
                ' à ' .
                $conflict->date_fin->format('H:i')
            );
        }

        return Planning::create([
            'salle_id'       => $data['salle_id'],
            'user_id'        => $data['user_id'],
            'titre'          => $data['titre'],
            'type_evenement' => $data['type_evenement'] ?? null,
            'date_debut'     => $data['date_debut'],
            'date_fin'       => $data['date_fin'],
            'statut'         => $data['statut'] ?? 'en_attente',
        ]);
    }
}