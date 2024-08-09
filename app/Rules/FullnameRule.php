<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FullnameRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (preg_match('~[0-9]+~', $value)) {
            return false;
        }

        if (strpbrk($value, '[`!@#$%^&*()_+\-=\[\]{};":"\\|,.<>\/?~]')) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Invalid :attribute');
    }
}
