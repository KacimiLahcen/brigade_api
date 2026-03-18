<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plat;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PlatController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plates = Plat::with(['category', 'ingredients'])
            ->where('is_available', true)
            ->get();
            
        return response()->json($plates);
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_available' => 'boolean',
            'ingredient_ids' => 'required|array', 
            'ingredient_ids.*' => 'exists:ingredients,id'
        ]);

        
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('plates', 'public');
        }

        $plate = Plat::create($validated);

        $plate->ingredients()->sync($request->ingredient_ids);

        return response()->json($plate->load('ingredients'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $plate = Plate::with(['category', 'ingredients'])->findOrFail($id);
        return response()->json($plate);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $plate = Plat::findOrFail($id);

        $validated = $request->validate([
            'name' => 'string|max:100',
            'price' => 'numeric|min:0',
            'category_id' => 'exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_available' => 'boolean',
            'ingredient_ids' => 'array',
            'ingredient_ids.*' => 'exists:ingredients,id'
        ]);

        if ($request->hasFile('image')) {
            if ($plate->image) {
                Storage::disk('public')->delete($plate->image);
            }
            $validated['image'] = $request->file('image')->store('plates', 'public');
        }

        $plate->update($validated);

        if ($request->has('ingredient_ids')) {
            $plate->ingredients()->sync($request->ingredient_ids);
        }

        return response()->json($plate->load('ingredients'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $plate = Plat::findOrFail($id);
        if ($plate->image) {
            Storage::disk('public')->delete($plate->image);
        }
        $plate->delete();
        return response()->json(['message' => 'Plat supprimé avec succès']);
    }
}
