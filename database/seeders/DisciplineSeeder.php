<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Discipline;

class DisciplineSeeder extends Seeder
{
    public function run(): void
    {
        $disciplines = [
            ['nom' => 'Football', 'description' => 'Sport collectif avec ballon rond'],
            ['nom' => 'Basketball', 'description' => 'Sport collectif avec ballon et panier'],
            ['nom' => 'Judo', 'description' => 'Art martial japonais'],
            ['nom' => 'Natation', 'description' => 'Sport aquatique'],
            ['nom' => 'Tennis', 'description' => 'Sport de raquette'],
        ];

        foreach ($disciplines as $discipline) {
            Discipline::create($discipline);
        }
    }
}