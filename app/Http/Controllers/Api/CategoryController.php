<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CategoryController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->query('active') === 'false') {
            return response()->json($query->get());
        }
        return response()->json($query->where('is_active', true)->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'color' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $category = Category::create($request->validated());

        return response()->json($category, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::with('plats')->findOrFail($id);
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'string|max:100|unique:categories,name,' . $id,
            'description' => 'nullable|string',
            'color' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $category->update($validated);
        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->plats()->where('is_available', true)->exists()) {
            return response()->json([
                'message' => 'Impossible de supprimer catégorie avec des plats actifs.'
            ], 422);
        }

        $category->delete();
        return response()->json(['message' => 'deleted with success']);
    }

    public function getPlates($id)
    {
        $category = Category::findOrFail($id);
        $plates = $category->plats()->where('is_available', true)->get();
        return response()->json($plates);
    }
}
