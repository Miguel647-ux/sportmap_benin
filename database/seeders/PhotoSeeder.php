<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Photo;
use App\Models\Centre;

class PhotoSeeder extends Seeder
{
    public function run(): void
    {
        $centres = Centre::all();

        foreach ($centres as $centre) {
            // Logo
            Photo::create([
                'url' => 'logos/centre_' . $centre->id_centre . '_logo.jpg',
                'type' => 'logo',
                'id_centre' => $centre->id_centre,
            ]);

            // Photo de galerie
            Photo::create([
                'url' => 'photos/centre_' . $centre->id_centre . '_photo1.jpg',
                'type' => 'photo',
                'id_centre' => $centre->id_centre,
            ]);

            Photo::create([
                'url' => 'photos/centre_' . $centre->id_centre . '_photo2.jpg',
                'type' => 'photo',
                'id_centre' => $centre->id_centre,
            ]);
        }
    }
}