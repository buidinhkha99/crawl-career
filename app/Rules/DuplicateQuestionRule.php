<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Collection;

class DuplicateQuestionRule implements Rule
{
    public Collection $question_duplicates;

    public Collection $key_duplicates;

    public Collection $questions;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Collection $question_duplicates, $questions)
    {
        $this->question_duplicates = $question_duplicates;
        $this->questions = $questions;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     */
    public function passes($attribute, $value): bool
    {
        if (! $this->question_duplicates->contains($value)) {
            return true;
        }

        $this->key_duplicates = $this->questions->filter(fn ($question) => $question === $value)->keys();

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Duplicate data in rows :rows', [
            'rows' => implode(', ', $this->key_duplicates->map(fn ($id) => $id + 3)->toArray()),
        ]);
    }
}
