<?php

namespace App\Http\Controllers;


use App\Models\DemandeP;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class DemandePController extends Controller
{
   /**
     * Ajouter une demande.
     */
    public function createDemande(Request $request)
    {
        try {
            $demande = DemandeP::create([
                'user_id' => $request->user_id,
                'article_id' => $request->article_id,
                'quantity' => $request->quantity,
                'total' => $request->total
            ]);

            return response()->json([
                'message' => 'Demande créée avec succès',
                'demande' => $demande
            ], 201); // 201 Created
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la création de la demande',
                'details' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Récupérer les détails d'une demande.
     */
    public function getDemandeDetails($id)
    {
        try {
            $demande = DemandeP::with(['user', 'article'])->findOrFail($id);

            return response()->json($demande, 200); // 200 OK
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Demande non trouvée'
            ], 404); // 404 Not Found
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la récupération de la demande',
                'details' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Récupérer toutes les demandes d'un utilisateur.
     */
    public function getAllDemandesByUser($userId)
    {
        try {
            $demandes = DemandeP::with('article')->where('user_id', $userId)->get();

            if ($demandes->isEmpty()) {
                return response()->json([
                    'message' => 'Aucune demande trouvée pour cet utilisateur'
                ], 404); // 404 Not Found
            }

            return response()->json($demandes, 200); // 200 OK
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la récupération des demandes',
                'details' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Récupérer toutes les demandes avec la quantité et le prix total.
     */
    public function getAllDemandes()
    {
        try {
            $demandes = DemandeP::with('article')
                ->get()
                ->map(function ($demande) {
                    $totalPrice = $demande->article->price * $demande->quantity;
                    return [
                        'demande_id' => $demande->id,
                        'user' => $demande->user->name,
                        'article' => $demande->article->name,
                        'quantity' => $demande->quantity,
                        'total_price' => $totalPrice
                    ];
                });

            if ($demandes->isEmpty()) {
                return response()->json([
                    'message' => 'Aucune demande disponible'
                ], 404); // 404 Not Found
            }

            return response()->json($demandes, 200); // 200 OK
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la récupération des demandes',
                'details' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }
}