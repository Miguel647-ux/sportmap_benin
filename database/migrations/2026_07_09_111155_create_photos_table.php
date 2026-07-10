<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id('id_photo');
            $table->string('url', 255);
            $table->enum('type', ['logo', 'photo']);
            $table->timestamp('date_ajout')->useCurrent();
            $table->unsignedBigInteger('id_centre');
            $table->foreign('id_centre')->references('id_centre')->on('centres')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();

            $table->index('id_centre');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};