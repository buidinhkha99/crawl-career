<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ExamRandomController;
use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which

| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('public/config', function (Request $request) {
    return [
        'required_auth' => env('VIMICO_REQUIRED_AUTH', false),
        'auth' => env('VIMICO_AUTH', true)
    ];
});

Route::get('logout', [ApiAuthController::class, 'logout'])->middleware('auth:api');
Route::get('/topics', [TopicController::class, 'show']);
Route::get('/topics/{id}', [TopicController::class, 'detail']);
Route::get('/topics-lesson', [TopicController::class, 'getTopicAndLesson']);
Route::get('/topics/{id}/lessons', [TopicController::class, 'lessons']);
Route::get('/topics/{id}/wrong-answer', [TopicController::class, 'getWrongAnswer']);

Route::get('/lessons/{id}', [\App\Http\Controllers\LessonController::class, 'show']);
Route::post('/lessons/{id}/submit', [\App\Http\Controllers\LessonController::class, 'submit']);

Route::get('/exam-random', [ExamRandomController::class, 'topics']);

Route::get('/mock-quizzes', [\App\Http\Controllers\MockQuizController::class, 'show']);
Route::get('/mock-quizzes/{id}', [\App\Http\Controllers\MockQuizController::class, 'detail']);
Route::post('/mock-quizzes/{id}/submit', [\App\Http\Controllers\QuizController::class, 'saveAnswerMockQuiz']);
Route::delete('/mock-quizzes/{id}/reset', [\App\Http\Controllers\QuizController::class, 'reset']);

Route::get('/summary-info', [\App\Http\Controllers\HomeController::class, 'summary']);

Route::post('/questions/{id}/submit', [\App\Http\Controllers\QuestionController::class, 'submit']);
Route::delete('/questions/{id}/reset', [\App\Http\Controllers\QuestionController::class, 'reset']);

Route::post('/register', [\App\Http\Controllers\ApiAuthController::class, 'register']);
Route::put('/me', [\App\Http\Controllers\ApiAuthController::class, 'update']);
Route::get('/me', [\App\Http\Controllers\ApiAuthController::class, 'show']);

// check token is not exists or expired
Route::get('/token', [\App\Http\Controllers\ApiAuthController::class, 'tokenExist']);

Route::middleware('auth:api')->group(function () {
    Route::get('classroom/attending/{id}', function (Request $request, $id) {
        $attendance = \App\Models\Attendance::findOrFail($id);
        $user = auth('api')->user();

        $attendance->attendees()->sync($user, false);

        return response()->json([
            'message' => __('Attendance added successfully!'),
            'data' => [
                'classroom' => $attendance->classroom->name,
                'lesson' => $attendance->name,
                'date' => $attendance->date,
            ]
        ]);
    })->name('api.attendance.add');

    Route::get('certificates', function (Request $request) {
        $certificates = [
            [
                'name' => 'Thẻ an toàn điện',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
  <path d="M11.251.068a.5.5 0 0 1 .227.58L9.677 6.5H13a.5.5 0 0 1 .364.843l-8 8.5a.5.5 0 0 1-.842-.49L6.323 9.5H3a.5.5 0 0 1-.364-.843l8-8.5a.5.5 0 0 1 .615-.09z"></path>
</svg>',
                'front' => 'https://placehold.co/400x600',
                'back' => 'https://placehold.co/400x600',
            ],
            [
                'name' => 'Thẻ ATLĐ',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
  <path fill-rule="evenodd" d="M12.516 2.17a.75.75 0 0 0-1.032 0 11.209 11.209 0 0 1-7.877 3.08.75.75 0 0 0-.722.515A12.74 12.74 0 0 0 2.25 9.75c0 5.942 4.064 10.933 9.563 12.348a.749.749 0 0 0 .374 0c5.499-1.415 9.563-6.406 9.563-12.348 0-1.39-.223-2.73-.635-3.985a.75.75 0 0 0-.722-.516l-.143.001c-2.996 0-5.717-1.17-7.734-3.08Zm3.094 8.016a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd"></path>
</svg>',
                'front' => 'https://placehold.co/400x600',
                'back' => 'https://placehold.co/400x600',
            ],
            [
                'name' => 'Giấy chứng nhận ATLĐ',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
  <path d="M15 15m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
  <path d="M13 17.5v4.5l2 -1.5l2 1.5v-4.5"></path>
  <path d="M10 19h-5a2 2 0 0 1 -2 -2v-10c0 -1.1 .9 -2 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -1 1.73"></path>
  <path d="M6 9l12 0"></path>
  <path d="M6 12l3 0"></path>
  <path d="M6 15l2 0"></path>
</svg>',
                'front' => 'https://placehold.co/400x600',
                'back' => 'https://placehold.co/400x600',
            ],
        ];

        return response()->json([
            'data' => $certificates
        ]);
    })->name('api.certificates.get');
});

Route::get('classroom/attendance/{id}/qr-code', function (Request $request, $id) {
    $attendance = \App\Models\Attendance::findOrFail($id);

    $qr = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(500)->format('png')->generate($attendance->register_url);

    return response($qr)->header('Content-type', 'image/png');
})->name('api.attendance.qr-code');
