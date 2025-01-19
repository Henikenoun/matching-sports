<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{

    // Méthode pour afficher toutes les notifications d'un utilisateur
    public function showUserNotifications($id)
    {
        try {
            // Récupération des notifications de l'utilisateur depuis la base de données
            $notifications = DB::table('notifications')
                ->where('notifiable_id', $id)
                ->get();

            return response()->json($notifications);
        } catch (\Exception $e) {
            // Capture et affichage de l'erreur
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }   


    // Méthode pour afficher les notifications des événements créés par l'utilisateur
    public function showUserEventNotifications($id)
    {
        try {
            // Récupération des notifications de type 'NewEvent' pour l'utilisateur
            $notifications = DB::table('notifications')
                ->where('notifiable_id', $id)
                ->where('type', 'App\Notifications\NewEvent')
                ->get();
    
            return response()->json($notifications);
        } catch (\Exception $e) {
            // Capture et affichage de l'erreur
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // Méthode pour afficher les notifications des événements annulés par l'utilisateur
    public function showUserEventCancelledNotifications($id)
    {
        try {
            // Récupération des notifications de type 'EventCancelled' pour l'utilisateur
            $notifications = DB::table('notifications')
                ->where('notifiable_id', $id)
                ->where('type', 'App\Notifications\EventCancelled')
                ->get();
    
            return response()->json($notifications);
        } catch (\Exception $e) {
            // Capture et affichage de l'erreur
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Méthode pour afficher les notifications de réservation
    public function showUserNewReservationNotifications($id)
    {
        try {
            // Récupération des notifications de type 'NewReservation' pour l'utilisateur
            $notifications = DB::table('notifications')
                ->where('notifiable_id', $id)
                ->where('type', 'App\Notifications\NewReservation')
                ->get();
    
            return response()->json($notifications);
        } catch (\Exception $e) {
            // Capture et affichage de l'erreur
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // Méthode pour afficher les notifications de réponse  de réservation

    public function showUserReservationResponseNotifications($id)
    {
        try {
            // Récupération des notifications de type 'ReservationResponse' pour l'utilisateur
            $notifications = DB::table('notifications')
                ->where('notifiable_id', $id)
                ->where('type', 'App\Notifications\ReservationResponse')
                ->get();
    
            return response()->json($notifications);
        } catch (\Exception $e) {
            // Capture et affichage de l'erreur
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    
    // Méthode pour afficher les notifications de nouvelles demandes
    public function showUserNewDemandeNotifications($id)
    {
        try {
            // Récupération des notifications de type 'NewDemande' pour l'utilisateur
            $notifications = DB::table('notifications')
                ->where('notifiable_id', $id)
                ->where('type', 'App\Notifications\NewDemande')
                ->get();
    
            return response()->json($notifications);
        } catch (\Exception $e) {
            // Capture et affichage de l'erreur
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    // Méthode pour afficher les notifications de réponse aux demandes
    public function showUserDemandeResponseNotifications($id)
    {
        try {
            // Récupération des notifications de type 'DemandeResponse' pour l'utilisateur
            $notifications = DB::table('notifications')
                ->where('notifiable_id', $id)
                ->where('type', 'App\Notifications\DemandeResponse')
                ->get();
    
            return response()->json($notifications);
        } catch (\Exception $e) {
            // Capture et affichage de l'erreur
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}