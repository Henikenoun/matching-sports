<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TerrainController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('api')->group(function () {
    Route::resource('reservation', ReservationController::class);
});

Route::middleware('api')->group(function () {
    Route::resource('evenements', EvenementController::class);
    });


    Route::put('/evenements/ajouterParticipant/{id}', [EvenementController::class, 'ajouterParticipant']);


    // Routes for Terrain
Route::middleware('api')->group(function () {
    Route::resource('terrains', TerrainController::class);
    });
Route::put('/terrains/{id}/disponibilite-false', [TerrainController::class, 'setDisponibiliteFalse']);
Route::put('/terrains/{id}/disponibilite-true', [TerrainController::class, 'setDisponibiliteTrue']);
// Routes for Club
Route::middleware('api')->group(function () {
    Route::resource('clubs', ClubController::class);
    });
Route::get('/clubs/city/{city}', [ClubController::class, 'showClubsByCity']);
Route::get('/clubs/{id}/terrains', [ClubController::class, 'showTerrainsInClub']);

Route::group([
    'middleware' => 'api',
    'prefix' => 'users'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refreshToken', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});

use Illuminate\Support\Facades\Artisan;

Route::get('/run-scheduler', function (Request $request) {
    $secret = $request->query('secret');
    if ($secret !== env('CRON_SECRET')) {
        return response()->json(['error' => 'Accès non autorisé'], 403);
    }

    Artisan::call('reservations:supprimer');
    return response()->json(['message' => 'Tâche exécutée avec succès']);
});


?>