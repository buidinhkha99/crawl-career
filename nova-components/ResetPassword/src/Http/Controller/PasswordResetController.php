<?php

namespace Salt\ResetPassword\Http\Controller;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Salt\ResetPassword\Http\Requests\PasswordResetRequest;

class PasswordResetController extends Controller
{
    public function reset(PasswordResetRequest $request)
    {
        $user = $request->user();
        $user->password = Hash::make($request->new_password);
        $user->save();
        Auth::logout();
    }

    public function getMinPasswordSize()
    {
        return response(['minpassw' => config('nova-password-reset.min_password_size', 8)]);
    }
}
