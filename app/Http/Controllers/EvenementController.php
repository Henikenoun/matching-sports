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
            // $evenements=Evenement::all();
            // $evenements=Evenement::with('participant')->get();
            $evenements=Evenement::with('responsable')->get();
            $evenements=Evenement::with('club')->get();
        
            return response()->json($evenements);
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

    public function ajouterParticipant(Request $request,$id)
    {
        try {
            $evenement=Evenement::findorFail($id);
            if($evenement->nbActuel<$evenement->nombreMax)
            {
                $evenement->nbActuel=$evenement->nbActuel+1;
                $evenement->participant=$request->input("participant");
                $evenement->save();
                return response()->json($evenement);
            }
            else
            {
                return response()->json("nombre maximal de participants atteint");
            }
        } catch (\Exception $e) {
            return response()->json("probleme d'ajout de participant");
        }
    }
    


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
    public function destroy($id)
    {
        try {
            
            $evenement=Evenement::findOrFail($id);
            if($evenement->raison=="")
            {
                return response()->json("Il faut remplir le champ raison ");
            }

            $evenement->delete();
            return response()->json("Evenement supprimée avec succes");
        } catch (\Exception $e) {
            return response()->json("probleme de suppression d'evenement");
        }
    }
}
