<?php

namespace App\Http\Controllers;

use App\Enums\ExaminationStatus;
use App\Enums\ExaminationType;
use App\Enums\QuizType;
use App\Models\Exam;
use App\Models\Examination;
use App\Models\ExaminationMockQuiz;
use App\Models\MockQuiz;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\User;
use Carbon\Carbon;
use Harishdurga\LaravelQuiz\Models\QuizAttemptAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Nova\Notifications\NovaNotification;
use Laravel\Nova\URL;
use Spatie\Permission\Models\Permission;
use Exception;

class QuizController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function saveAnswer(Request $request)
    {
        $request->validate([
            'exam_id' => ['required', 'integer'],
            'quiz_id' => ['required', 'integer'],
            'answers' => ['array'],
            'is_started_at' => ['required', 'date'],
            'is_finished_at' => ['required', 'date'],
        ]);

        $user = Auth::user();
        if (empty($user)) {
            abort(403);
        }

        // check conditions for participating in the exam
        $quiz = Quiz::select('id', 'duration', 'name')->where('id', $request->get('quiz_id'))->first();
        if (! $quiz) {
            return back()->withErrors([
                'error' => __('Thông tin kỳ thi sai'),
            ])->onlyInput('error');
        }

        if (! $quiz->users()->where('users.id', $user->id)->exists()) {
            return back()->withErrors([
                'error' => __('Không nộp được bài thi'),
            ])->onlyInput('error');
        }

        $exam = Exam::select('name', 'id', 'score_pass', 'start_at', 'end_at', 'question_amount')->where('id', $request->get('exam_id'))->first();
        if (! $exam) {
            return back()->withErrors([
                'error' => __('Thông tin kỳ thi sai'),
            ])->onlyInput('error');
        }
        // check user submitted
        $exam_result = Examination::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->where('end_time', '!=', null)
            ->exists();
        if ($exam_result) {
            return back()->withErrors([
                'error' => __('Thi xong'),
            ])->onlyInput('error');
        }

        $data = $request->get('data');
        // check total questions
        $total_questions = collect($data)->count();
        if ($exam->question_amount != $total_questions || ! $request->get('is_started_at') || ! $request->get('is_finished_at') || ! $data) {
            return back()->withErrors([
                'error' => __('Thông tin kỳ thi sai'),
            ])->onlyInput('error');
        }

        $startExam = Carbon::createFromFormat('Y-m-d H:i:s', $exam->start_at);
        $endExam = Carbon::createFromFormat('Y-m-d H:i:s', $exam->end_at);
        if (! Carbon::now()->between($startExam, $endExam)) {
            return back()->withErrors([
                'error' => __('Không đủ điều kiện nộp bài thi'),
            ])->onlyInput('error');
        }

        // check time conditions for duration do quiz
        $start_quiz_at = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($request->get('is_started_at'))));
        $submit_quiz_at = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($request->get('is_finished_at'))));

        $duration = abs($submit_quiz_at->diffInSeconds($start_quiz_at));
        $endQuiz = Carbon::now();
        $startQuiz = (clone $endQuiz)->subSeconds($duration);

        if ($duration > $quiz->duration * 60 || ! $startQuiz->between($startExam, $endExam) || ! $endQuiz->between($startExam, $endExam)) {
            return back()->withErrors([
                'error' => __('Hết giờ thi'),
            ])->onlyInput('error');
        }

        // check permissions
        $quiz_attempt = QuizAttempt::select('id')->where('quiz_id', $quiz->id)->where('participant_id', $user->id)->first();
        if (! $quiz_attempt) {
            return back()->withErrors([
                'error' => __('Thông tin kỳ thi sai'),
            ])->onlyInput('error');
        }

        DB::transaction(function () use ($quiz, $endQuiz, $startQuiz, $exam, $user, $total_questions, $quiz_attempt, $data) {

            $number_correct_answer = 0;
            $number_wrong_answer = 0;
            $number_unanswered = 0;

            $metadata = collect($data)->map(function ($answer) use ($quiz, &$number_correct_answer, &$number_unanswered, &$number_wrong_answer, $quiz_attempt) {
                // get id question
                $question = Question::select('id')->where('id', Arr::get($answer, 'question_id'))->first();
                if (! $question) {
                    return back()->withErrors([
                        'error' => __('Thông tin kỳ thi sai'),
                    ])->onlyInput('error');
                }
                // check question
                $quiz_question = QuizQuestion::select('id')->where('quiz_id', $quiz->id)->where('question_id', $question->id)->first();
                if (! $quiz_question) {
                    return back()->withErrors([
                        'error' => __('Thông tin kỳ thi sai'),
                    ])->onlyInput('error');
                }

                $correct_answer = $question?->options->where('is_correct', true)?->pluck('id')?->toArray() ?: [];
                // check correct answer
                $answered_ids = Arr::get($answer, 'answered');
                if (empty($answered_ids)) {
                    $number_unanswered++;

                    return [
                        'answers' => collect(Arr::get($answer, 'answers'))->map(function ($ans) use ($correct_answer) {
                            return [
                                'id' => Arr::get($ans, 'id'),
                                'data' => Arr::get($ans, 'data'),
                                'is_correct' => collect($correct_answer)->contains(Arr::get($ans, 'id')),
                                'is_choose' => false,
                            ];
                        }),
                        'order' => Arr::get($answer, 'order'),
                        'answered' => Arr::get($answer, 'answered'),
                        'question_id' => Arr::get($answer, 'question_id'),
                        'question_type' => Arr::get($answer, 'question_type'),
                        'question_content' => Arr::get($answer, 'question_content'),
                        'is_correct' => false,
                    ];
                }

                $array_answer_ids = is_array($answered_ids) ? $answered_ids : [$answered_ids];

                if (count(array_diff($correct_answer, $array_answer_ids)) == 0) {
                    $number_correct_answer++;
                }

                if (count(array_diff($correct_answer, $array_answer_ids)) > 0) {
                    $number_wrong_answer++;
                }

                // add answer to system laravel quiz
                if (is_array($answered_ids)) {
                    // if many answers, check list answers
                    collect($answered_ids)->each(function ($id) use ($quiz_attempt, $quiz_question) {
                        $question_option = QuestionOption::select('id')->where('id', $id)->first();
                        if (! $question_option) {
                            return back()->withErrors([
                                'error' => __('Thông tin kỳ thi sai'),
                            ])->onlyInput('error');
                        }
                        QuizAttemptAnswer::firstOrCreate(
                            [
                                'quiz_attempt_id' => $quiz_attempt->id,
                                'quiz_question_id' => $quiz_question->id,
                                'question_option_id' => $question_option->id,
                            ]
                        );
                    });
                }

                $question_option = QuestionOption::select('id')->where('id', $answered_ids)->first();
                if (! $question_option) {
                    return back()->withErrors([
                        'error' => __('Thông tin kỳ thi sai'),
                    ])->onlyInput('error');
                }
                QuizAttemptAnswer::firstOrCreate(
                    [
                        'quiz_attempt_id' => $quiz_attempt->id,
                        'quiz_question_id' => $quiz_question->id,
                        'question_option_id' => $question_option->id,
                    ]
                );

                return [
                    'answers' => collect(Arr::get($answer, 'answers'))->map(function ($ans) use ($answer, $correct_answer) {
                        return [
                            'id' => Arr::get($ans, 'id'),
                            'data' => Arr::get($ans, 'data'),
                            'is_correct' => collect($correct_answer)->contains(Arr::get($ans, 'id')),
                            'is_choose' => collect(Arr::get($answer, 'answered'))->contains(Arr::get($ans, 'id')),
                        ];
                    }),
                    'order' => Arr::get($answer, 'order'),
                    'answered' => Arr::get($answer, 'answered'),
                    'question_id' => Arr::get($answer, 'question_id'),
                    'question_type' => Arr::get($answer, 'question_type'),
                    'question_content' => Arr::get($answer, 'question_content'),
                    'is_correct' => count(array_diff($correct_answer, $array_answer_ids)) == 0,
                ];
            });

            // save examination
            $score = sprintf('%.1f', $total_questions > 0 ? $number_correct_answer * 10 / $total_questions : 0);
            $examination = Examination::create([
                'exam_id' => $exam->id,
                'quiz_id' => $quiz->id,
                'user_id' => $user->id,
                'start_time' => $startQuiz,
                'end_time' => $endQuiz,
                'duration' => $endQuiz->diffInSeconds($startQuiz),
                'correct_answer' => $number_correct_answer,
                'wrong_answer' => $number_wrong_answer,
                'unanswered' => $number_unanswered,
                'score' => $score,
                'state' => $score >= $exam->score_pass ? ExaminationStatus::Pass : ExaminationStatus::Fail,
                'examination' => $metadata,
                'dob' => $user->dob,
                'username' => $user->username,
                'gender' => $user->gender,
                'name' => $user->name,
                'position' => $user->position,
                'department' => $user->department,
                'factory_name' => $user->factory_name,
                'exam_name' => $exam->name,
                'start_time_exam' => $exam->start_at,
                'end_time_exam' => $exam->end_at,
                'uuid' => Str::uuid()->toString(),
                'group' => $user->groups?->implode('name', ', '),
                'quiz_name' => $quiz->name,
                'avatar_url' => $user->avatar_url,
                'avatar' => $user->avatar,
                'employee_code' => $user->employee_code,
            ]);

            $user_noty = collect([]);
            Permission::findByName('submitQuiz')->roles()->with(['users'])->get()->pluck('users')->map(function ($users) use ($user_noty) {
                $user_noty->push(...$users);
            });

            $user_noty->unique()?->map(function ($user) use ($examination) {
                $user->notify(
                    NovaNotification::make()
                        ->message(Auth::user()?->name.' đã nộp bài thi')
                        ->action(__('Result Test'), URL::remote('/media/examination/'.$examination->uuid))
                        ->icon('link')
                        ->type('info')
                );
            });
        });

        return redirect()->back()->with('message', __('Nộp bài thi thành công!'));
    }

    public function saveAnswerMockQuiz(Request $request, $id)
    {
        $request->validate([
            'questions' => ['array'],
            'started_at' => ['required', 'date'],
            'finished_at' => ['required', 'date'],
        ]);

        $user = auth('api')?->user();
        if (empty($user)) {
            return response()->json(['message' => __('Unauthorized !')], 401);
        }

        // check conditions for participating in the exam
        $quiz = MockQuiz::select('id', 'duration', 'name', 'question_amount_quiz', 'score_pass_quiz')->where('id', $id)->first();
        if (!$quiz) {
            return response()->json(['message' => __('Wrong exam information')], 404);
        }

        // check exam has already been completed
        $exam_result = ExaminationMockQuiz::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->where('end_time', '!=', null)
            ->exists();
        if ($exam_result) {
            return response()->json(['message' => __('The assignment could not be submitted because the test has already been completed.')], 400);
        }

        // check total questions
        $data = $request->get('questions');
        $total_questions = collect($data)->count();
        if ($quiz->question_amount_quiz != $total_questions) {
            return response()->json(['message' => __('Wrong exam information')], 400);
        }

        // check time conditions for duration do quiz
        $start_quiz_at = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($request->get('started_at'))));
        $submit_quiz_at = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($request->get('finished_at'))));

        $duration = abs($submit_quiz_at->diffInSeconds($start_quiz_at));
        $endQuiz = Carbon::now();
        $startQuiz = (clone $endQuiz)->subSeconds($duration);

        if ($duration > $quiz->duration * 60) {
            return response()->json(['message' => __('Time runs out')], 403);
        }

        $examination = collect();
        try {
            DB::transaction(function () use ($quiz, $endQuiz, $startQuiz, $user, $total_questions, $data, $request, &$examination) {

                $number_correct_answer = 0;
                $number_wrong_answer = 0;
                $number_unanswered = 0;

                $quiz_attempt = QuizAttempt::firstOrCreate([
                    'quiz_id' => $quiz->id,
                    'participant_id' => $user->id,
                    'participant_type' => User::class
                ]);

                $metadata = collect($data)->map(function ($question_submit, $index) use ($quiz, &$number_correct_answer, &$number_unanswered, &$number_wrong_answer, $quiz_attempt) {
                    // get id question
                    $question = Question::select('id')->where('id', Arr::get($question_submit, 'id'))->first();
                    if (!$question) {
                        throw new Exception( __('Wrong exam information'));
                    }

                    // check question
                    $quiz_question = QuizQuestion::select('id')->where('quiz_id', $quiz->id)->where('question_id', $question->id)->first();
                    if (!$quiz_question) {
                        throw new Exception( __('Wrong exam information'));
                    }
                    $correct_answer = $question?->options->where('is_correct', true)?->pluck('id')?->toArray() ?: [];
                    // check correct answer
                    $answered_ids = Arr::get($question_submit, 'answered');
                    if (empty($answered_ids)) {
                        $number_unanswered++;

                        return [
                            'answers' => collect(Arr::get($question_submit, 'answers'))->map(function ($ans) use ($correct_answer) {
                                return [
                                    'id' => Arr::get($ans, 'id'),
                                    'content' => Arr::get($ans, 'content'),
                                    'is_correct' => collect($correct_answer)->contains(Arr::get($ans, 'id')),
                                    'is_choose' => false,
                                ];
                            }),
                            'order' => $index + 1,
                            'answered' => Arr::get($question_submit, 'answered'),
                            'id' => Arr::get($question_submit, 'id'),
                            'question_type' => Arr::get($question_submit, 'question_type'),
                            'content' => Arr::get($question_submit, 'content'),
                            'is_correct' => false,
                        ];
                    }

                    $array_answer_ids = is_array($answered_ids) ? $answered_ids : [$answered_ids];

                    if (count(array_diff($correct_answer, $array_answer_ids)) == 0) {
                        $number_correct_answer++;
                    }

                    if (count(array_diff($correct_answer, $array_answer_ids)) > 0) {
                        $number_wrong_answer++;
                    }

                    // add answer to system laravel quiz
                    if (is_array($answered_ids)) {
                        // if many answers, check list answers
                        collect($answered_ids)->each(function ($id) use ($quiz_question, $quiz_attempt) {
                            $question_option = QuestionOption::select('id')->where('id', $id)->first();
                            if (!$question_option) {
                                throw new Exception( __('Wrong exam information'));
                            }
                            QuizAttemptAnswer::firstOrCreate(
                                [
                                    'quiz_attempt_id' => $quiz_attempt->id,
                                    'quiz_question_id' => $quiz_question->id,
                                    'question_option_id' => $question_option->id,
                                ]
                            );
                        });
                    }

                    $question_option = QuestionOption::select('id')->where('id', $answered_ids)->first();
                    if (!$question_option) {
                        throw new Exception( __('Wrong exam information'));
                    }
                    QuizAttemptAnswer::firstOrCreate(
                        [
                            'quiz_attempt_id' => $quiz_attempt->id,
                            'quiz_question_id' => $quiz_question->id,
                            'question_option_id' => $question_option->id,
                        ]
                    );

                    return [
                        'answers' => collect(Arr::get($question_submit, 'answers'))->map(function ($ans) use ($question_submit, $correct_answer) {
                            return [
                                'id' => Arr::get($ans, 'id'),
                                'content' => Arr::get($ans, 'content'),
                                'is_correct' => collect($correct_answer)->contains(Arr::get($ans, 'id')),
                                'is_choose' => collect(Arr::get($question_submit, 'answered'))->contains(Arr::get($ans, 'id')),
                            ];
                        }),
                        'order' => $index + 1,
                        'answered' => Arr::get($question_submit, 'answered'),
                        'id' => Arr::get($question_submit, 'id'),
                        'question_type' => Arr::get($question_submit, 'question_type'),
                        'content' => Arr::get($question_submit, 'content'),
                        'is_correct' => count(array_diff($correct_answer, $array_answer_ids)) == 0,
                    ];
                });

                // save examination
                $score = sprintf('%.1f', $total_questions > 0 ? $number_correct_answer * 10 / $total_questions : 0);
                $examination = ExaminationMockQuiz::create([
                    'quiz_id' => $quiz->id,
                    'user_id' => $user->id,
                    'start_time' => $startQuiz,
                    'end_time' => $endQuiz,
                    'duration' => $endQuiz->diffInSeconds($startQuiz),
                    'correct_answer' => $number_correct_answer,
                    'wrong_answer' => $number_wrong_answer,
                    'unanswered' => $number_unanswered,
                    'score' => $score,
                    'state' => $score >= $quiz->score_pass_quiz ? ExaminationStatus::Pass : ExaminationStatus::Fail,
                    'examination' => $metadata,
                    'dob' => $user->dob,
                    'username' => $user->username,
                    'gender' => $user->gender,
                    'name' => $user->name,
                    'position' => $user->position,
                    'department' => $user->department,
                    'factory_name' => $user->factory_name,
                    'uuid' => Str::uuid()->toString(),
                    'group' => $user->group?->name,
                    'quiz_name' => $quiz->name,
                    'avatar_url' => $user->avatar_url,
                    'avatar' => $user->avatar,
                    'employee_code' => $user->employee_code,
                    'type' => ExaminationType::Random
                ]);
            });
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);;
        }

        return [
        'is_done' => true,
        'is_passed' => $examination->getAttribute('state') == ExaminationStatus::Pass,
        'score' => $examination->getAttribute('score'),
        'duration' => $examination->getAttribute('duration') ?: 0,
        'total_questions' => $quiz->questions?->count() ?? 0,
        'right_answers' => $examination->getAttribute('correct_answer'),
        'wrong_answers' => $examination->getAttribute('wrong_answer'),
        'unanswered' => $examination->getAttribute('unanswered'),
        'questions' => $examination->getAttribute('examination'),
        'examination' => collect($examination->getAttribute('examination'))->map(fn($question) => array_merge($question,
            [
                'index_answered' => collect($question['answers'])->where('id', $question['answered'])?->keys()?->first() ?? null,
                'index_correct_answer' => collect($question['answers'])->where('is_correct', true)?->keys()?->first() ?? null,
            ])
        ),
    ];
    }

    public function reset(Request $request, $id)
    {
        $user = auth('api')?->user();
        if (empty($user)) {
            return response()->json(['message' => __('Unauthorized !')], 401);
        }

        $quiz = MockQuiz::where('id', $id)->first();
        if (empty($quiz)) {
            return response()->json(['message' => __('Not Found!')], 404);
        }

        try {
            ExaminationMockQuiz::where('user_id', $user->id)->where('quiz_id', $quiz->id)->delete();
        }catch (\Exception $e) {
            return response()->json(['message' => $e], 400);
        }

        return response()->json(['message' => __('Delete history as a examination successfully!')]);
    }
}
