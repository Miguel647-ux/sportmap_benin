<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('centre_discipline', function (Blueprint $table) {
            $table->unsignedBigInteger('id_centre');
            $table->unsignedBigInteger('id_discipline');
            $table->primary(['id_centre', 'id_discipline']);
            $table->foreign('id_centre')->references('id_centre')->on('centres')->onDelete('cascade');
            $table->foreign('id_discipline')->references('id_discipline')->on('disciplines')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('centre_discipline');
    }
};