<?php

use App\Http\Controllers\DemandeController;
use App\Http\Controllers\EquipeController;
use App\Http\Controllers\EvenementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('api')->group(function () {
    Route::resource('evenements', EvenementController::class);
    });


    Route::put('/evenements/ajouterParticipant/{id}', [EvenementController::class, 'ajouterParticipant']);

    Route::middleware('api')->group(function() {
        Route::resource('equipes', EquipeController::class);
    });
    
    Route::middleware('api')->group(function() {
        Route::resource('demandes', DemandeController::class);
        Route::put('/demandes/{id}/status', [DemandeController::class, 'updateStatus']);
        Route::delete('/demandes/{id}/annulation', [DemandeController::class, 'annulation']);
    });
    
    // Route::middleware('api')->group(function() {
    //     Route::resource('Users', DemandeController::class);
    //     Route::put('/Users/{id}/status', [AuthController::class, 'updateAvailability']);
    // });