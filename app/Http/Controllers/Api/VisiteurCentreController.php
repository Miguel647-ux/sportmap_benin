<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Centre;
use App\Models\Discipline;
use App\Models\CategorieAge;
use Illuminate\Http\Request;

class VisiteurCentreController extends Controller
{
    public function index(Request $request)
    {
        $query = Centre::with(['quartier.commune', 'disciplines', 'categorieAges', 'photos'])
            ->where('statut', 'publie');

        // Filtre par discipline
        if ($request->has('id_discipline') && $request->id_discipline) {
            $query->whereHas('disciplines', function ($q) use ($request) {
                $q->where('disciplines.id_discipline', $request->id_discipline);
            });
        }

        // Filtre par catégorie d'âge
        if ($request->has('id_categorie_age') && $request->id_categorie_age) {
            $query->whereHas('categorieAges', function ($q) use ($request) {
                $q->where('categorie_ages.id_categorie_age', $request->id_categorie_age);
            });
        }

        // Filtre par commune
        if ($request->has('id_commune') && $request->id_commune) {
            $query->whereHas('quartier', function ($q) use ($request) {
                $q->where('id_commune', $request->id_commune);
            });
        }

        // Filtre par quartier
        if ($request->has('id_quartier') && $request->id_quartier) {
            $query->where('id_quartier', $request->id_quartier);
        }

        // Recherche par nom
        if ($request->has('search') && $request->search) {
            $query->where('nom', 'LIKE', '%' . $request->search . '%');
        }

        // Rayon de 5km (géolocalisation)
        if ($request->has('latitude') && $request->has('longitude') && $request->latitude && $request->longitude) {
            $lat = $request->latitude;
            $lng = $request->longitude;
            $rayon = $request->rayon ?? 5; // 5km par défaut

            $query->whereRaw(
                "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) < ?",
                [$lat, $lng, $lat, $rayon]
            );
        }

         // PAGINATION 
    $perPage = $request->per_page ?? 15;
    $centres = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($centres);
    }

    public function show(Centre $centre)
    {
        if ($centre->statut !== 'publie') {
            return response()->json(['message' => 'Centre non trouvé'], 404);
        }

        return response()->json($centre->load(['quartier.commune', 'disciplines', 'categorieAges', 'photos']));
    }

    public function disciplines()
    {
        return response()->json(Discipline::orderBy('nom')->get());
    }

    public function categorieAges()
    {
        return response()->json(CategorieAge::orderBy('age_min')->get());
    }
}