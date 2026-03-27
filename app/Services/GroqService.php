<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqService
{
    public function analyzeDish(array $dietaryTags, string $dishName, array $ingredients): array
    {
        $prompt = $this->buildPrompt($dietaryTags, $dishName, $ingredients);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
            'Content-Type'  => 'application/json',
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model'       => 'llama3-8b-8192',
            'temperature' => 0.2,
            'messages'    => [
                [
                    'role'    => 'user',
                    'content' => $prompt,
                ]
            ],
        ]);

        if (!$response->successful()) {
            throw new \RuntimeException('Groq API error: ' . $response->body());
        }

        $text = trim($response->json()['choices'][0]['message']['content'] ?? '');

        return $this->parseResponse($text);
    }

    private function buildPrompt(array $dietaryTags, string $dishName, array $ingredients): string
    {
        $ingredientsJson  = json_encode($ingredients);
        $restrictionsJson = json_encode($dietaryTags);

        return <<<PROMPT
Analyze the nutritional compatibility between this dish and the user's dietary restrictions.

DISH: {$dishName}
INGREDIENT TAGS: {$ingredientsJson}
USER RESTRICTIONS: {$restrictionsJson}

Tag mapping rules:
- "vegan" restriction conflicts with: contains_meat, contains_lactose
- "no_sugar" restriction conflicts with: contains_sugar
- "no_cholesterol" restriction conflicts with: contains_cholesterol
- "gluten_free" restriction conflicts with: contains_gluten
- "no_lactose" restriction conflicts with: contains_lactose

Calculate score: start at 100, subtract 25 for each conflict found.

Respond ONLY with this JSON (no markdown, no explanation):
{"score": <0-100>, "warning_message": "<in French if score < 50, else empty string>"}
PROMPT;
    }

    private function parseResponse(string $text): array
    {
        $text = preg_replace('/```json|```/', '', $text);
        $text = trim($text);

        preg_match('/\{.*\}/s', $text, $matches);
        $data = json_decode($matches[0] ?? '{}', true);

        if (!isset($data['score'])) {
            Log::warning('Groq response parsing failed', ['text' => $text]);
            return [
                'score'           => 50,
                'label'           => '🟡 Recommended with notes',
                'warning_message' => null,
            ];
        }

        $score   = max(0, min(100, (int) $data['score']));
        $warning = $data['warning_message'] ?? '';

        $label = match (true) {
            $score >= 80 => '✅ Highly Recommended',
            $score >= 50 => '🟡 Recommended with notes',
            default      => '⚠️ Not Recommended',
        };

        return [
            'score'           => $score,
            'label'           => $label,
            'warning_message' => $score < 50 ? $warning : null,
        ];
    }
}
