<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ArrayAtLeastOneRequired implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $success = false;
        foreach ($value as $arrayElement) {
            if (null !== $arrayElement) {
                $success = true;
            }
        }

        if(!$success)
            $fail('At least one :attribute required.');
    }
}
