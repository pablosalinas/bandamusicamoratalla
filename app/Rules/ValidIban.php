<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidIban implements ValidationRule
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

        $iban = strtoupper(str_replace(' ', '', $value));

        if (!$this->isValidIban($iban)) {
            $fail('El :attribute introducido no es un IBAN válido.');
        }
    }

    private function isValidIban(string $iban): bool
    {
        // Check basic length and format (letters + numbers)
        if (!preg_match('/^[A-Z]{2}[0-9]{2}[A-Z0-9]{1,30}$/', $iban)) {
            return false;
        }

        // Move first 4 chars to the end
        $rearranged = substr($iban, 4) . substr($iban, 0, 4);

        // Convert letters to numbers (A=10, B=11, ... Z=35)
        $numericIban = '';
        foreach (str_split($rearranged) as $char) {
            if (ctype_alpha($char)) {
                $numericIban .= ord($char) - 55; // ord('A') is 65, so 65 - 55 = 10
            } else {
                $numericIban .= $char;
            }
        }

        // Calculate Modulo 97 (using bcmath if available, or manual string math)
        // Since the number is too large for standard integer modulo, we do it in chunks
        $remainder = 0;
        foreach (str_split($numericIban, 7) as $chunk) {
            $remainder = ($remainder . $chunk) % 97;
        }

        return $remainder === 1;
    }
}
