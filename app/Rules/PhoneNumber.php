<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }
        
        // Normalize: remove spaces, dashes, parentheses
        $normalized = preg_replace('/[\s\-\(\)]+/', '', $value);
        
        // Check if it's a valid phone number format
        if (!preg_match('/^\+?[0-9]+$/', $normalized)) {
            $fail(__('validation.custom.phone.regex'));
            return;
        }
        
        // Country-specific validation for CZ and SK
        if (preg_match('/^\+420/', $normalized)) {
            // Czech number: +420 followed by exactly 9 digits
            if (!preg_match('/^\+420[0-9]{9}$/', $normalized)) {
                $fail(__('validation.custom.phone.cz_format'));
                return;
            }
        } elseif (preg_match('/^\+421/', $normalized)) {
            // Slovak number: +421 followed by exactly 9 digits
            if (!preg_match('/^\+421[0-9]{9}$/', $normalized)) {
                $fail(__('validation.custom.phone.sk_format'));
                return;
            }
        } else {
            // Generic validation: 9-15 digits (E.164 standard allows up to 15)
            $digitsOnly = preg_replace('/[^0-9]/', '', $normalized);
            $digitCount = strlen($digitsOnly);
            
            if ($digitCount < 9 || $digitCount > 15) {
                $fail(__('validation.custom.phone.regex'));
                return;
            }
        }
    }
}
