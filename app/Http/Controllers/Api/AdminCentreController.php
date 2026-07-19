<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCentreRequest;
use App\Http\Requests\Admin\UpdateCentreRequest;
use App\Models\Centre;
use Illuminate\Http\Request;

class AdminCentreController extends Controller
{
    public function index(Request $request)
    {
        $query = Centre::with(['disciplines'])
            ->orderBy('created_at', 'desc');

        // Filtre par statut
        if ($request->has('statut') && $request->statut) {
            $query->where('statut', $request->statut);
        }

        // Recherche par nom
        if ($request->has('search') && $request->search) {
            $query->where('nom', 'LIKE', '%' . $request->search . '%');
        }

        $perPage = $request->per_page ?? 15;
        $centres = $query->paginate($perPage);

        return response()->json($centres);
    }

   public function store(Request $request)
{
    $data = $request->all();
    $data['id_administrateur'] = $request->user()->id_administrateur;
    $data['statut'] = $data['statut'] ?? 'brouillon';

    // Logo
    if ($request->hasFile('logo')) {
        $logo = $request->file('logo');
        $logoPath = $logo->store('centres/logos', 'public');
        $data['logo'] = '/storage/' . $logoPath;
    }

    // Photos
    if ($request->hasFile('photos')) {
        $photosArray = [];
        foreach ($request->file('photos') as $photo) {
            $path = $photo->store('centres/photos', 'public');
            $photosArray[] = '/storage/' . $path;
        }
        $data['photos'] = $photosArray; // ← Pas de json_encode() ici
    }

    $centre = Centre::create($data);

    if (isset($data['disciplines'])) {
        $centre->disciplines()->attach($data['disciplines']);
    }

    return response()->json([
        'message' => 'Centre créé avec succès',
        'centre' => $centre->load(['disciplines']),
    ], 201);
}

    public function show(Centre $centre)
    {
        return response()->json($centre->load(['disciplines']));
    }

    public function update(Request $request, Centre $centre)
{
    $data = $request->all();

    if ($request->hasFile('logo')) {
        $logo = $request->file('logo');
        $logoPath = $logo->store('centres/logos', 'public');
        $data['logo'] = '/storage/' . $logoPath;
    }

    if ($request->hasFile('photos')) {
        $photosArray = [];
        foreach ($request->file('photos') as $photo) {
            $path = $photo->store('centres/photos', 'public');
            $photosArray[] = '/storage/' . $path;
        }
        $data['photos'] = $photosArray; // ← Pas de json_encode() ici
    }

    $centre->update($data);

    if (isset($data['disciplines'])) {
        $centre->disciplines()->sync($data['disciplines']);
    }

    return response()->json([
        'message' => 'Centre mis à jour avec succès',
        'centre' => $centre->load(['disciplines']),
    ]);
}

    public function destroy(Centre $centre)
    {
        $centre->delete();

        return response()->json(['message' => 'Centre supprimé avec succès']);
    }

    public function publier(Centre $centre)
    {
        $ancienStatut = $centre->statut;
        $centre->publier();

        return response()->json([
            'message' => 'Centre publié avec succès',
            'centre' => $centre,
        ]);
    }

    public function depublier(Centre $centre)
    {
        $ancienStatut = $centre->statut;
        $centre->depublier();

        return response()->json([
            'message' => 'Centre dépublié avec succès',
            'centre' => $centre,
        ]);
    }
}