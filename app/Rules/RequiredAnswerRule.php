<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RequiredAnswerRule implements Rule
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
     */
    public function passes($attribute, $value): bool
    {
        if ($value->first() === '') {
        return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return __('The :attribute field is required.', [
            'attribute' => __('Answer'),
        ]);
    }
}
