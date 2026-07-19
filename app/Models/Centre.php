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
        'commune',
        'quartier',
        'latitude',
        'longitude',
        'telephone',
        'whatsapp',
        'email',
        'horaires',
        'logo',
        'photos',
        'statut',
        'date_creation',
        'id_administrateur',
    ];

    protected $casts = [
        'date_creation' => 'datetime',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'photos' => 'array',
    ];

    public function administrateur()
    {
        return $this->belongsTo(Administrateur::class, 'id_administrateur');
    }

    public function disciplines()
    {
        return $this->belongsToMany(Discipline::class, 'centre_discipline', 'id_centre', 'id_discipline');
    }

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

    public function getLogoUrlAttribute()
    {
        return $this->logo ?? null;
    }

    public function getPhotosListAttribute()
    {
        return $this->photos ?? [];
    }
}