<?php

namespace App\Support;

class PhoneNumber
{
    public static function stripSpaces(string $phone): string
    {
        return preg_replace('/\s+/', '', $phone) ?? '';
    }

    public static function normalize(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if (str_starts_with($digits, '256')) {
            return '+'.$digits;
        }

        if (str_starts_with($digits, '0')) {
            return '+256'.substr($digits, 1);
        }

        return '+256'.$digits;
    }

    /**
     * @return array<int, string>
     */
    public static function validationRules(bool $required = true): array
    {
        $rules = ['string', 'max:20', 'regex:/^(\+256|256|0)?7[0-9]{8}$/'];

        array_unshift($rules, $required ? 'required' : 'nullable');

        return $rules;
    }
}
