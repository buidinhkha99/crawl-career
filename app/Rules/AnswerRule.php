<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Collection;

class AnswerRule implements Rule
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
        return $value->every(fn ($correct_answer) => $this->answers->get((int) $correct_answer - 1) !== null);
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return __('Invalid correct answer');
    }
}
