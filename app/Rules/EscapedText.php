<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EscapedText implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (str_contains($value, '<?php')) {
            $fail('Invalid value detected');
            return;
        }

        if (str_contains($value, '@php')) {
            $fail('Invalid value detected');
            return;
        }

        if (str_contains($value, '{{')) {
            $fail('Invalid value detected: \'{{\'');
            return;
        }

        if (str_contains($value, '}}')) {
            $fail('Invalid value detected: \'}}\'');
            return;
        }
    }
}
