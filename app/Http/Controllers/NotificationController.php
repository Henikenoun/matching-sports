<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    // Display a listing of the notifications.
    // public function index()
    // {
    //     $notifications = Notification::all();
    //     return response()->json($notifications);
    // }

    // Store a newly created notification in storage.
    // public function store(Request $request)
    // {
    //     $notification = new Notification();
    //     $notification->title = $request->title;
    //     $notification->message = $request->message;
    //     $notification->save();

    //     return response()->json($notification, 201);
    // }

    // Display the specified notification.
    // public function show($id)
    // {
    //     $notification = Notification::find($id);
    //     if ($notification) {
    //         return response()->json($notification);
    //     } else {
    //         return response()->json(['message' => 'Notification not found'], 404);
    //     }
    // }

    // Update the specified notification in storage.
    // public function update(Request $request, $id)
    // {
    //     $notification = Notification::find($id);
    //     if ($notification) {
    //         $notification->title = $request->title;
    //         $notification->message = $request->message;
    //         $notification->save();

    //         return response()->json($notification);
    //     } else {
    //         return response()->json(['message' => 'Notification not found'], 404);
    //     }
    // }

    // Remove the specified notification from storage.
    // public function destroy($id)
    // {
    //     $notification = Notification::find($id);
    //     if ($notification) {
    //         $notification->delete();
    //         return response()->json(['message' => 'Notification deleted']);
    //     } else {
    //         return response()->json(['message' => 'Notification not found'], 404);
    //     }
    // }

    // Méthode pour afficher les notifications de création de notifications
    public function showEventNotifications()
    {
        try {
            // Vérification de la base de données
            $notifications = DB::table('notifications')
                ->where('type', 'App\Notifications\NewEvent')
                ->get();

            return response()->json($notifications);
        } catch (\Exception $e) {
            // Capture et affichage de l'erreur
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
// methode pour afficher les notifications de annulation de evenement
    public function showEventNotificationsAnnulation()
    {
        try {
            // Vérification de la base de données
            $notifications = DB::table('notifications')
                ->where('type', 'App\Notifications\EventCancelled')
                ->get();

            return response()->json($notifications);
        } catch (\Exception $e) {
            // Capture et affichage de l'erreur
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // methode pour afficher les notifications de refus de reservation
    public function showReservationNotificationsRefus()
    {
        try {
            // Vérification de la base de données
            $notifications = DB::table('notifications')
                ->where('type', 'App\Notifications\ReservationResponse')
                ->get();

            return response()->json($notifications);
        } catch (\Exception $e) {
            // Capture et affichage de l'erreur
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // methode pour afficher les notifications de acceptation de reservation
    public function showReservationNotificationsAccept()
    {
        try {
            // Vérification de la base de données
            $notifications = DB::table('notifications')
                ->where('type', 'App\Notifications\ReservationResponse')
                ->get();

            return response()->json($notifications);
        } catch (\Exception $e) {
            // Capture et affichage de l'erreur
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // methode pour afficher les notifications de demande de reservation

    public function showReservationNotificationsDemande()
    {
        try {
            // Vérification de la base de données
            $notifications = DB::table('notifications')
                ->where('type', 'App\Notifications\NewReservation')
                ->get();

            return response()->json($notifications);
        } catch (\Exception $e) {
            // Capture et affichage de l'erreur
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

// methode pour afficher les notifications de demandes 
    public function showDemandeNotifications()
    {
        try {
            // Vérification de la base de données
            $notifications = DB::table('notifications')
                ->where('type', 'App\Notifications\NewDemande')
                ->get();

            return response()->json($notifications);
        } catch (\Exception $e) {
            // Capture et affichage de l'erreur
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // methode pour afficher les notifications de refus de demande

    public function showDemandeNotificationsRefus()
    {
        try {
            // Vérification de la base de données
            $notifications = DB::table('notifications')
                ->where('type', 'App\Notifications\DemandeResponse')
                ->get();

            return response()->json($notifications);
        } catch (\Exception $e) {
            // Capture et affichage de l'erreur
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    // methode pour afficher les notifications de acceptation de demande

    public function showDemandeNotificationsAccept()
    {
        try {
            // Vérification de la base de données
            $notifications = DB::table('notifications')
                ->where('type', 'App\Notifications\DemandeResponse')
                ->get();

            return response()->json($notifications);
        } catch (\Exception $e) {
            // Capture et affichage de l'erreur
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }






   

   
}