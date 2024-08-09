<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Salt\ResetPassword\Http\Controller\PasswordResetController;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

// Route::get('/', function (Request $request) {
//     //
// });

Route::post('/', [PasswordResetController::class, 'reset'])
    ->name('laravel-nova-reset-password');

Route::get('min-password-size', [PasswordResetController::class, 'getMinPasswordSize'])
    ->name('laravel-nova-reset-password-min-pass-size');
