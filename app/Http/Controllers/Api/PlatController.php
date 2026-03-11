<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plat;

class PlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //get all plats for the authenticated user
        return response()->json($request->user()->plats()->with('category')->get()); //eager loading (with)
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id'
        ]);

        $plat = $request->user()->plats()->create($fields);

        return response()->json($plat, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Plat $plat)
    {
        return response()->json($plat->load('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plat $plat)
    {
        $fields = $request->validate([
            'title' => 'string',
            'price' => 'numeric',
            'category_id' => 'exists:categories,id'
        ]);

        $plat->update($fields);
        return response()->json($plat);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plat $plat)
    {
        $plat->delete();
        return response()->json(['message' => ' Plat deleted']);
    }
}
