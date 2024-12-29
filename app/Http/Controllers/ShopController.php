<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $shops = Shop::with('club')->get();

            if ($shops->isEmpty()) {
                return response()->json(['message' => 'Aucun shop trouvé.'], 404);
            }

            return response()->json($shops, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la récupération des shops.',
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
            $shop = Shop::create($request->all());

            return response()->json($shop, 201); 
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la création du shop.',
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
            $shop = Shop::with('club')->findOrFail($id);
            return response()->json($shop, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Shop non trouvé.'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la récupération du shop.',
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
            $shop = Shop::findOrFail($id);

            $shop->update($request->all());

            return response()->json($shop, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Shop non trouvé.'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la mise à jour du shop.',
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
            $shop = Shop::findOrFail($id);
            $shop->delete();

            return response()->json(['message' => 'Shop supprimé avec succès.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Shop non trouvé.'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la suppression du shop.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function addPhotos(Request $request, $id)
    {
        // Valider que les photos sont bien présentes dans la requête
        $request->validate([
            'photos' => 'required|array', // Attente d'un tableau de photos
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Chaque photo doit être une image valide
        ]);

        try {
            $shop = Shop::findOrFail($id); // Trouver le shop par ID

            // Télécharger les photos et obtenir leurs URLs
            $photos = [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('photos', 'public'); // Stocker les photos dans 'storage/app/public/photos'
                $photos[] = Storage::url($path); // Obtenir l'URL publique de la photo
            }

            // Ajouter les nouvelles photos au tableau existant (si présent)
            $existingPhotos = $shop->photos ? json_decode($shop->photos, true) : [];
            $shop->photos = json_encode(array_merge($existingPhotos, $photos)); // Mettre à jour les photos

            $shop->save(); // Sauvegarder les modifications
            return response()->json($shop, 200); // Retourner le shop mis à jour

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de l\'ajout des photos.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer toutes les catégories d'un magasin.
     */
    public function getCategoriesByShop($shopId)
    {
        $shop = Shop::find($shopId);

        if (!$shop) {
            return response()->json(['message' => 'Magasin non trouvé'], 404);
        }

        // Récupérer toutes les catégories associées au magasin
        //pluck : permet de récupérer toutes les catégories uniques liées aux articles du magasin.
        $categories = $shop->articles()->with('categorie')->get()->pluck('categorie')->unique('id');

        return response()->json($categories);
    }
    public function getArticlesByCategoryAndShop($shopId, $categoryId)
    {
        $shop = Shop::find($shopId);

        if (!$shop) {
            return response()->json(['message' => 'Magasin non trouvé'], 404);
        }

        $category = Categorie::find($categoryId);
        if (!$category) {
            return response()->json(['message' => 'Catégorie non trouvée'], 404);
        }

        $articles = $shop->articles()
                         ->where('categorie_id', $categoryId)
                         ->get();

        return response()->json($articles);
    }
}
