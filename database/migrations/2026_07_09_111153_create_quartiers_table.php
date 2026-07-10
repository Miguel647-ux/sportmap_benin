<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quartiers', function (Blueprint $table) {
            $table->id('id_quartier');
            $table->string('nom', 100);
            $table->unsignedBigInteger('id_commune');
            $table->foreign('id_commune')->references('id_commune')->on('communes')->onDelete('restrict')->onUpdate('cascade');
            $table->unique(['nom', 'id_commune']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quartiers');
    }
};