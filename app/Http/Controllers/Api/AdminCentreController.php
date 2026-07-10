<?php

namespace App\Http\Controllers\Api;

use App\Notifications\CentreStatutNotification;
use App\Models\Administrateur;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCentreRequest;
use App\Http\Requests\Admin\UpdateCentreRequest;
use App\Models\Centre;
use App\Http\Requests\Admin\UploadPhotoRequest;
use App\Models\Photo;
use Illuminate\Support\Facades\Storage;

class AdminCentreController extends Controller
{
    public function index(Request $request)
{
    $query = Centre::with(['quartier.commune', 'disciplines', 'categorieAges', 'photos'])
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

    public function store(StoreCentreRequest $request)
    {
        $data = $request->validated();
        $data['id_administrateur'] = $request->user()->id_administrateur;
        $data['statut'] = $data['statut'] ?? 'brouillon';

        $centre = Centre::create($data);

        if (isset($data['disciplines'])) {
            $centre->disciplines()->attach($data['disciplines']);
        }

        if (isset($data['categorie_ages'])) {
            $centre->categorieAges()->attach($data['categorie_ages']);
        }

        return response()->json([
            'message' => 'Centre créé avec succès',
            'centre' => $centre->load(['quartier', 'disciplines', 'categorieAges']),
        ], 201);
    }

    public function show(Centre $centre)
    {
        return response()->json($centre->load(['quartier.commune', 'disciplines', 'categorieAges', 'photos']));
    }

    public function update(UpdateCentreRequest $request, Centre $centre)
    {
        $data = $request->validated();

        $centre->update($data);

        if (isset($data['disciplines'])) {
            $centre->disciplines()->sync($data['disciplines']);
        }

        if (isset($data['categorie_ages'])) {
            $centre->categorieAges()->sync($data['categorie_ages']);
        }

        return response()->json([
            'message' => 'Centre mis à jour avec succès',
            'centre' => $centre->load(['quartier', 'disciplines', 'categorieAges']),
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

    // Notifier l'administrateur
    $admin = Administrateur::find($centre->id_administrateur);
    if ($admin) {
        $admin->notify(new CentreStatutNotification($centre, $ancienStatut, 'publie'));
    }

        return response()->json([
            'message' => 'Centre publié avec succès',
            'centre' => $centre,
        ]);
    }

    public function depublier(Centre $centre)
    {
        $ancienStatut = $centre->statut;
        $centre->depublier();

          // Notifier l'administrateur
    $admin = Administrateur::find($centre->id_administrateur);
    if ($admin) {
        $admin->notify(new CentreStatutNotification($centre, $ancienStatut, 'brouillon'));
    }

        return response()->json([
            'message' => 'Centre dépublié avec succès',
            'centre' => $centre,
        ]);
    }

    public function uploadPhoto(UploadPhotoRequest $request, Centre $centre)
{
     dd($request->all(), $request->file('photo'));
if ($request->hasFile('photo')) {
        $file = $request->file('photo');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('centres/' . $centre->id_centre . '/photos', $filename, 'public');

        $photo = Photo::create([
            'url' => '/storage/' . $path,
            'type' => $request->type ?? 'photo',
            'id_centre' => $centre->id_centre,
        ]);
        

        return response()->json([
            'message' => 'Photo uploadée avec succès',
            'photo' => $photo,
        ], 201);
    }

    return response()->json(['message' => 'Aucune photo fournie'], 422);
}

public function deletePhoto(Photo $photo)
{
    // Supprimer le fichier physique
    $path = str_replace('/storage/', '', $photo->url);
    if (Storage::disk('public')->exists($path)) {
        Storage::disk('public')->delete($path);
    }

    $photo->delete();

    return response()->json(['message' => 'Photo supprimée avec succès']);
}
}