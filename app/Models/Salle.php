<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'capacite', 'localisation', 'description', 'disponible'];

    protected $casts = [
        'disponible' => 'boolean',
    ];

    public function equipements()
    {
        return $this->belongsToMany(Equipement::class)
                    ->withPivot('quantite')
                    ->withTimestamps();
    }



    public function plannings()
    {
        return $this->hasMany(Planning::class);
    }
}
