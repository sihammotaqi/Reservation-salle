<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipement extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description', 'quantite'];

    public function salles()
    {
        return $this->belongsToMany(Salle::class)
                    ->withPivot('quantite')
                    ->withTimestamps();
    }
}
