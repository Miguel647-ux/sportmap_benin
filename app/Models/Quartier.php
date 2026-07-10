<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quartier extends Model
{
    use HasFactory;

    protected $table = 'quartiers';
    protected $primaryKey = 'id_quartier';

    protected $fillable = [
        'nom',
        'id_commune',
    ];

    // Relations
    public function commune()
    {
        return $this->belongsTo(Commune::class, 'id_commune');
    }

    public function centres()
    {
        return $this->hasMany(Centre::class, 'id_quartier');
    }
}