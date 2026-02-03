<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Collection;

class CountAnswerRule implements Rule
{
    public Collection $answers;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Collection $answers)
    {
        $this->answers = $answers;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     */
    public function passes($attribute, $value): bool
    {
        return $this->answers->count();
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return __('There must be at least one answer');
    }
}
