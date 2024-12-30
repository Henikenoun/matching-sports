<?php

namespace App\Http\Controllers;

use App\Models\reservation;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\NewReservation;
use App\Notifications\ReservationResponse;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $reservation=reservation::all()->with('terrain')->get();
            return response()->json($reservation);
        } catch (\Exception $e) {
        return response()->json("probleme de récupération de la liste des reservation");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $reservation=new reservation([
                "ID"=>$request->input("ID"),
                "User_Reserve"=>$request->input("User_Reserve"),
                "Nb_Place"=>$request->input("Nb_Place"),
                "Complet"=>$request->input("Complet"),
                "Type"=>$request->input("Type"),
                "Date_Reservation"=>$request->input("Date_Reservation"),
                "Date_TempsReel"=>$request->input("Date_TempsReel"),
                "Participants"=>$request->input("Participants"),
                "terrain_id"=>$request->input("terrain_id"),
                "Club_id"=>$request->input("Club_id")

            ]);
            
            
            $reservation->save();
            
             // Récupérer le propriétaire du club depuis le modèle User
             $clubOwner = User::where('club_id', $reservation->Club_id)->first();

             // Envoyer la notification
             if ($clubOwner) {
                 $clubOwner->notify(new NewReservation($reservation));
             }
            
            return response()->json($reservation);
            
            
        } catch (\Exception $e) {
           return response()->json($e->getMessage());
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
            return response()->json("probleme de récupération des données");
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
            return response()->json("probleme de modification");
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
                return response()->json("Réservation supprimée avec succès");
            } else {
                return response()->json("La suppression n'est autorisée que plus de 24 heures avant la date de réservation", 403);
            }
            
        } catch (\Exception $e) {
            return response()->json("Problème de suppression de la réservation", 500);
        }
    }
    
    public function Annuler($id){
        try {
            $reservation = Reservation::findOrFail($id);
            if (!$reservation->ispaye) {
                $reservation->delete();
                return response()->json("Réservation supprimée car elle ne sont pas payée");
            }
        } catch (\Exception $e) {
            return response()->json("Problème de suppression de la réservation", 500);
        }
        
    }


    public function accept($id)
    {
        try {
            $reservation = Reservation::findOrFail($id);
            $reservation->update(['status' => 'accepted']); // Assurez-vous d'avoir une colonne 'status' dans votre table 'reservations'

            // Récupérer l'utilisateur qui a créé la réservation
            $user = User::findOrFail($reservation->User_Reserve);

            // Envoyer la notification
            $user->notify(new ReservationResponse($reservation, 'accepted'));

            return response()->json("Réservation acceptée avec succès");
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function refuse($id)
    {
        try {
            $reservation = Reservation::findOrFail($id);
            $reservation->update(['status' => 'refused']); // Assurez-vous d'avoir une colonne 'status' dans votre table 'reservations'

            // Récupérer l'utilisateur qui a créé la réservation
            $user = User::findOrFail($reservation->User_Reserve);

            // Envoyer la notification
            $user->notify(new ReservationResponse($reservation, 'refused'));

            return response()->json("Réservation refusée avec succès");
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    

}