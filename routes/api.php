<?php

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

    // Routes for Terrain
Route::get('/terrains', [TerrainController::class, 'index']);
Route::post('/terrains', [TerrainController::class, 'store']);
Route::get('/terrains/{id}', [TerrainController::class, 'show']);
Route::delete('/terrains/{id}', [TerrainController::class, 'destroy']);
Route::put('/terrains/{id}', [TerrainController::class, 'update']);
Route::put('/terrains/{id}/disponibilite-false', [TerrainController::class, 'setDisponibiliteFalse']);
Route::put('/terrains/{id}/disponibilite-true', [TerrainController::class, 'setDisponibiliteTrue']);
// Routes for Club
Route::get('/clubs', [ClubController::class, 'index']);
Route::post('/clubs', [ClubController::class, 'store']);
Route::get('/clubs/{id}', [ClubController::class, 'show']);
Route::delete('/clubs/{id}', [ClubController::class, 'destroy']);
Route::put('/clubs/{id}', [ClubController::class, 'update']);
Route::get('/clubs/city/{city}', [ClubController::class, 'showClubsByCity']);
Route::get('/clubs/{id}/terrains', [ClubController::class, 'showTerrainsInClub']);

?>