<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TerrainController;
use App\Http\Controllers\ClubController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Routes for Terrain
Route::get('/terrains', [TerrainController::class, 'index']);
Route::post('/terrains', [TerrainController::class, 'store']);
Route::get('/terrains/{id}', [TerrainController::class, 'show']);
Route::delete('/terrains/{id}', [TerrainController::class, 'destroy']);
Route::put('/terrains/{id}', [TerrainController::class, 'update']);

// Routes for Club
Route::get('/clubs', [ClubController::class, 'index']);
Route::post('/clubs', [ClubController::class, 'store']);
Route::get('/clubs/{id}', [ClubController::class, 'show']);
Route::delete('/clubs/{id}', [ClubController::class, 'destroy']);
Route::put('/clubs/{id}', [ClubController::class, 'update']);
Route::get('/clubs/city/{city}', [ClubController::class, 'showClubsByCity']);
Route::get('/clubs/{id}/terrains', [ClubController::class, 'showTerrainsInClub']);
