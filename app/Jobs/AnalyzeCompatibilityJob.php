<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Recommendations;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AnalyzeCompatibilityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $recommendation;

    public function __construct(Recommendations $recommendation)
    {
        $this->recommendation = $recommendation;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {

        $user = $this->recommendation->user;
        $plate = $this->recommendation->plate->load('ingredients');

        $userTags = $user->dietary_tags ?? []; // like:: ["vegan", "no_sugar"]
        $score = 100;
        $warnings = [];

        foreach ($plate->ingredients as $ingredient) {
            $ingTags = $ingredient->tags ?? []; // ecxmple ["contains_meat"]

            //comparing logic
            if (in_array('vegan', $userTags) && in_array('contains_meat', $ingTags)) {
                $score -= 50;
                $warnings[] = "Contient de la viande (incompatible avec Vegan)";
            }
            if (in_array('no_sugar', $userTags) && in_array('contains_sugar', $ingTags)) {
                $score -= 30;
                $warnings[] = "Contient du sucre";
            }
        }
        $label = 'Not Recommended';
        if ($score >= 80) $label = 'Highly Recommended';
        elseif ($score >= 50) $label = 'Recommended';


        $this->recommendation->update([
            'score' => max(0, $score),
            'label' => $label,
            'status' => 'ready',
            'warning_message' => implode(', ', $warnings)
        ]);
    }
}
