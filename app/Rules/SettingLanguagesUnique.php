<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SettingLanguagesUnique implements Rule
{
    protected $request;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
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
        $languages = collect(json_decode($this->request->get('languages')));

        return $languages->duplicates('key')->count() === 0 && $languages->duplicates('value')->count() === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Languages must unique.';
    }
}
