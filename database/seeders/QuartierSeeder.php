<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quartier;
use App\Models\Commune;

class QuartierSeeder extends Seeder
{
    public function run(): void
    {
        $quartiers = [
            ['nom' => 'Fidjrossè', 'id_commune' => $this->getCommuneId('Cotonou')],
            ['nom' => 'Akpakpa', 'id_commune' => $this->getCommuneId('Cotonou')],
            ['nom' => 'Calavi', 'id_commune' => $this->getCommuneId('Cotonou')],
            ['nom' => 'Kpota', 'id_commune' => $this->getCommuneId('Porto-Novo')],
            ['nom' => 'Ampère', 'id_commune' => $this->getCommuneId('Porto-Novo')],
            ['nom' => 'Banikanni', 'id_commune' => $this->getCommuneId('Parakou')],
            ['nom' => 'Tchaourou', 'id_commune' => $this->getCommuneId('Parakou')],
        ];

        foreach ($quartiers as $quartier) {
            Quartier::create($quartier);
        }
    }

    private function getCommuneId($nom)
    {
        return Commune::where('nom', $nom)->first()->id_commune;
    }
}