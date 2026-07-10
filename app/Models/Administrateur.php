<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable; 

class Administrateur extends Model
{
    use HasFactory;
    use HasApiTokens;
    use Notifiable;

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
    ];

    // Relations
    public function centres()
    {
        return $this->hasMany(Centre::class, 'id_administrateur');
    }
}