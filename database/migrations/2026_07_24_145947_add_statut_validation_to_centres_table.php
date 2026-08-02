<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('centres', function (Blueprint $table) {
            $table->enum('statut_validation', ['en_attente', 'valide', 'refuse'])
                  ->default('en_attente')
                  ->after('statut');
        });
    }

    public function down(): void
    {
        Schema::table('centres', function (Blueprint $table) {
            $table->dropColumn('statut_validation');
        });
    }
};