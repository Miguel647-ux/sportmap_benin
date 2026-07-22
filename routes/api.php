<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\AdminCentreController;
use App\Http\Controllers\Api\AdminDisciplineController;
use App\Http\Controllers\Api\AdminLocaliteController;
use App\Http\Controllers\Api\VisiteurCentreController;
use App\Http\Controllers\Api\AdminStatsController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\AdminContactController;


// ============================================
// ROUTES PUBLIQUES (Visiteur)
// ============================================

Route::prefix('visiteur')->group(function () {
    Route::get('/centres', [VisiteurCentreController::class, 'index']);
    Route::get('/centres/{centre}', [VisiteurCentreController::class, 'show']);
    Route::get('/disciplines', [VisiteurCentreController::class, 'disciplines']);
     Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,1');
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

Route::prefix('admin')->middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    // Auth
    Route::post('/logout', [AdminAuthController::class, 'logout']);
    Route::get('/me', [AdminAuthController::class, 'me']);
    Route::get('/contacts', [AdminContactController::class, 'index']);
     Route::put('/contacts/{contact}/read', [AdminContactController::class, 'markAsRead']);
    Route::delete('/contacts/{contact}', [AdminContactController::class, 'destroy']);
    


    // Centres
    Route::apiResource('centres', AdminCentreController::class);
    Route::post('/centres/{centre}/publier', [AdminCentreController::class, 'publier']);
    Route::post('/centres/{centre}/depublier', [AdminCentreController::class, 'depublier']);
    Route::post('/centres/{centre}/photos', [AdminCentreController::class, 'uploadPhoto']);
    Route::delete('/photos/{photo}', [AdminCentreController::class, 'deletePhoto']);

    // Disciplines
    Route::apiResource('disciplines', AdminDisciplineController::class);

    Route::get('/stats', [AdminStatsController::class, 'index']);
});

Route::get('/contacts', [ContactController::class, 'index'])->middleware('auth:sanctum');