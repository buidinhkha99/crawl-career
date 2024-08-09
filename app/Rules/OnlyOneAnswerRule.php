<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Collection;

class OnlyOneAnswerRule implements Rule
{
    public Collection $question_types;

    public string $question_type;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Collection $question_types, string $question_type)
    {
        $this->question_types = $question_types;
        $this->question_type = $question_type;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     */
    public function passes($attribute, $value): bool
    {
        if ($this->question_types->get($this->question_type) === \App\Enums\QuestionType::One_Answer && $value->count() > 1) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return __('This type of question has only one correct answer');
    }
}
