<?php

namespace App\Http\Controllers;

use App\Models\PageStatic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function logout(Request $request)
    {
        if (PageStatic::where('path', '/')->exists()) {
            if (! Auth::user()) {
                return redirect('/');
            }

            Auth::logout();

            return redirect('/');
        }

        abort(404, 'Page not found!');
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required'],
            'password' => ['required'],
            'redirect_after_login' => ['required'],
        ]);

        $credentials = [
            'employee_code' => $request->get('name'),
            'password' => $request->get('password'),
        ];

        if (Auth::user()) {
            return redirect($request->get('redirect_after_login'));
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if (PageStatic::where('path', $request->get('redirect_after_login'))->exists()) {
                return redirect($request->get('redirect_after_login'));
            }

            return back()->withErrors([
                'error' => __('Unauthenticated'),
            ])->onlyInput('error');
        }

        return back()->withErrors([
            'error' => __('Unauthenticated'),
        ])->onlyInput('error');
    }
}
