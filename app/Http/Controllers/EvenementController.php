<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\User;
use App\Notifications\NewEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Notifications\EventCancelled;

class EvenementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $evenements = Evenement::with(['responsable', 'club', 'terrain'])->get();
            return response()->json($evenements);
        } catch (\Exception $e) {
            Log::error('Error in index method: ' . $e->getMessage());
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $evenement = new Evenement([
                "terrain_id" => $request->input("terrain_id"),
                "club_id" => $request->input("club_id"),
                "nom" => $request->input("nom"),
                "type" => $request->input("type"),
                "nombreMax" => $request->input("nombreMax"),
                "date" => $request->input("date"),
                "nbActuel" => $request->input("nbActuel"),
                "description" => $request->input("description"),
                "photo" => $request->input("photo"),
                "prixUnitaire" => $request->input("prixUnitaire"),
                "responsable" => $request->input("responsable"),
                "raison" => $request->input("raison"),
            ]);
            $evenement->save();

            // Send notifications to all users
            $users = User::all();
            foreach ($users as $user) {
                $user->notify(new NewEvent($evenement));
            }

            return response()->json($evenement);
        } catch (\Exception $e) {
            Log::error('Error in store method: ' . $e->getMessage());
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Add participants to the event.
     */
    public function ajouterParticipant(Request $request, $id)
    {
        try {
            $evenement = Evenement::findOrFail($id);
            $participants = $request->input('participants'); // Expecting an array of participant IDs

            if ($evenement->nbActuel + count($participants) <= $evenement->nombreMax) {
                $evenement->participants()->attach($participants);
                $evenement->nbActuel += count($participants);
                $evenement->save();

                return response()->json($evenement);
            } else {
                return response()->json("nombre maximal de participants atteint", 400);
            }
        } catch (\Exception $e) {
            Log::error('Error in ajouterParticipant method: ' . $e->getMessage());
            return response()->json($e->getMessage(), 500);
        }
    }
    //ajouter la methode annuler evenement et notifiez les users 
    public function annulerEvenement(Request $request, $id)
    {
        try {
            $evenement = Evenement::findOrFail($id);
            $evenement->delete();
            $users = User::all();
            foreach ($users as $user) {
                $user->notify(new EventCancelled($evenement));
            }
            return response()->json($evenement);
        } catch (\Exception $e) {
            Log::error('Error in annulerEvenement method: ' . $e->getMessage());
            return response()->json($e->getMessage(), 500);
        }
    }
}