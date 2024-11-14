<?php

namespace App\Http\Controllers;

use App\Models\Terrain;
use Illuminate\Http\Request;

class TerrainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $terrains = Terrain::with('club')->get(); // Inclut le club lié
            return response()->json($terrains, 200);
        } catch (\Exception $e) {
            return response()->json("Sélection impossible {$e->getMessage()}");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $terrain = new Terrain([
                'nom' => $request->input('nom'),
                'type' => $request->input('type'),
                'disponibilite' => $request->input('disponibilite'),
                'capacite' => $request->input('capacite'),
                'fraisLocation' => $request->input('fraisLocation'),
                'club_id' => $request->input('club_id'),
            ]);
            $terrain->save();
            return response()->json($terrain);
        } catch (\Exception $e) {
            return response()->json("Insertion impossible {$e->getMessage()}");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $terrain = Terrain::findOrFail($id);
            return response()->json($terrain);
        } catch (\Exception $e) {
            return response()->json("Problème de récupération des données {$e->getMessage()}");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $terrain = Terrain::findOrFail($id);
            $terrain->update($request->all());
            return response()->json($terrain);
        } catch (\Exception $e) {
            return response()->json("Problème de modification {$e->getMessage()}");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $terrain = Terrain::findOrFail($id);
            $terrain->delete();
            return response()->json("Terrain supprimé avec succès");
        } catch (\Exception $e) {
            return response()->json("Problème de suppression de terrain {$e->getMessage()}");
        }
    }

    public function showTerrainsByClub($clubId)
    {
        try {
            $terrains = Terrain::where('club_id', $clubId)->with('club')->get();
            return response()->json($terrains);
        } catch (\Exception $e) {
            return response()->json("Sélection impossible {$e->getMessage()}");
        }
    }


}
