<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Facades\Validator;

class QuestionAmountValidate  implements InvokableRule
{
    public function __invoke($attribute, $value, $fail)
    {

        $validator = Validator::make(
            [
                $attribute => $value,
            ],
            [
                $attribute => 'required|min:1|integer',
            ],
            [
            ],
            [
                $attribute => __('Question Amount'),
            ]
        );

        if ($validator->fails()) {
            $fail($validator->errors()->first());
        }
    }
}
