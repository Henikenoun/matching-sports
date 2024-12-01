<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TerrainController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\ShopController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Routes for Reservation
Route::middleware('api')->group(function () {
    Route::resource('reservation', ReservationController::class);
});

// Routes for Evenements
Route::middleware('api')->group(function () {
    Route::resource('evenements', EvenementController::class);
});

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

// Routes for User Authentication
Route::group([
    'middleware' => 'api',
    'prefix' => 'users'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refreshToken', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::put('/edit-profile', [AuthController::class, 'editProfile']);
});

// Routes for Chat
Route::middleware('auth:api')->group(function () {
    Route::get('/conversations', [ChatController::class, 'getConversations']);
    Route::post('/messages', [ChatController::class, 'sendMessage']);
    Route::post('/conversations', [ChatController::class, 'store']);
});

// Routes for categories
Route::middleware('api')->group(function () {
    Route::resource('categories', CategorieController::class);
});

// Routes for shop
Route::middleware('api')->group(function () {
    Route::resource('shops', ShopController::class);
});
Route::post('/shops/{id}/add-photos', [ShopController::class, 'addPhotos']);
Route::get('/shops/{shopId}/categories', [ShopController::class, 'getCategoriesByShop']);
Route::get('/shops/{shopId}/categories/{categoryId}/articles', [ShopController::class, 'getArticlesByCategoryAndShop']);


// Routes for articles
Route::middleware('api')->group(function () {
    Route::resource('articles', ArticleController::class);
});
Route::post('/articles/{id}/add-color', [ArticleController::class, 'addColor']);


// Routes for demandes
Route::post('/demandes', [DemandeController::class, 'createDemande']);
Route::get('/demandes/{id}', [DemandeController::class, 'getDemandeDetails']);
Route::get('/user/{userId}/demandes', [DemandeController::class, 'getAllDemandesByUser']);
Route::get('/demandes', [DemandeController::class, 'getAllDemandes']);
?>