<?php

namespace App\Http\Controllers\Api;

use App\Services\CloudinaryService;
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

   public function store(Request $request, CloudinaryService $cloudinary)
{
    $data = $request->all();
    $data['id_administrateur'] = $request->user()->id_administrateur;
    $data['statut'] = $data['statut'] ?? 'brouillon';

    // Gérer le logo
    if ($request->hasFile('logo')) {
        $data['logo'] = $cloudinary->upload($request->file('logo')->getRealPath(), 'sportmap/logos');
    }

    // Gérer les photos
    if ($request->hasFile('photos')) {
        $photosArray = [];
        foreach ($request->file('photos') as $photo) {
            $photosArray[] = $cloudinary->upload($photo->getRealPath(), 'sportmap/photos');
        }
        $data['photos'] = json_encode($photosArray);
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

   public function update(Request $request, Centre $centre, CloudinaryService $cloudinary)
{
    $data = $request->all();

    // Gérer le logo
    if ($request->hasFile('logo')) {
        $data['logo'] = $cloudinary->upload($request->file('logo')->getRealPath(), 'sportmap/logos');
    }

    // Gérer les photos
    if ($request->hasFile('photos')) {
        $photosArray = [];
        foreach ($request->file('photos') as $photo) {
            $photosArray[] = $cloudinary->upload($photo->getRealPath(), 'sportmap/photos');
        }
        $data['photos'] = json_encode($photosArray);
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