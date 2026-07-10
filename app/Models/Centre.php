<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Centre extends Model
{
    use HasFactory;

    protected $table = 'centres';
    protected $primaryKey = 'id_centre';

    protected $fillable = [
        'nom',
        'description',
        'adresse',
        'latitude',
        'longitude',
        'telephone',
        'whatsapp',
        'email',
        'horaires',
        'statut',
        'date_creation',
        'id_administrateur',
        'id_quartier',
    ];

    protected $casts = [
        'date_creation' => 'datetime',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    // Relations
    public function administrateur()
    {
        return $this->belongsTo(Administrateur::class, 'id_administrateur');
    }

    public function quartier()
    {
        return $this->belongsTo(Quartier::class, 'id_quartier');
    }

    public function disciplines()
    {
        return $this->belongsToMany(Discipline::class, 'centre_discipline', 'id_centre', 'id_discipline');
    }

    public function categorieAges()
    {
        return $this->belongsToMany(CategorieAge::class, 'centre_categorie_age', 'id_centre', 'id_categorie_age');
    }

    public function photos()
    {
        return $this->hasMany(Photo::class, 'id_centre');
    }

    // Méthodes utiles
    public function publier()
    {
        $this->update(['statut' => 'publie']);
    }

    public function depublier()
    {
        $this->update(['statut' => 'brouillon']);
    }

    public function estVisible()
    {
        return $this->statut === 'publie';
    }

    public function getLogoAttribute()
    {
        return $this->photos()->where('type', 'logo')->first();
    }

    public function getPhotosGalerieAttribute()
    {
        return $this->photos()->where('type', 'photo')->get();
    }
}