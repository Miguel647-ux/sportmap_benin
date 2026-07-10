<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Centre;
use App\Models\Administrateur;
use App\Models\Quartier;
use App\Models\Discipline;
use App\Models\CategorieAge;

class CentreSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = Administrateur::first()->id_administrateur;

        $centres = [
            [
                'nom' => 'Centre de Football de Fidjrossè',
                'description' => 'Centre de formation en football pour jeunes',
                'adresse' => 'Fidjrossè, Cotonou',
                'latitude' => 6.3600,
                'longitude' => 2.3900,
                'telephone' => '+229 97000001',
                'whatsapp' => '+229 97000001',
                'email' => 'contact@foot-fidjrosse.bj',
                'horaires' => 'Lun-Sam 08:00-18:00',
                'statut' => 'publie',
                'id_administrateur' => $adminId,
                'id_quartier' => $this->getQuartierId('Fidjrossè'),
            ],
            [
                'nom' => 'Académie de Judo d\'Akpakpa',
                'description' => 'Académie de judo pour enfants et adultes',
                'adresse' => 'Akpakpa, Cotonou',
                'latitude' => 6.3700,
                'longitude' => 2.4000,
                'telephone' => '+229 97000002',
                'whatsapp' => '+229 97000002',
                'email' => 'contact@judo-akpakpa.bj',
                'horaires' => 'Mar-Jeu 14:00-20:00',
                'statut' => 'publie',
                'id_administrateur' => $adminId,
                'id_quartier' => $this->getQuartierId('Akpakpa'),
            ],
            [
                'nom' => 'Complexe Sportif de Calavi',
                'description' => 'Centre multisports à Calavi',
                'adresse' => 'Calavi, Cotonou',
                'latitude' => 6.4400,
                'longitude' => 2.3500,
                'telephone' => '+229 97000003',
                'whatsapp' => '+229 97000003',
                'email' => 'contact@calavi-sport.bj',
                'horaires' => 'Lun-Dim 06:00-21:00',
                'statut' => 'brouillon',
                'id_administrateur' => $adminId,
                'id_quartier' => $this->getQuartierId('Calavi'),
            ],
        ];

        foreach ($centres as $centreData) {
            $centre = Centre::create($centreData);

            // Ajouter les disciplines (au moins 1)
            $disciplines = Discipline::inRandomOrder()->take(rand(1, 3))->get();
            $centre->disciplines()->attach($disciplines->pluck('id_discipline'));

            // Ajouter les catégories d'âge
            $categories = CategorieAge::inRandomOrder()->take(rand(1, 2))->get();
            $centre->categorieAges()->attach($categories->pluck('id_categorie_age'));
        }
    }

    private function getQuartierId($nom)
    {
        return Quartier::where('nom', $nom)->first()->id_quartier;
    }
}