<?php

namespace App\Http\Controllers;

use App\Models\Plat;
use App\Models\Recommendations;
use App\Jobs\AnalyzeCompatibilityJob;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    
    // POST /api/recommendations/analyze/{plate_id}
    public function analyze(Request $request, $plate_id)
    {
        $user = $request->user();
        $plate = Plat::findOrFail($plate_id);

        //make new recommandation
        $recommendation = Recommendations::create([
            'user_id' => $user->id,
            'plate_id' => $plate->id,
            'status' => 'processing'
        ]);

        //(Queue)
        AnalyzeCompatibilityJob::dispatch($recommendation);

        return response()->json([
            'status' => 'processing',
            'message' => 'Analyse Loading...',
            'recommendation_id' => $recommendation->id
        ]);
    }

    // GET /api/recommendations :show all user's recomndations
    public function index(Request $request)
    {
        return response()->json($request->user()->recommendations()->with('plate')->get());
    }
}