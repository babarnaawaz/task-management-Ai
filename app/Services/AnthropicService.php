<?php

namespace App\Services;

use App\Exceptions\AnthropicApiException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnthropicService
{
    private const API_URL = 'https://api.anthropic.com/v1/messages';
    private const MODEL = 'claude-sonnet-4-20250514';
    private const MAX_TOKENS = 4096;
    private const ANTHROPIC_VERSION = '2023-06-01';

    private string $apiKey;

    /**
     * Create a new service instance.
     */
    public function __construct()
    {
        $this->apiKey = config('services.anthropic.api_key', '');
        
        if (empty($this->apiKey)) {
            Log::warning('Anthropic API key is not configured');
        }
    }

    /**
     * Break down a task into subtasks using AI.
     */
    public function breakdownTask(string $taskTitle, string $taskDescription, array $options = []): array
    {
        if (empty($this->apiKey)) {
            throw new AnthropicApiException('Anthropic API key is not configured');
        }

        $prompt = $this->buildBreakdownPrompt($taskTitle, $taskDescription, $options);

        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'anthropic-version' => self::ANTHROPIC_VERSION,
                'content-type' => 'application/json',
            ])
            ->timeout(60)
            ->post(self::API_URL, [
                'model' => self::MODEL,
                'max_tokens' => self::MAX_TOKENS,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
            ]);

            if (!$response->successful()) {
                throw new AnthropicApiException(
                    'Anthropic API request failed: ' . $response->body(),
                    $response->status()
                );
            }

            $data = $response->json();
            
            return $this->parseAnthropicResponse($data);
            
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Anthropic API connection error', [
                'error' => $e->getMessage(),
                'task_title' => $taskTitle,
            ]);

            throw new AnthropicApiException(
                'Failed to connect to Anthropic API: ' . $e->getMessage(),
                0,
                $e
            );
            
        } catch (AnthropicApiException $e) {
            throw $e;
            
        } catch (\Exception $e) {
            Log::error('Anthropic API error', [
                'error' => $e->getMessage(),
                'task_title' => $taskTitle,
            ]);

            throw new AnthropicApiException(
                'Failed to process task breakdown: ' . $e->getMessage(),
                0,
                $e
            );
        }
    }

    /**
     * Build the prompt for task breakdown.
     */
    private function buildBreakdownPrompt(string $title, string $description, array $options): string
    {
        $complexity = $options['complexity_level'] ?? 'moderate';
        $focusAreas = $options['focus_areas'] ?? [];

        $prompt = "You are a project management expert. Break down the following task into actionable subtasks.\n\n";
        $prompt .= "Task Title: {$title}\n";
        $prompt .= "Task Description: {$description}\n";
        $prompt .= "Complexity Level: {$complexity}\n";

        if (!empty($focusAreas)) {
            $prompt .= "Focus Areas: " . implode(', ', $focusAreas) . "\n";
        }

        $prompt .= "\nProvide a JSON array of subtasks with the following structure:\n";
        $prompt .= "[\n";
        $prompt .= "  {\n";
        $prompt .= "    \"title\": \"Subtask title\",\n";
        $prompt .= "    \"description\": \"Detailed description\",\n";
        $prompt .= "    \"estimated_hours\": 2\n";
        $prompt .= "  }\n";
        $prompt .= "]\n\n";
        $prompt .= "Guidelines:\n";
        $prompt .= "- Create 3-8 subtasks depending on complexity\n";
        $prompt .= "- Each subtask should be specific and actionable\n";
        $prompt .= "- Include estimated hours for each subtask (1-40 hours)\n";
        $prompt .= "- Order subtasks logically from first to last\n";
        $prompt .= "- Return ONLY the JSON array, no additional text or markdown formatting\n";

        return $prompt;
    }

    /**
     * Parse the response from Anthropic API.
     */
    private function parseAnthropicResponse(array $response): array
    {
        if (!isset($response['content'][0]['text'])) {
            throw new AnthropicApiException('Invalid response format from Anthropic API');
        }

        $text = $response['content'][0]['text'];
        
        // Extract JSON from response (Claude might wrap it in markdown)
        $text = preg_replace('/```json\s*|\s*```/', '', $text);
        $text = trim($text);

        $subtasks = json_decode($text, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('JSON parsing error', [
                'error' => json_last_error_msg(),
                'response' => $text,
            ]);
            
            throw new AnthropicApiException('Failed to parse JSON response: ' . json_last_error_msg());
        }

        if (!is_array($subtasks)) {
            throw new AnthropicApiException('Expected array of subtasks, got: ' . gettype($subtasks));
        }

        // Validate subtask structure
        foreach ($subtasks as $index => $subtask) {
            if (!isset($subtask['title'])) {
                throw new AnthropicApiException("Subtask at index {$index} missing required 'title' field");
            }
        }

        return $subtasks;
    }

    /**
     * Check if the API key is configured.
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }
}