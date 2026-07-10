<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('centres', function (Blueprint $table) {
            $table->id('id_centre');
            $table->string('nom', 150);
            $table->text('description')->nullable();
            $table->string('adresse', 255)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('telephone', 20)->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('horaires', 255)->nullable();
            $table->enum('statut', ['brouillon', 'publie'])->default('brouillon');
            $table->timestamp('date_creation')->useCurrent();
            $table->unsignedBigInteger('id_administrateur');
            $table->unsignedBigInteger('id_quartier');
            $table->foreign('id_administrateur')->references('id_administrateur')->on('administrateurs')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('id_quartier')->references('id_quartier')->on('quartiers')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();

            $table->index('statut');
            $table->index('id_quartier');
            $table->index('id_administrateur');
            $table->index(['latitude', 'longitude']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('centres');
    }
};