<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Administrateur extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'administrateurs';
    protected $primaryKey = 'id_administrateur';

    protected $fillable = [
        'nom',
        'email',
        'mot_de_passe',
        'date_inscription',
    ];

    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    protected $casts = [
        'date_inscription' => 'datetime',
    ];

    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    public function centres()
    {
        return $this->hasMany(Centre::class, 'id_administrateur');
    }
}