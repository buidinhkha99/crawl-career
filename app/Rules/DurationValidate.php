<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Facades\Validator;

class DurationValidate  implements InvokableRule
{
    public function __invoke($attribute, $value, $fail)
    {

        $validator = Validator::make(
            [
                $attribute => $value,
            ],
            [
                $attribute => 'required|min:0|integer',
            ],
            [
            ],
            [
                $attribute => __('Duration'),
            ]
        );

        if ($validator->fails()) {
            $fail($validator->errors()->first());
        }
    }
}
