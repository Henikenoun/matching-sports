<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Club;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class RatingController extends Controller
{
    /**
     * Ajouter une évaluation.
     */
    public function store(Request $request)
{
    // Validation des données
    $request->validate([
        'rateable_id' => 'required|integer',
        'rateable_type' => 'required|string',
        'rating' => 'required|integer|min:1|max:5',
        'review' => 'nullable|string',
    ]);

    try {
        // Vérification des données envoyées
        Log::debug('Données envoyées: ', $request->all());

        // Vérifiez si l'utilisateur est authentifié
        if (!Auth::check()) {
            return response()->json(['message' => 'Utilisateur non authentifié'], 401);
        }

        $userId = Auth::id();
        Log::debug('ID de l\'utilisateur authentifié : ' . $userId);
        
        // Création de l'évaluation
        $rating = new Rating([
            'user_id' => $userId,
            'rateable_id' => $request->input('rateable_id'),
            'rateable_type' => $request->input('rateable_type'),
            'rating' => $request->input('rating'),
            'review' => $request->input('review'),
        ]);
        
        // Sauvegarde de l'évaluation
        $rating->save();

        // Retourner la réponse avec l'évaluation
        return response()->json([
            'message' => 'Évaluation ajoutée avec succès',
            'rating' => $rating
        ]);
        
    } catch (\Exception $e) {
        Log::error('Erreur lors de l\'ajout de l\'évaluation : ' . $e->getMessage());
        return response()->json(['error' => 'Erreur interne du serveur'], 500);
    }
}

    

    /**
     * Récupérer les évaluations pour une entité spécifique.
     */
    public function show($type, $id)
{
    Log::info('Début de la méthode show', ['type' => $type, 'id' => $id]);

    $model = match ($type) {
        'club' => Club::class,
        'user' => User::class,
        'event' => Event::class,
        default => null,
    };

    if (!$model) {
        Log::warning('Type d\'entité invalide', ['type' => $type]);
        return response()->json(['error' => 'Type d\'entité invalide'], 400);
    }

    $entity = $model::find($id);
    if (!$entity) {
        Log::warning('Entité non trouvée', ['type' => $type, 'id' => $id]);
        return response()->json(['error' => 'Entité non trouvée'], 404);
    }

    $ratings = Rating::where('rateable_type', $model)
        ->where('rateable_id', $id)
        ->get();

    Log::info('Évaluations récupérées avec succès', ['ratings' => $ratings]);

    if ($ratings->isEmpty()) {
        Log::info('Aucune évaluation trouvée pour cette entité', ['type' => $type, 'id' => $id]);
        $averageRating = 0;
    } else {
        $totalRatings = $ratings->sum('rating'); // Assuming 'note' is the field for the rating value
        $numberOfRatings = $ratings->count();
        $averageRating = $totalRatings / $numberOfRatings;
    }

    Log::info('Moyenne des évaluations calculée avec succès', ['averageRating' => $averageRating]);

    return response()->json([
        'entity' => $entity,
        'averageRating' => $averageRating
    ]);
}
}


