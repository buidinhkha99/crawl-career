<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GlobalSearchController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

//Route::get('/', [HomeController::class, 'index']);
//Route::get('/{slug?}', [HomeController::class, 'show']);

Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->middleware(['auth']);

Route::post('/form', [PageController::class, 'submit']);
Route::post('/subscribe', [PageController::class, 'subscribe']);

Route::get('/assets/{slug}', [PageController::class, 'customizePage']);
Route::get('/search', [GlobalSearchController::class, 'show']);
Route::post('/answer', [\App\Http\Controllers\QuizController::class, 'saveAnswer']);

Route::get('/media/examination/{id}', [\App\Http\Controllers\MediaController::class, 'streamExamPdf'])->middleware([
    'nova',
    \Laravel\Nova\Http\Middleware\Authenticate::class,
    \Laravel\Nova\Http\Middleware\Authorize::class,
]);
Route::get('/media/report', [\App\Http\Controllers\MediaController::class, 'streamReportPdf'])->middleware([
    'nova',
    \Laravel\Nova\Http\Middleware\Authenticate::class,
    \Laravel\Nova\Http\Middleware\Authorize::class,
]);
