<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('centre_categorie_age', function (Blueprint $table) {
            $table->unsignedBigInteger('id_centre');
            $table->unsignedBigInteger('id_categorie_age');
            $table->primary(['id_centre', 'id_categorie_age']);
            $table->foreign('id_centre')->references('id_centre')->on('centres')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_categorie_age')->references('id_categorie_age')->on('categorie_ages')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('centre_categorie_age');
    }
};