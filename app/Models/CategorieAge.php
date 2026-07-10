<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieAge extends Model
{
    use HasFactory;

    protected $table = 'categorie_ages';
    protected $primaryKey = 'id_categorie_age';

    protected $fillable = [
        'libelle',
        'age_min',
        'age_max',
    ];

    // Relations
    public function centres()
    {
        return $this->belongsToMany(Centre::class, 'centre_categorie_age', 'id_categorie_age', 'id_centre');
    }
}