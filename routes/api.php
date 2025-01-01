<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TerrainController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\DemandePController;
use App\Http\Controllers\EquipeController;
use App\Http\Controllers\ShopController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StripeController;

Route::post('/payment/processpayment', [StripeController::class,
'processPayment']);

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
Route::get('/users/{id}', [AuthController::class, 'getUserById']);
Route::group([
    'middleware' => 'api',
    'prefix' => 'users'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refreshToken', [AuthController::class, 'refresh']);
    Route::middleware('auth:api')->get('/user-profile', [AuthController::class, 'getUserProfile']);
    Route::get('/getAll', [AuthController::class, 'getall']);
    Route::put('/edit-profile', [AuthController::class, 'editProfile']);
});
Route::get('users/verify-email', [AuthController::class, 'verifyEmail'])->name('verify.email');

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
Route::post('/demandesP', [DemandePController::class, 'createDemande']);
Route::get('/demandesP/{id}', [DemandePController::class, 'getDemandeDetails']);
Route::get('/user/{userId}/demandesP', [DemandePController::class, 'getAllDemandesByUser']);
Route::get('/demandesP', [DemandePController::class, 'getAllDemandes']);
?>

