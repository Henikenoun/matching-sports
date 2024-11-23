<?php

namespace App\Http\Controllers;

use App\Models\reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $reservation=reservation::with('terrain')->get();
            return response()->json($reservation);
        } catch (\Exception $e) {
        return response()->json($e->getMessage(),400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $reservation=new reservation([
                "User_Reserve"=>$request->input("User_Reserve"),
                "Nom_Club"=>$request->input("Nom_Club"),
                "Nb_Place"=>$request->input("Nb_Place"),
                "Complet"=>$request->input("Complet"),
                "Type"=>$request->input("Type"),
                "Date_Reservation"=>$request->input("Date_Reservation"),
                "Date_TempsReel"=>$request->input("Date_TempsReel"),
                "Participants"=>$request->input("Participants"),
                "ispaye"=>$request->input("ispaye"),
                "Club_id"=>$request->input("Club_id"),
                "terrain_id"=>$request->input("terrain_id"),

            ]);
            $reservation->save();
            return response()->json($reservation);
            
            
        } catch (\Exception $e) {
           return response()->json($e->getMessage(),400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $reservation=reservation::findOrFail($id);
            return response()->json($reservation);
            
        } catch (\Exception $e) {
            return response()->json($e->getMessage(),400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $reservation=reservation::findorFail($id);
            $reservation->update($request->all());
            return response()->json($reservation);

        } catch (\Exception $e) {
            return response()->json($e->getMessage(),400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $reservation = Reservation::findOrFail($id);
            
            // Get the current date and the reservation date as DateTime objects
            $currentDate = new \DateTime();
            $reservationDate = new \DateTime($reservation->Date_TempsReel);
            
            // Calculate the difference in hours
            $interval = $currentDate->diff($reservationDate);
            $hoursDifference = ($interval->days * 24) + $interval->h; // Total hours difference
            
            if ($interval->invert == 0 && $hoursDifference > 24) {
                // If the reservation date is more than 24 hours away
                $reservation->delete();
                return response()->json("Réservation supprimée avec succès" , 200);
            } else {
                return response()->json("La suppression n'est autorisée que plus de 24 heures avant la date de réservation", 403);
            }
            
        } catch (\Exception $e) {
            return response()->json("Problème de suppression de la réservation", 500);
        }
    }
    //disponibilité de terrain lezmou true
    //notification 48h
    public function supprimerReservationsNonPayees()
    {
        try {
            // Obtenir la date et l'heure actuelles
            $now = new \DateTime();

            // Calculer la limite des 24 heures
            $limit = clone $now;
            $limit->modify('-24 hours');

            // Rechercher les réservations non payées et datant de plus de 24 heures
            $reservations = Reservation::where('ispaye', false)
                ->where('created_at', '<', $limit->format('Y-m-d H:i:s'))
                ->get();

            foreach ($reservations as $reservation) {
                // Envoyer une notification avant suppression
                $this->envoyerNotification($reservation);
                
                // Supprimer la réservation
                $reservation->delete();
            }

            return "Réservations non payées supprimées avec succès.";
        } catch (\Exception $e) {
            return "Erreur lors de la suppression des réservations : " . $e->getMessage();
        }
    }

    private function envoyerNotification($reservation)
    {
        // Exemple simple : écrire un message (vous pouvez utiliser un service de notification comme Mail ou SMS)
        echo "Notification : La réservation avec ID {$reservation->id} a été supprimée car elle n'était pas payée.\n";
    }






}
