<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plat;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Recommendations;

class AdminController extends Controller
{
    // GET /api/admin/stats
    public function stats()
    {
        $mostRecommended = Recommendations::selectRaw('plate_id, AVG(score) as avg_score')
            ->where('status', 'ready')
            ->groupBy('plate_id')
            ->orderByDesc('avg_score')
            ->with('plate')
            ->first();

        $leastRecommended = Recommendations::selectRaw('plate_id, AVG(score) as avg_score')
            ->where('status', 'ready')
            ->groupBy('plate_id')
            ->orderBy('avg_score')
            ->with('plate')
            ->first();

        $topCategory = Category::withCount('plats')
            ->orderByDesc('plats_count')
            ->first();

        return response()->json([
            'total_plates'          => Plat::count(),
            'total_categories'      => Category::count(),
            'total_ingredients'     => Ingredient::count(),
            'total_recommendations' => Recommendations::count(),
            'most_recommended'      => $mostRecommended,
            'least_recommended'     => $leastRecommended,
            'top_category'          => $topCategory,
        ]);
    }
}
