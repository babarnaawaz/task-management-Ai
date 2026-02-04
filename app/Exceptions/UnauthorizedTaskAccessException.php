<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class UnauthorizedTaskAccessException extends Exception
{
    /**
     * The exception message.
     */
    protected $message = 'You are not authorized to access this task';

    /**
     * The exception code.
     */
    protected $code = 403;

    /**
     * Render the exception as an HTTP response.
     */
    public function render($request): JsonResponse
    {
        return response()->json([
            'error' => 'Forbidden',
            'message' => $this->getMessage(),
        ], $this->code);
    }

    /**
     * Report the exception.
     */
    public function report(): void
    {
        Log::warning('Unauthorized task access attempt', [
            'message' => $this->getMessage(),
            'user_id' => auth()->id(),
            'url' => request()->url(),
        ]);
    }
}