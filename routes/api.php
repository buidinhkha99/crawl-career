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

        $attended = $attendance->attendees()
            ->whereNull('created_at')
            ->where(['attendance_id' => $attendance->id, 'user_id' => $user->id])
            ->first();

        if (is_null($attended)) {
            return response()->json([
                'message' => __('User already attended!'),
                'data' => [
                    'classroom' => $attendance->classroom->name,
                    'lesson' => $attendance->name,
                    'date' => $attendance->date,
                ]
            ]);
        }

        $attended->created_at = now();
        $attended->updated_at = now();
        $attended->save();

        return response()->json([
            'message' => __('Attendance added successfully!'),
            'data' => [
                'classroom' => $attendance->classroom->name,
                'lesson' => $attendance->name,
                'date' => $attendance->date,
            ]
        ]);
    })->name('api.attendance.add');

    Route::get('certificates', [\App\Http\Controllers\CertificateController::class, 'certificates'])->name('api.certificates.get');
});

Route::get('classroom/attendance/{id}/qr-code', function (Request $request, $id) {
    $attendance = \App\Models\Attendance::findOrFail($id);

    $qr = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(500)->format('png')->generate($attendance->register_url);

    return response($qr)->header('Content-type', 'image/png');
})->name('api.attendance.qr-code');
