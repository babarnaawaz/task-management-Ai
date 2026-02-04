<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPriority implements ValidationRule
{
    /**
     * The allowed priority values.
     */
    protected array $allowedPriorities = ['low', 'medium', 'high', 'urgent'];

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!in_array($value, $this->allowedPriorities, true)) {
            $fail("The {$attribute} must be one of: " . implode(', ', $this->allowedPriorities));
        }
    }
}