<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategorieAge;

class CategorieAgeSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['libelle' => 'Mini-poussins', 'age_min' => 6, 'age_max' => 8],
            ['libelle' => 'Poussins', 'age_min' => 9, 'age_max' => 12],
            ['libelle' => 'Benjamins', 'age_min' => 13, 'age_max' => 17],
        ];

        foreach ($categories as $categorie) {
            CategorieAge::create($categorie);
        }
    }
}