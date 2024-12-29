<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\categorie;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $articles = Article::all();
            return response()->json($articles);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la récupération des articles.',
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
            $article = Article::create([
                'ref' => $request->input('ref'),
                'name' => $request->input('name'),
                'desc' => $request->input('desc'),
                'photo' => $request->input('photo'),
                'quantity' => $request->input('quantity'),
                'price' => $request->input('price'),
                'couleur' => $request->input('couleur'), 
                'remise' => $request->input('remise', 0),
                'offre' => $request->input('offre', false),
                'categorie_id' => $request->input('categorie_id'),
                'shop_id' => $request->input('shop_id')
            ]);

            return response()->json($article, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la création de l\'article.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        try {
            return response()->json($article); 
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Article non trouvé.',
                'details' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        try {
            
            $article->update([
                'ref' => $request->input('ref', $article->ref),
                'name' => $request->input('name', $article->name),
                'desc' => $request->input('desc', $article->desc),
                'photo' => $request->input('photo', $article->photo),
                'quantity' => $request->input('quantity', $article->quantity),
                'price' => $request->input('price', $article->price),
                'couleur' => $request->input('couleur', $article->couleur), 
                'remise' => $request->input('remise', $article->remise),
                'offre' => $request->input('offre', $article->offre),
                'categorie_id' => $request->input('categorie_id', $article->categorie_id),
                'shop_id' => $request->input('shop_id', $article->shop_id)
            ]);

            return response()->json($article); 
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la mise à jour de l\'article.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        try {
            $article->delete(); 
            return response()->json(['message' => 'Article supprimé avec succès.'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la suppression de l\'article.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function addColor(Request $request, $id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Article non trouvé'], 404);
        }
        $colors = $article->couleurs ?? [];

        if (!in_array($request->input('couleur'), $colors)) {
            $colors[] = $request->input('couleur');
        }

        $article->couleurs = $colors;
        $article->save();

        return response()->json($article, 200);
    }
    
}
