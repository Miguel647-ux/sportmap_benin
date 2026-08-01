<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Centre;
use App\Models\Discipline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\NouveauCentreMail;

class VisiteurCentreController extends Controller
{
    /**
     * Liste des centres (publics)
     */
    public function index(Request $request)
    {
        $query = Centre::with(['disciplines'])
            ->where('statut', 'publie')
            ->where('statut_validation', 'valide');

        // Filtre par discipline
        if ($request->has('id_discipline') && $request->id_discipline) {
            $query->whereHas('disciplines', function ($q) use ($request) {
                $q->where('disciplines.id_discipline', $request->id_discipline);
            });
        }

        // Filtre par commune
        if ($request->has('commune') && $request->commune) {
            $query->where('commune', 'LIKE', '%' . $request->commune . '%');
        }

        // Filtre par quartier
        if ($request->has('quartier') && $request->quartier) {
            $query->where('quartier', 'LIKE', '%' . $request->quartier . '%');
        }

        // Recherche par nom
        if ($request->has('search') && $request->search) {
            $query->where('nom', 'LIKE', '%' . $request->search . '%');
        }

        // Géolocalisation (rayon de 5km par défaut)
        if ($request->has('latitude') && $request->has('longitude')) {
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

    /**
     * Détail d'un centre (public)
     */
    public function show(Centre $centre)
    {
        if ($centre->statut !== 'publie' || $centre->statut_validation !== 'valide') {
            return response()->json(['message' => 'Centre non trouvé'], 404);
        }

        $centre->increment('nombre_vues');

        return response()->json($centre->load(['disciplines']));
    }

    /**
     * Liste des disciplines (public)
     */
    public function disciplines()
    {
        return response()->json(Discipline::orderBy('nom')->get());
    }

    /**
     * Ajout d'un centre par un visiteur (statut en_attente)
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:150',
            'description' => 'nullable|string',
            'adresse' => 'nullable|string|max:255',
            'commune' => 'nullable|string|max:100',
            'quartier' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'telephone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:150',
            'horaires' => 'nullable|string|max:255',
            'disciplines' => 'required|array|min:1',
            'disciplines.*' => 'exists:disciplines,id_discipline',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Création du centre avec statut "en_attente"
        $centre = Centre::create([
            'nom' => $request->nom,
            'description' => $request->description,
            'adresse' => $request->adresse,
            'commune' => $request->commune,
            'quartier' => $request->quartier,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'telephone' => $request->telephone,
            'whatsapp' => $request->whatsapp,
            'email' => $request->email,
            'horaires' => $request->horaires,
            'statut' => 'brouillon',
            'statut_validation' => 'en_attente',
            'id_administrateur' => 1, // Admin par défaut (à adapter)
        ]);

        // Ajouter les disciplines
        if ($request->has('disciplines')) {
            $centre->disciplines()->attach($request->disciplines);
        }

        // Envoyer une notification à l'admin
        try {
            Mail::to(config('mail.from.address'))->send(new NouveauCentreMail($centre));
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email admin: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Votre centre a été ajouté avec succès. Il sera publié après validation par l\'administrateur.',
            'centre' => $centre,
        ], 201);
    }
}