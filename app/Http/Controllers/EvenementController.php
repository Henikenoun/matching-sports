<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Http\Request;

class EvenementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            //$evenements=Evenement::all();
            //$evenements=Evenement::with('terrain')->get();
            //$evenements=Evenement::with('participant')->get();
            
            //  $evenements=Evenement::with('responsable')->get();
            //  $evenements=Evenement::with('club')->get();
            $evenements = Evenement::with(['responsable', 'club'])->get();
            


        
            return response()->json($evenements);
        } catch (\Exception $e) {
        return response()->json($e->getMessage());
        }
    }

    //afficher les evenement d'un participant
    public function getEvenementsByParticipant($participantId)
    {
        try {
            $evenements = Evenement::whereHas('participants', function ($query) use ($participantId) {
                $query->where('participant_id', $participantId);
            })->with(['responsable', 'club'])->get();
    
            if ($evenements->isEmpty()) {
                return response()->json([
                    'message' => 'Aucun événement trouvé pour ce participant'
                ], 404); // 404 Not Found
            }
    
            return response()->json($evenements, 200); // 200 OK
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la récupération des événements',
                'details' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        try {
            $evenement=new Evenement([
                "terrain_id"=>$request->input("terrain_id"),
                "club_id"=>$request->input("club_id"),
                "nom"=>$request->input("nom"),
                "type"=>$request->input("type"),
                "nombreMax"=>$request->input("nombreMax"),
                "date"=>$request->input("date"),
                "nbActuel"=>$request->input("nbActuel"),
                "description"=>$request->input("description"),
                "photo"=>$request->input("photo"),
                "prixUnitaire"=>$request->input("prixUnitaire"),
                "responsable"=>$request->input("responsable"),
                //"participant"=>$request->input("participant"),
                "raison"=>$request->input("raison"),

            ]);
            $evenement->save();
            return response()->json($evenement);
            
            
        } catch (\Exception $e) {
           return response()->json($e->getMessage());
        }
    }
    
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
            return response()->json($e->getMessage(), 500);
        }
    }

    // public function ajouterParticipant(Request $request,$id)
    // {
    //     try {
    //         $evenement=Evenement::findorFail($id);

    //         if($evenement->nbActuel<$evenement->nombreMax)
    //         {
    //             $evenement->nbActuel=$evenement->nbActuel+1;
    //             $evenement->participants=$request->input("participants");
    //             $evenement->save();
    //             return response()->json($evenement);
    //         }
    //         else
    //         {
    //             return response()->json("nombre maximal de participants atteint");
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json($e->getMessage(), 500);
    //     }
    // }
    


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $evenement=Evenement::findorFail($id);
            return response()->json($evenement);
        } catch (\Exception $e) {
            return response()->json("probleme de récupération de l'evenement");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $evenement=Evenement::findorFail($id);
            $evenement->update($request->all());
            return response()->json($evenement);

        } catch (\Exception $e) {
            return response()->json("probleme de modification");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
//     public function destroy($id,Request $request)
//     {
//         try {
            
//             $evenement=Evenement::findOrFail($id);
//             $raison=$request->input('raison');
//             if($evenement->raison=="")
//             {
//                 return response()->json("Il faut remplir le champ raison ");
//             }
// $evenement->raison=$raison;
//             $evenement->delete();
//             return response()->json("Evenement supprimée avec succes");
//         } catch (\Exception $e) {
//             return response()->json("probleme de suppression d'evenement");
//         }
//     }

public function destroy(Request $request, $id)
{
    try {
        $evenement = Evenement::findOrFail($id);
        $raison = $request->input('raison');

        if (empty($raison)) {
            return response()->json("Il faut remplir le champ raison", 400);
        }

        $evenement->raison = $raison;
        $evenement->delete();

        return response()->json("Evenement supprimé avec succès");
    } catch (\Exception $e) {
        return response()->json("Problème de suppression d'événement", 500);
 
   }

}



   public function getEvenementsByResponsable($responsableId)
    {
        try {
            $evenements = Evenement::where('responsable', $responsableId)->with(['responsable', 'club'])->get();
    
            if ($evenements->isEmpty()) {
                return response()->json([
                    'message' => 'Aucun événement trouvé pour ce responsable'
                ], 404); // 404 Not Found
            }
    
            return response()->json($evenements, 200); // 200 OK
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la récupération des événements',
                'details' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }

    }








}
