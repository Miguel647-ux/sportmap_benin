<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CommuneSeeder::class,
            QuartierSeeder::class,
            AdministrateurSeeder::class,
            DisciplineSeeder::class,
            CategorieAgeSeeder::class,
            CentreSeeder::class,
            PhotoSeeder::class,
        ]);
    }
}