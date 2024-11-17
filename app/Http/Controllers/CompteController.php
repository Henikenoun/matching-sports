<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CompteController extends Controller
{
    /**
     * Affiche la liste de tous les comptes.
     */
    public function index()
    {
        try {
            $comptes = Compte::all();
            return response()->json($comptes, 200);
        } catch (\Exception $e) {
            return response()->json("Sélection impossible {$e->getMessage()}");
        }
    }

    /**
     * Enregistre un nouveau compte dans la base de données.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nom' => 'required|string|max:255',
                'prénom' => 'required|string|max:255',
                'date_de_naissance' => 'required|date',
                'ville' => 'required|string|max:255',
                'numéro_de_téléphone' => 'required|string|max:20',
                'email' => 'required|string|email|max:255|unique:comptes',
                'mot_de_passe' => 'required|string|min:8',
                'transport' => 'nullable|string|max:255',
                'photo' => 'nullable|string|max:255',
                'disponibilité' => 'required|boolean',
            ]);

            $validatedData['mot_de_passe'] = Hash::make($validatedData['mot_de_passe']);

            $compte = Compte::create($validatedData);
            return response()->json($compte, 201);
        } catch (\Exception $e) {
            return response()->json("Insertion impossible {$e->getMessage()}");
        }
    }

    /**
     * Affiche les détails d'un compte spécifique.
     */
    public function show($id)
    {
        try {
            $compte = Compte::findOrFail($id);
            return response()->json($compte);
        } catch (\Exception $e) {
            return response()->json("Problème de récupération des données {$e->getMessage()}");
        }
    }

    /**
     * Met à jour un compte existant dans la base de données.
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'nom' => 'sometimes|required|string|max:255',
                'prénom' => 'sometimes|required|string|max:255',
                'date_de_naissance' => 'sometimes|required|date',
                'ville' => 'sometimes|required|string|max:255',
                'numéro_de_téléphone' => 'sometimes|required|string|max:20',
                'email' => 'sometimes|required|string|email|max:255|unique:comptes,email,' . $id,
                'mot_de_passe' => 'sometimes|required|string|min:8',
                'transport' => 'nullable|string|max:255',
                'photo' => 'nullable|string|max:255',
                'disponibilité' => 'sometimes|required|boolean',
            ]);

            if ($request->has('mot_de_passe')) {
                $validatedData['mot_de_passe'] = Hash::make($validatedData['mot_de_passe']);
            }

            $compte = Compte::findOrFail($id);
            $compte->update($validatedData);

            return response()->json($compte);
        } catch (\Exception $e) {
            return response()->json("Problème de modification {$e->getMessage()}");
        }
    }

    /**
     * Supprime un compte existant de la base de données.
     */
    public function destroy($id)
    {
        try {
            $compte = Compte::findOrFail($id);
            $compte->delete();
            return response()->json("Compte supprimé avec succès");
        } catch (\Exception $e) {
            return response()->json("Problème de suppression de compte {$e->getMessage()}");
        }
    }
}