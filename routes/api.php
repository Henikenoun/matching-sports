<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::get('/comptes', 'App\Http\Controllers\CompteController@index');
Route::post('/comptes', 'App\Http\Controllers\CompteController@store');
Route::get('/comptes/{id}', 'App\Http\Controllers\CompteController@show');
Route::put('/comptes/{id}', 'App\Http\Controllers\CompteController@update');
Route::delete('/comptes/{id}', 'App\Http\Controllers\CompteController@destroy');
