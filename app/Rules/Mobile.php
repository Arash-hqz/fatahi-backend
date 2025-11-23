<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Mobile implements Rule
{
    /**
     * Regular expression for E.164-like mobile numbers.
     * Allows optional leading +, followed by 8-15 digits (no leading 0).
     * Examples: +15551234567, 15551234567
     *
     * @var string
     */
    protected string $pattern = '/^\+?[1-9]\d{7,14}$/';

    /**
     * Create a new rule instance.
     * You may allow customizing the pattern in future via constructor args.
     */
    public function __construct()
    {
        // nothing for now
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if (is_null($value) || $value === '') {
            return false;
        }

        if (! is_string($value) && ! is_numeric($value)) {
            return false;
        }

        return (bool) preg_match($this->pattern, (string) $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The :attribute must be a valid mobile number in international format (e.g. +15551234567).';
    }
}
