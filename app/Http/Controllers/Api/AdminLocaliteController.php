<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCommuneRequest;
use App\Http\Requests\Admin\StoreQuartierRequest;
use App\Models\Commune;
use App\Models\Quartier;

class AdminLocaliteController extends Controller
{
    // ========== COMMUNES ==========

   public function indexCommunes(Request $request)
{
    $perPage = $request->per_page ?? 15;
    $communes = Commune::with('quartiers')->orderBy('nom')->paginate($perPage);
    return response()->json($communes);
}
    public function storeCommune(StoreCommuneRequest $request)
    {
        $commune = Commune::create($request->validated());

        return response()->json([
            'message' => 'Commune créée avec succès',
            'commune' => $commune,
        ], 201);
    }

    public function showCommune(Commune $commune)
    {
        return response()->json($commune->load('quartiers'));
    }

    public function updateCommune(StoreCommuneRequest $request, Commune $commune)
    {
        $commune->update($request->validated());

        return response()->json([
            'message' => 'Commune mise à jour avec succès',
            'commune' => $commune,
        ]);
    }

    public function destroyCommune(Commune $commune)
    {
        if ($commune->quartiers()->count() > 0) {
            return response()->json([
                'message' => 'Impossible de supprimer cette commune car elle contient des quartiers.',
            ], 422);
        }

        $commune->delete();

        return response()->json(['message' => 'Commune supprimée avec succès']);
    }

    // ========== QUARTIERS ==========

   public function indexQuartiers(Request $request)
{
    $perPage = $request->per_page ?? 15;
    $quartiers = Quartier::with('commune')->orderBy('nom')->paginate($perPage);
    return response()->json($quartiers);
}
    public function storeQuartier(StoreQuartierRequest $request)
    {
        $quartier = Quartier::create($request->validated());

        return response()->json([
            'message' => 'Quartier créé avec succès',
            'quartier' => $quartier->load('commune'),
        ], 201);
    }

    public function showQuartier(Quartier $quartier)
    {
        return response()->json($quartier->load('commune'));
    }

    public function updateQuartier(StoreQuartierRequest $request, Quartier $quartier)
    {
        $quartier->update($request->validated());

        return response()->json([
            'message' => 'Quartier mis à jour avec succès',
            'quartier' => $quartier->load('commune'),
        ]);
    }

    public function destroyQuartier(Quartier $quartier)
    {
        if ($quartier->centres()->count() > 0) {
            return response()->json([
                'message' => 'Impossible de supprimer ce quartier car il contient des centres.',
            ], 422);
        }

        $quartier->delete();

        return response()->json(['message' => 'Quartier supprimé avec succès']);
    }
}