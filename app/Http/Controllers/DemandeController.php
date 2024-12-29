<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use Illuminate\Http\Request;
use DateTime;

class DemandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
           
            $demandes = Demande::with(['user', 'equipe'])->get();

            return response()->json([
                "message" => "Liste des demandes récupérée avec succès",
                "demandes" => $demandes,
            ], 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validation des données entrantes
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'equipe_id' => 'required|exists:equipes,id',
                'date' => 'required|date',
            ]);
    
            // Création de la demande avec un état par défaut
            $demande = new Demande([
                "user_id" => $validatedData["user_id"],
                "equipe_id" => $validatedData["equipe_id"],
                "date" => $validatedData["date"],
                "etat" => "en cours", // État par défaut
            ]);
    
            $demande->save();
    
            return response()->json([
                "message" => "Demande créée avec succès",
                "demande" => $demande,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Erreur lors de la création de la demande",
                "error" => $e->getMessage(),
            ], 500);
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
           
            $demande = Demande::with(['user', 'equipe'])->findOrFail($id);

            return response()->json([
                "message" => "Demande récupérée avec succès",
                "demande" => $demande,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "error" => "Demande non trouvée",
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                "error" => "Erreur lors de la récupération de la demande : " . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Demande $demande)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy( $id)
    {
        try {
            
            $demande=Demande::findOrFail($id);
            $demande->delete();
            return response()->json("demande supprimée avec succes");
        } catch (\Exception $e) {
            return response()->json("probleme de suppression d'demande");
        }
    }
    public function updateStatus(Request $request, $id)
    {
        try {
            // Validation des données d'entrée
            $validatedData = $request->validate([
                'status' => 'required|string|in:en cours,acceptée', // Limité aux statuts gérés ici
            ]);
    
            // Recherche de la demande
            $demande = Demande::findOrFail($id);
    
            if ($validatedData['status'] === 'acceptée') {
                $user = $demande->user; // Relation définie entre Demande et User
                if ($user->availability == 1) {
                    $demande->etat = 'acceptée';
                    $user->availability = 0; // Mise à jour de la disponibilité de l'utilisateur
                    $user->save();
                } else {
                    return response()->json([
                        "message" => "Impossible de changer le statut en 'acceptée'. L'utilisateur n'est pas disponible.",
                    ], 400);
                }
            } else {
                // Mise à jour pour 'en cours'
                $demande->etat = $validatedData['status'];
            }
    
            $demande->save();
    
            return response()->json([
                "message" => "État de la demande mis à jour avec succès.",
                "demande" => $demande,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                "message" => "Demande introuvable",
                "error" => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Erreur lors de la mise à jour de l'état de la demande.",
                "error" => $e->getMessage(),
            ], 500);
        }
    }

    public function annulation($id)
{

    try {
        // Recherche de la demande
        $demande = Demande::findOrFail($id);
        $user = $demande->user; // Relation définie entre Demande et User

        // Vérification du statut de la demande
        if ($demande->etat === 'acceptée') {
            // Accéder à la réservation via l'équipe

            $equipe = $demande->equipe; // Relation vers l'équipe
            if (!$equipe) {
                return response()->json([
                    "message" => "Aucune équipe associée à cette demande."
                ], 404);
            }

            $reservation = $equipe->reservation; // Relation vers la réservation
            if (!$reservation) {
                return response()->json([
                    "message" => "Aucune réservation associée à l'équipe."
                ], 404);
            }
            $reservationDate = new DateTime($reservation->Date_Reservation); // Date de réservation
            $now = new DateTime(date('Y/m/d')); // Date actuelle au format aaaa/mm/jj
    
            // Calcul de la différence en jours
            $diffInDays = $reservationDate->diff($now)->days;
    
            if ($reservationDate < $now || $diffInDays < 1) {
                return response()->json([
                    "message" => "La date de réservation est inférieure à 1 jour. Annulation impossible."
                ], 400);
            }
            $user->availability = 1; // Mise à jour de la disponibilité de l'utilisateur
            $user->save();
        }

        // Suppression directe si l'état est "en cours" ou si les conditions sont remplies pour "acceptée"

        $demande->delete();

        return response()->json([
            "message" => "Demande annulée avec succès."
        ], 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            "message" => "Demande introuvable",
            "error" => $e->getMessage(),
        ], 404);
    } catch (\Exception $e) {
        return response()->json([
            "message" => "Erreur lors de l'annulation de la demande",
            "error" => $e->getMessage(),
        ], 500);
    }
}

    


}
