<?php

namespace App\Services;

use App\Models\AiImageModel;

class AiService
{
    /**
     * Model Router Task Mappings
     * Automatically routes tasks to the best Gemini model for speed, cost, or reasoning depth.
     */
    protected array $taskModelMap = [
        'chat'      => 'gemini-3.5-flash-lite', // Interactive chatbot widget & turns
        'fast'      => 'gemini-3.5-flash-lite', // Fast short text generation, summaries & bullet points
        'balanced'  => 'gemini-3.5-flash-lite', // Career advice, salary negotiation, cover letters
        'reasoning' => 'gemini-3.5-flash-lite', // CV scoring/reviews, interview evaluations, aptitude test generation
        'audio'     => 'gemini-3.1-flash',      // Multimodal audio transcription & speech processing
    ];

    protected $apiKey;
    protected $model;

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
        $this->model  = env('GEMINI_MODEL') ?: 'gemini-3.5-flash-lite';
    }

    /**
     * Resolve the target model for a given task type or custom model key
     */
    public function resolveModel(string $task = 'fast'): string
    {
        // Check if specific task override exists in environment variables (e.g. GEMINI_MODEL_CHAT)
        $envTaskKey = 'GEMINI_MODEL_' . strtoupper($task);
        if ($envTaskModel = env($envTaskKey)) {
            return $envTaskModel;
        }

        return $this->taskModelMap[$task] ?? ($this->taskModelMap['fast']);
    }

    /**
     * Get API URL for a specific model or task
     */
    protected function getApiUrl(string $task = 'fast'): string
    {
        $selectedModel = $this->resolveModel($task);
        return 'https://generativelanguage.googleapis.com/v1beta/models/' . $selectedModel . ':generateContent';
    }
}
