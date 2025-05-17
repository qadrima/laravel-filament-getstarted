<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueRecurringDays implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $days = collect($value)->pluck('day')->filter();

        if ($days->count() !== $days->unique()->count()) {
            $fail('Duplicate days are not allowed in the recurring schedule.');
        }
    }
}
