<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plat;
use App\Models\Recommendations;
use App\Jobs\AnalyzeCompatibilityJob;
use Illuminate\Http\Request;

class RecommendationsController extends Controller
{
    // POST /api/recommendations/analyze/{plate_id}
    public function analyze(Request $request, $plate_id)
    {
        $user  = $request->user();
        $plate = Plat::findOrFail($plate_id);

        $recommendation = Recommendations::create([
            'user_id'  => $user->id,
            'plate_id' => $plate->id,
            'status'   => 'processing',
        ]);

        AnalyzeCompatibilityJob::dispatch($recommendation);

        return response()->json([
            'status'            => 'processing',
            'message'           => 'Analyse en cours...',
            'recommendation_id' => $recommendation->id,
        ], 202);
    }

    // GET /api/recommendations
    public function index(Request $request)
    {
        $recommendations = $request->user()
            ->recommendations()
            ->with('plate')
            ->latest()
            ->get();

        return response()->json($recommendations);
    }

    // GET /api/recommendations/{plate_id}
    public function show(Request $request, $plate_id)
    {
        $recommendation = Recommendations::where('user_id', $request->user()->id)
            ->where('plate_id', $plate_id)
            ->latest()
            ->firstOrFail();

        return response()->json($recommendation);
    }
}
