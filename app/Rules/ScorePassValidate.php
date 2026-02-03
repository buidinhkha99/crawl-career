<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Facades\Validator;

class ScorePassValidate  implements InvokableRule
{
    public function __invoke($attribute, $value, $fail)
    {

        $validator = Validator::make(
            [
                $attribute => $value,
            ],
            [
                $attribute => 'required|min:0|numeric|max:10',
            ],
            [
            ],
            [
                $attribute => __('Score Pass'),
            ]
        );

        if ($validator->fails()) {
            $fail($validator->errors()->first());
        }
    }
}
