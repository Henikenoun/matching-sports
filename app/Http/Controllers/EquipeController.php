<?php

namespace App\Http\Controllers;

use App\Models\Equipe;
use Illuminate\Http\Request;

class EquipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index()
    {
        try {
            $equipes = Equipe::with(['reservation'])->get();
    
            // Retourner les données en JSON
            return response()->json([
                "message" => "Liste des équipes récupérée avec succès",
                "equipes" => $equipes,
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
           
            // Création de l'équipe
            $equipe = new Equipe([
                "nom" => $request->input("nom"),
                "type" => $request->input("type"),
                "nombre" => $request->input("nombre"),
                "reservation_id" => $request->input("reservation_id"),
                "participants" => json_encode($request->input("participants")), // Conversion en JSON si nécessaire
            ]);
    
            $equipe->save();
    
            // Retourne la réponse en JSON
            return response()->json([
                "message" => "Équipe créée avec succès",
                "equipe" => $equipe,
            ], 201);
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
            $equipe=Equipe::findorFail($id);
            return response()->json($equipe);
        } catch (\Exception $e) {
            return response()->json("probleme de récupération de l'equipe");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Equipe $equipe)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
   
    public function destroy($id)
    {
        try {
            
            $equipe=Equipe::findOrFail($id);
            $equipe->delete();
            return response()->json("Equipe supprimée avec succes");
        } catch (\Exception $e) {
            return response()->json("probleme de suppression d'equipe");
        }
    }
}
