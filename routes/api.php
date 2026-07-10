<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\AdminCentreController;
use App\Http\Controllers\Api\AdminDisciplineController;
use App\Http\Controllers\Api\AdminLocaliteController;
use App\Http\Controllers\Api\VisiteurCentreController;

// ============================================
// ROUTES PUBLIQUES (Visiteur)
// ============================================

Route::prefix('visiteur')->group(function () {
    Route::get('/centres', [VisiteurCentreController::class, 'index']);
    Route::get('/centres/{centre}', [VisiteurCentreController::class, 'show']);
    Route::get('/disciplines', [VisiteurCentreController::class, 'disciplines']);
    Route::get('/categorie-ages', [VisiteurCentreController::class, 'categorieAges']);
});

// ============================================
// ROUTES ADMIN (Authentification)
// ============================================

Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login']);
});

// ============================================
// ROUTES ADMIN (Protégées)
// ============================================

Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AdminAuthController::class, 'logout']);
    Route::get('/me', [AdminAuthController::class, 'me']);


    // Centres
    Route::apiResource('centres', AdminCentreController::class);
    Route::post('/centres/{centre}/publier', [AdminCentreController::class, 'publier']);
    Route::post('/centres/{centre}/depublier', [AdminCentreController::class, 'depublier']);
    Route::post('/centres/{centre}/photos', [AdminCentreController::class, 'uploadPhoto']);
    Route::delete('/photos/{photo}', [AdminCentreController::class, 'deletePhoto']);

    // Disciplines
    Route::apiResource('disciplines', AdminDisciplineController::class);

    // Localités
    Route::get('/communes', [AdminLocaliteController::class, 'indexCommunes']);
    Route::post('/communes', [AdminLocaliteController::class, 'storeCommune']);
    Route::get('/communes/{commune}', [AdminLocaliteController::class, 'showCommune']);
    Route::put('/communes/{commune}', [AdminLocaliteController::class, 'updateCommune']);
    Route::delete('/communes/{commune}', [AdminLocaliteController::class, 'destroyCommune']);

    Route::get('/quartiers', [AdminLocaliteController::class, 'indexQuartiers']);
    Route::post('/quartiers', [AdminLocaliteController::class, 'storeQuartier']);
    Route::get('/quartiers/{quartier}', [AdminLocaliteController::class, 'showQuartier']);
    Route::put('/quartiers/{quartier}', [AdminLocaliteController::class, 'updateQuartier']);
    Route::delete('/quartiers/{quartier}', [AdminLocaliteController::class, 'destroyQuartier']);

    
});