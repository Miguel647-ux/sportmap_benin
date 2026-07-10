<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorie_ages', function (Blueprint $table) {
            $table->id('id_categorie_age');
            $table->string('libelle', 100);
            $table->unsignedTinyInteger('age_min');
            $table->unsignedTinyInteger('age_max');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorie_ages');
    }
};