<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $table = 'photos';
    protected $primaryKey = 'id_photo';

    protected $fillable = [
        'url',
        'type',
        'date_ajout',
        'id_centre',
    ];

    protected $casts = [
        'date_ajout' => 'datetime',
    ];

    // Relations
    public function centre()
    {
        return $this->belongsTo(Centre::class, 'id_centre');
    }
}