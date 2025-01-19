<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use Illuminate\Http\Request;
use DateTime;
use App\Models\User;
use App\Notifications\DemandeResponse;
use App\Notifications\NewDemande;
use Illuminate\Support\Facades\Log; // Assurez-vous d'importer correctement la façade Log


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

            // Récupération de l'utilisateur pour l'envoyer la notification
        $user = User::find($validatedData["user_id"]);
        $user->notify(new NewDemande($demande));

    
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
        try {
            // Validation des données entrantes
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'equipe_id' => 'required|exists:equipes,id',
                'date' => 'required|date',
            ]);

            // Mise à jour de la demande existante
            $demande->update([
                "user_id" => $validatedData["user_id"],
                "equipe_id" => $validatedData["equipe_id"],
                "date" => $validatedData["date"],
                "etat" => $request->input('etat', $demande->etat), // Conserver l'état actuel si non fourni
            ]);

            // Récupération de l'utilisateur pour l'envoyer la notification
            $user = User::find($validatedData["user_id"]);
            $user->notify(new NewDemande($demande));

            return response()->json([
                "message" => "Demande mise à jour avec succès",
                "demande" => $demande,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Erreur lors de la mise à jour de la demande",
                "error" => $e->getMessage(),
            ], 500);
        }
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
      // Envoyer la notification d'annulation
      $user->notify(new DemandeResponse($demande));


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
public function accept($id)
    {
        try {
            // Recherche de la demande
            $demande = Demande::findOrFail($id);
            $user = $demande->user; // Relation définie entre Demande et User

            // Vérification du statut de la demande
            if ($demande->etat === 'en cours') {
                $demande->etat = 'acceptée';
                $user->availability = 0; // Mise à jour de la disponibilité de l'utilisateur
                $user->save();
            } else {
                return response()->json([
                    "message" => "Impossible de changer le statut en 'acceptée'. La demande est déjà acceptée.",
                ], 400);
            }

            $demande->save();

            // Envoyer la notification d'acceptation
            $user->notify(new DemandeResponse($demande));

            return response()->json([
                "message" => "Demande acceptée avec succès."
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                "message" => "Demande introuvable",
                "error" => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Erreur lors de l'acceptation de la demande",
                "error" => $e->getMessage(),
            ], 500);
        }
    }
//une méthode pour refus une demande et envoyer une notification à l'utilisateur
public function refuse($id)
    {
        try {
            // Recherche de la demande et chargement des relations nécessaires
            $demande = Demande::with(['user', 'equipe'])->findOrFail($id);
            $user = $demande->user; // Relation définie entre Demande et User

            // Vérification du statut de la demande
            if ($demande->etat === 'en cours') {
                $demande->etat = 'refusée';
            } else {
                return response()->json([
                    "message" => "Impossible de changer le statut en 'refusée'. La demande est déjà acceptée ou refusée.",
                ], 400);
            }

            $demande->save();

            // Vérification des relations
            if (!$demande->equipe) {
                Log::error('Demande ID ' . $demande->id . ' n\'a pas d\'équipe associée.');
                return response()->json([
                    "message" => "Les informations de la demande sont incomplètes : équipe manquante.",
                ], 400);
            }

            if (!$demande->user) {
                Log::error('Demande ID ' . $demande->id . ' n\'a pas d\'utilisateur associé.');
                return response()->json([
                    "message" => "Les informations de la demande sont incomplètes : utilisateur manquant.",
                ], 400);
            }

            // Log avant d'envoyer la notification
            Log::info('Envoi de la notification de refus pour la demande ID: ' . $demande->id);

            // Envoyer la notification de refus
            $notification = new DemandeResponse($demande, 'refusée');
            $user->notify($notification);

            // Log après l'envoi de la notification
            Log::info('Notification de refus envoyée pour la demande ID: ' . $demande->id);

            return response()->json([
                "message" => "Demande refusée avec succès.",
                "notification" => $notification->toArray($user)
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Demande introuvable: ' . $e->getMessage());
            return response()->json([
                "message" => "Demande introuvable",
                "error" => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            Log::error('Erreur lors du refus de la demande: ' . $e->getMessage());
            return response()->json([
                "message" => "Erreur lors du refus de la demande",
                "error" => $e->getMessage(),
            ], 500);
        }
    }

    public function accepter($id)
    {
        try {
            // Recherche de la demande et chargement des relations nécessaires
            $demande = Demande::with(['user', 'equipe'])->findOrFail($id);
            $user = $demande->user; // Relation définie entre Demande et User

            // Vérification du statut de la demande
            if ($demande->etat === 'en cours') {
                $demande->etat = 'acceptée';
            } else {
                return response()->json([
                    "message" => "Impossible de changer le statut en 'acceptée'. La demande est déjà acceptée ou refusée.",
                ], 400);
            }

            $demande->save();

            // Vérification des relations
            if (!$demande->equipe) {
                Log::error('Demande ID ' . $demande->id . ' n\'a pas d\'équipe associée.');
                return response()->json([
                    "message" => "Les informations de la demande sont incomplètes : équipe manquante.",
                ], 400);
            }

            if (!$demande->user) {
                Log::error('Demande ID ' . $demande->id . ' n\'a pas d\'utilisateur associé.');
                return response()->json([
                    "message" => "Les informations de la demande sont incomplètes : utilisateur manquant.",
                ], 400);
            }

            // Log avant d'envoyer la notification
            Log::info('Envoi de la notification d\'acceptation pour la demande ID: ' . $demande->id);

            // Envoyer la notification d'acceptation
            $notification = new DemandeResponse($demande, 'acceptée');
            $user->notify($notification);

            // Log après l'envoi de la notification
            Log::info('Notification d\'acceptation envoyée pour la demande ID: ' . $demande->id);

            return response()->json([
                "message" => "Demande acceptée avec succès.",
                "notification" => $notification->toArray($user)
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Demande introuvable: ' . $e->getMessage());
            return response()->json([
                "message" => "Demande introuvable",
                "error" => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'acceptation de la demande: ' . $e->getMessage());
            return response()->json([
                "message" => "Erreur lors de l'acceptation de la demande",
                "error" => $e->getMessage(),
            ], 500);
        }
    }
}