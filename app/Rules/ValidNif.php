<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidNif implements ValidationRule
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

        $nif = strtoupper(trim($value));

        if (!$this->isValidDni($nif) && !$this->isValidNie($nif)) {
            $fail('El :attribute introducido no es un NIF/NIE válido.');
        }
    }

    private function isValidDni(string $dni): bool
    {
        if (preg_match('/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/i', $dni)) {
            $letters = "TRWAGMYFPDXBNJZSQVHLCKE";
            $number = (int) substr($dni, 0, 8);
            $letter = substr($dni, -1);
            return $letter === $letters[$number % 23];
        }
        return false;
    }

    private function isValidNie(string $nie): bool
    {
        if (preg_match('/^[XYZ][0-9]{7}[TRWAGMYFPDXBNJZSQVHLCKE]$/i', $nie)) {
            $letters = "TRWAGMYFPDXBNJZSQVHLCKE";
            $replacements = ['X' => '0', 'Y' => '1', 'Z' => '2'];
            $firstLetter = substr($nie, 0, 1);
            $numberStr = str_replace(array_keys($replacements), array_values($replacements), $firstLetter) . substr($nie, 1, 7);
            $number = (int) $numberStr;
            $letter = substr($nie, -1);
            return $letter === $letters[$number % 23];
        }
        return false;
    }
}
