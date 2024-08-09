<?php

namespace Salt\ResetPassword\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordResetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $minPasswordSize = config('nova-password-reset.min_password_size', 8);

        return [
            'current_password' => ['required',
                Password::default(),
                function ($attribute, $value, $fail) {
                    if (! Hash::check($value, Auth::user()->password)) {
                        return $fail(__('The current password is incorrect'));
                    }
                },
            ],
            'new_password' => ['required', Password::default(), function ($attribute, $value, $fail) {
                if (Hash::check($value, Auth::user()->password)) {
                    return $fail(__('The new password is the same as the current password'));
                }
            }],
            'confirm_new_password' => "required|string|min:$minPasswordSize|same:new_password",
        ];
    }
}
