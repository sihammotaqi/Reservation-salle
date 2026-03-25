<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planning extends Model
{
    use HasFactory;

    // Status Constants
    public const STATUT_EN_ATTENTE = 'en_attente';
    public const STATUT_APPROUVE   = 'approuve';
    public const STATUT_REJETE     = 'rejete';
    public const STATUT_ANNULE     = 'annule';

    protected $fillable = ['salle_id', 'user_id', 'titre', 'date_debut', 'date_fin', 'statut', 'type_evenement'];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin'   => 'datetime',
    ];

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: réservations actives (bloquent les créneaux)
     */
    public function scopeActives($query)
    {
        return $query->whereIn('statut', [self::STATUT_EN_ATTENTE, self::STATUT_APPROUVE]);
    }
}
