<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    use HasFactory;

    protected $table = 'disciplines';
    protected $primaryKey = 'id_discipline';

    protected $fillable = [
        'nom',
        'description',
    ];

    // Relations
    public function centres()
    {
        return $this->belongsToMany(Centre::class, 'centre_discipline', 'id_discipline', 'id_centre');
    }
}