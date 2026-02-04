<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FutureDate implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $date = Carbon::parse($value);
            
            if ($date->isPast()) {
                $fail("The {$attribute} must be a future date.");
            }
        } catch (\Exception $e) {
            $fail("The {$attribute} is not a valid date.");
        }
    }
}