<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Centre;
use App\Models\Administrateur;
use App\Models\Discipline;

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
                'commune' => 'Cotonou',
                'quartier' => 'Fidjrossè',
                'latitude' => 6.3600,
                'longitude' => 2.3900,
                'telephone' => '+229 97000001',
                'whatsapp' => '+229 97000001',
                'email' => 'contact@foot-fidjrosse.bj',
                'horaires' => 'Lun-Sam 08:00-18:00',
                'logo' => null,
                'photos' => null,
                'statut' => 'publie',
                'id_administrateur' => $adminId,
            ],
            [
                'nom' => 'Académie de Judo d\'Akpakpa',
                'description' => 'Académie de judo pour enfants et adultes',
                'adresse' => 'Akpakpa, Cotonou',
                'commune' => 'Cotonou',
                'quartier' => 'Akpakpa',
                'latitude' => 6.3700,
                'longitude' => 2.4000,
                'telephone' => '+229 97000002',
                'whatsapp' => '+229 97000002',
                'email' => 'contact@judo-akpakpa.bj',
                'horaires' => 'Mar-Jeu 14:00-20:00',
                'logo' => null,
                'photos' => null,
                'statut' => 'publie',
                'id_administrateur' => $adminId,
            ],
            [
                'nom' => 'Complexe Sportif d\'Adjarra',
                'description' => 'Centre multisports à Porto',
                'adresse' => 'Adjarra, Porto-Novo',
                'commune' => 'Porto-Novo',
                'quartier' => 'Adjarra',
                'latitude' => 6.4400,
                'longitude' => 2.3500,
                'telephone' => '+229 97000003',
                'whatsapp' => '+229 97000003',
                'email' => 'contact@calavi-sport.bj',
                'horaires' => 'Lun-Dim 06:00-21:00',
                'logo' => null,
                'photos' => null,
                'statut' => 'brouillon',
                'id_administrateur' => $adminId,
            ],
        ];

        foreach ($centres as $centreData) {
            $centre = Centre::create($centreData);

            // Ajouter les disciplines (au moins 1)
            $disciplines = Discipline::inRandomOrder()->take(rand(1, 3))->get();
            $centre->disciplines()->attach($disciplines->pluck('id_discipline'));
        }
    }
}