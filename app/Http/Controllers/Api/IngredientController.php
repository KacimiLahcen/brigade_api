<?php

namespace App\Http\Controllers\Api;

use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    public function index()
    {
        return response()->json(Ingredient::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:ingredients,name',
            'tags' => 'required|array', 
            'tags.*' => 'string'
        ]);

        $ingredient = Ingredient::create($validated);

        return response()->json([
            'message' => 'Ingrédient créé avec succès',
            'ingredient' => $ingredient
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $ingredient = Ingredient::findOrFail($id);

        $validated = $request->validate([
            'name' => 'string|unique:ingredients,name,' . $id,
            'tags' => 'array',
            'tags.*' => 'string'
        ]);

        $ingredient->update($validated);

        return response()->json($ingredient);
    }

    public function destroy($id)
    {
        $ingredient = Ingredient::findOrFail($id);
        
        $ingredient->delete();

        return response()->json(['message' => 'Ingrédient supprimé']);
    }
}