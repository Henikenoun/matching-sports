<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = Categorie::all();

            if ($categories->isEmpty()) {
                return response()->json(['message' => 'Aucune catégorie trouvée.'], 404);
            }

            return response()->json($categories, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la récupération des catégories.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            
            $categorie = Categorie::create([
                'name' => $request->input('name'),
                'desc' => $request->input('desc'),
                'photo' => $request->input('photo')
            ]);

            return response()->json($categorie, 201); 
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la création de la catégorie.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $categorie = Categorie::findOrFail($id);
            return response()->json($categorie, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Catégorie non trouvée.'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la récupération de la catégorie.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $categorie = Categorie::findOrFail($id);

            
            $categorie->update([
                'name' => $request->input('name', $categorie->name),  
                'desc' => $request->input('desc', $categorie->desc),
                'photo' => $request->input('photo', $categorie->photo)
            ]);

            return response()->json($categorie, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Catégorie non trouvée.'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la mise à jour de la catégorie.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $categorie = Categorie::findOrFail($id);
            $categorie->delete();

            return response()->json(['message' => 'Catégorie supprimée avec succès.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Catégorie non trouvée.'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la suppression de la catégorie.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
