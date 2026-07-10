<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Commune;

class CommuneSeeder extends Seeder
{
    public function run(): void
    {
        $communes = [
            ['nom' => 'Cotonou'],
            ['nom' => 'Porto-Novo'],
            ['nom' => 'Parakou'],
        ];

        foreach ($communes as $commune) {
            Commune::create($commune);
        }
    }
}