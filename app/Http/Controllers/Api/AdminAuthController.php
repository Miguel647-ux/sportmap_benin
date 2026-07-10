<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Administrateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'mot_de_passe' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $admin = Administrateur::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->mot_de_passe, $admin->mot_de_passe)) {
            return response()->json(['message' => 'Email ou mot de passe incorrect'], 401);
        }

        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'token' => $token,
            'admin' => [
                'id' => $admin->id_administrateur,
                'nom' => $admin->nom,
                'email' => $admin->email,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnexion réussie']);
    }

    public function me(Request $request)
    {
        return response()->json([
            'admin' => [
                'id' => $request->user()->id_administrateur,
                'nom' => $request->user()->nom,
                'email' => $request->user()->email,
            ],
        ]);
    }
}