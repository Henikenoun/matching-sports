<?php

use App\Http\Controllers\EvenementController;
use App\Http\Controllers\ReservationController;
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

?>