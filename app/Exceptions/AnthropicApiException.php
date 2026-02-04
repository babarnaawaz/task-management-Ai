<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AnthropicApiException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     */
    public function render($request): JsonResponse
    {
        return response()->json([
            'error' => 'AI Service Error',
            'message' => $this->getMessage(),
        ], $this->getCode() ?: 500);
    }

    /**
     * Report the exception.
     */
    public function report(): void
    {
        Log::error('Anthropic API Exception', [
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'trace' => $this->getTraceAsString(),
        ]);
    }
}