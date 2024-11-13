<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $clubs = Club::with('terrains')->get(); // Inclut les terrains liés
            return response()->json($clubs, 200);
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
            $club = new Club([
                'nom' => $request->input('nom'),
                'ville' => $request->input('ville'),
                'adresse' => $request->input('adresse'),
                'numTel' => $request->input('numTel'),
                'email' => $request->input('email'),
                'nbTerrain' => $request->input('nbTerrain'),
            ]);
            $club->save();
            return response()->json($club);
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
            $club = Club::findOrFail($id);
            return response()->json($club);
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
            $club = Club::findOrFail($id);
            $club->update($request->all());
            return response()->json($club);
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
            $club = Club::findOrFail($id);
            $club->delete();
            return response()->json("Club supprimé avec succès");
        } catch (\Exception $e) {
            return response()->json("Problème de suppression de club {$e->getMessage()}");
        }
    }

    public function showClubsByCity($city)
    {
        try {
            $clubs = Club::where('ville', $city)->with('terrains')->get();
            return response()->json($clubs);
        } catch (\Exception $e) {
            return response()->json("Sélection impossible {$e->getMessage()}");
        }
    }

    public function clubsPaginate()
    {
        try {
            $perPage = request()->input('pageSize', 10);
            $clubs = Club::with('terrains')->paginate($perPage);
            return response()->json([
                'clubs' => $clubs->items(),
                'totalPages' => $clubs->lastPage(),
            ]);
        } catch (\Exception $e) {
            return response()->json("Sélection impossible {$e->getMessage()}");
        }
    }
}