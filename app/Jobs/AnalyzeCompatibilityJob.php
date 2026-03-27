<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Recommendations;
use App\Services\GroqService;

class AnalyzeCompatibilityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $recommendation;

    public function __construct(Recommendations $recommendation)
    {
        $this->recommendation = $recommendation;
    }

    public function handle(GroqService $groq): void
    {
        $user  = $this->recommendation->user;
        $plate = $this->recommendation->plate->load('ingredients');

        $dietaryTags = $user->dietary_tags ?? [];

        // collect all ingredient tags as a flat array
        $ingredientTags = $plate->ingredients
            ->pluck('tags')
            ->flatten()
            ->unique()
            ->values()
            ->toArray();

        try {
            $result = $groq->analyzeDish($dietaryTags, $plate->name, $ingredientTags);

            $this->recommendation->update([
                'score'           => $result['score'],
                'label'           => $result['label'],
                'status'          => 'ready',
                'warning_message' => $result['warning_message'],
            ]);
        } catch (\Throwable $e) {
            Log::error('AnalyzeCompatibilityJob failed: ' . $e->getMessage());

            $this->recommendation->update([
                'status'          => 'ready',
                'score'           => 50,
                'label'           => '🟡 Recommended with notes',
                'warning_message' => null,
            ]);
        }
    }
}
