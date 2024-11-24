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
            // Retrieve all clubs with their associated terrains
            $clubs = Club::with('terrains')->get();
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
        $request->validate([
            'nom' => 'required|string',
            'ville' => 'required|string',
            'adresse' => 'required|string',
            'numTel' => 'required|string',
            'email' => 'required|email',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        try {
            $club = new Club([
                'nom' => $request->input('nom'),
                'ville' => $request->input('ville'),
                'adresse' => $request->input('adresse'),
                'numTel' => $request->input('numTel'),
                'email' => $request->input('email'),
                'nbTerrain' => 0, // Set default value to 0
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude')
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

    /**
     * Display clubs by city.
     */
    public function showClubsByCity($city)
    {
        try {
            $clubs = Club::where('ville', $city)->get();
            return response()->json($clubs);
        } catch (\Exception $e) {
            return response()->json("Sélection impossible {$e->getMessage()}");
        }
    }

    /**
     * Display terrains in a specific club.
     */
    public function showTerrainsInClub($id)
    {
        try {
            $club = Club::findOrFail($id);
            return response()->json($club->terrains);
        } catch (\Exception $e) {
            return response()->json("Problème de récupération des terrains {$e->getMessage()}");
        }
    }
}
