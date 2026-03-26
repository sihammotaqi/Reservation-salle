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
            })->exists();
    }

    public function create($data)
    {
        if ($this->checkConflict(
            $data['salle_id'],
            $data['date_debut'],
            $data['date_fin']
        )) {
            throw new \Exception('Conflit détecté');
        }

        return Planning::create($data);
    }
}