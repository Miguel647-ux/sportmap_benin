<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    use HasFactory;

    protected $table = 'communes';
    protected $primaryKey = 'id_commune';

    protected $fillable = [
        'nom',
    ];

    // Relations
    public function quartiers()
    {
        return $this->hasMany(Quartier::class, 'id_commune');
    }
}