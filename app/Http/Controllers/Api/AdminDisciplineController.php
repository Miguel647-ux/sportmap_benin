<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDisciplineRequest;
use App\Models\Discipline;
use Illuminate\Http\Request;

class AdminDisciplineController extends Controller
{
    public function index(Request $request)
{
    $perPage = $request->per_page ?? 15;
    $disciplines = Discipline::orderBy('nom')->paginate($perPage);
    return response()->json($disciplines);
}

    public function store(StoreDisciplineRequest $request)
    {
        $discipline = Discipline::create($request->validated());

        return response()->json([
            'message' => 'Discipline créée avec succès',
            'discipline' => $discipline,
        ], 201);
    }

    public function show(Discipline $discipline)
    {
        return response()->json($discipline);
    }

    public function update(StoreDisciplineRequest $request, Discipline $discipline)
    {
        $discipline->update($request->validated());

        return response()->json([
            'message' => 'Discipline mise à jour avec succès',
            'discipline' => $discipline,
        ]);
    }

    public function destroy(Discipline $discipline)
    {
        $discipline->delete();

        return response()->json(['message' => 'Discipline supprimée avec succès']);
    }
}