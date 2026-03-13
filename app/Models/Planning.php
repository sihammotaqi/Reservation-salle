<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planning extends Model
{
    use HasFactory;

    protected $fillable = ['salle_id', 'user_id', 'titre', 'date_debut', 'date_fin', 'statut'];

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
}
