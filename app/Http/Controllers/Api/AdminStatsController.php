<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Centre;
use App\Models\Discipline;
use Illuminate\Http\Request;

class AdminStatsController extends Controller
{
    public function index()
    {
        $totalCentres = Centre::count();
        $centresPublies = Centre::where('statut', 'publie')->count();
        $centresBrouillons = Centre::where('statut', 'brouillon')->count();
        $totalDisciplines = Discipline::count();

        // Top 5 des centres les plus vus
        $topCentres = Centre::where('statut', 'publie')
            ->orderBy('nombre_vues', 'desc')
            ->limit(5)
            ->get(['id_centre', 'nom', 'nombre_vues']);

        // Répartition par commune
        $repartitionCommunes = Centre::where('statut', 'publie')
            ->whereNotNull('commune')
            ->selectRaw('commune, count(*) as total')
            ->groupBy('commune')
            ->orderBy('total', 'desc')
            ->get();

        return response()->json([
            'total_centres' => $totalCentres,
            'centres_publies' => $centresPublies,
            'centres_brouillons' => $centresBrouillons,
            'total_disciplines' => $totalDisciplines,
            'top_centres' => $topCentres,
            'repartition_communes' => $repartitionCommunes,
        ]);
    }
}