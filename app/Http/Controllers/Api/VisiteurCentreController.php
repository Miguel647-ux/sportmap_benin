<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Centre;
use App\Models\Discipline;
use Illuminate\Http\Request;

class VisiteurCentreController extends Controller
{
    public function index(Request $request)
    {
        $query = Centre::with(['disciplines'])
            ->where('statut', 'publie');

        // Filtre par discipline
        if ($request->has('id_discipline') && $request->id_discipline) {
            $query->whereHas('disciplines', function ($q) use ($request) {
                $q->where('disciplines.id_discipline', $request->id_discipline);
            });
        }

        // Filtre par commune (texte)
        if ($request->has('commune') && $request->commune) {
            $query->where('commune', 'LIKE', '%' . $request->commune . '%');
        }

        // Filtre par quartier (texte)
        if ($request->has('quartier') && $request->quartier) {
            $query->where('quartier', 'LIKE', '%' . $request->quartier . '%');
        }

        // Recherche par nom
        if ($request->has('search') && $request->search) {
            $query->where('nom', 'LIKE', '%' . $request->search . '%');
        }

        // Rayon de 5km (géolocalisation)
        if ($request->has('latitude') && $request->has('longitude') && $request->latitude && $request->longitude) {
            $lat = $request->latitude;
            $lng = $request->longitude;
            $rayon = $request->rayon ?? 5;

            $query->whereRaw(
                "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) < ?",
                [$lat, $lng, $lat, $rayon]
            );
        }

        $perPage = $request->per_page ?? 15;
        $centres = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($centres);
    }

    public function show(Centre $centre)
    {
        if ($centre->statut !== 'publie') {
            return response()->json(['message' => 'Centre non trouvé'], 404);
        }

         // Incrémenter le compteur de vues
    $centre->increment('nombre_vues');

    return response()->json($centre->load(['disciplines']));

        return response()->json($centre->load(['disciplines']));
    }

    public function disciplines()
    {
        return response()->json(Discipline::orderBy('nom')->get());
    }
}