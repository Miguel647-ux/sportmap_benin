<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Administrateur;
use Illuminate\Support\Facades\Hash;

class AdministrateurSeeder extends Seeder
{
    public function run(): void
    {
        Administrateur::create([
            'nom' => 'Admin SportMap',
            'email' => 'azifanmiguel647@gmail.com',
            'mot_de_passe' => Hash::make('password123'),
            'date_inscription' => now(),
        ]);
    }
}