<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UserNameRule implements Rule
{
    public string $field;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $field)
    {
        $this->field = $field;
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
        if (empty($value)) {
            return false;
        }
        if (! is_numeric($value)) {
            return false;
        }

        if (strlen($value) > 30) {
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
        return __('Invalid :attribute', [
            'attribute' => $this->field,
        ]);
    }
}
