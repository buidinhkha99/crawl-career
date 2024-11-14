<?php

namespace App\Models;

use App\Enums\ExamStatus;
use App\Exceptions\AppException;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BaseExam extends Model
{
    use HasFactory;

    protected $relationQuiz = QuizOccupational::class;
    protected $relationExamination = Examination::class;

    protected $table = 'exams';

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function quizzes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany($this->relationQuiz, 'exam_id');
    }

    public function examinations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany($this->relationExamination, 'exam_id');
    }

    public function quiz()
    {
        return $this->hasOne($this->relationQuiz, 'exam_id');
    }

    public function getUsersAttribute()
    {
        return $this->getAttribute('quizzes')->pluck('users')->flatten()->filter();
    }

    public function getStatusAttribute(): string
    {
        if ($this->getAttribute('start_at')->gt(now())) {
            return ExamStatus::Upcoming;
        }

        if ($this->getAttribute('end_at')->lt(now())) {
            return ExamStatus::Finished;
        }

        return ExamStatus::Happening;
    }

    public function getQuizKitAttribute(): Collection
    {
        return $this->quizzes()->with(['questions:id', 'questions.topics:name'])
            ->get(['quizzes.id', 'quizzes.name', 'quizzes.exam_id'])
            ->map(function ($quiz) {

                $questions = $quiz->questions->map(fn ($question) => [
                    'id' => $question->id,
                    'topic_name' => $question->topics->first()?->name,
                ]);

                return [
                    'name' => $quiz->getAttribute('name'),
                    'id' => $quiz->getAttribute('id'),
                    'kit' => $questions->groupBy('topic_name')->map(fn ($value, $key) => [
                        'topics' => $key,
                        'amount' => $value->count(),
                    ])->values(),
                ];
            });
    }

    /**
     * @throws Exception
     */
    public function saveQuizzes(Collection $quiz_kits, $duration)
    {
        if ($quiz_kits->count() == 0) {
            throw new AppException(__('The exam :exam must have a quiz', [
                'exam' => $this->getAttribute('name'),
            ]));
        }

        DB::transaction(function () use ($quiz_kits, $duration) {
            $this->quizzes()->whereNotIn('id', $quiz_kits->pluck('id')->filter())->delete();
            $duplicates = $quiz_kits->pluck('name')->map(fn($name) => trim($name))->duplicates()->values();

            $quiz_kits->each(function ($quiz_kit) use ($duration, $duplicates) {
                $quiz_kit = collect($quiz_kit)->values();

                if ($quiz_kit->count() <= 0) {
                    throw new AppException(__('The exam :exam must have a quiz', [
                        'exam' => $this->getAttribute('name'),
                    ]));
                }

                $id = $quiz_kit->get(1);
                $name = trim($quiz_kit->first());
                if ($name === '') {
                    throw new AppException(__('The :attribute field is required.', [
                        'attribute' => __('Kit name'),
                    ]));
                }
                // kit is collection of topic.name & number of questions selected in that topic
                $kits = collect($quiz_kit->get(2) ?? [])->map(fn ($kit) => collect($kit)->values());

                $total_questions = $kits->sum(fn ($kit) => (int) $kit->get(1));

                if ($duplicates->contains($name)) {
                    throw new AppException(__("The name ':kit' has already been taken", [
                        'kit' => $name,
                    ]));
                }

                if ($total_questions != (int) $this->getAttribute('question_amount')) {
                    throw new AppException(__("The quiz kit ':kit' must be equal to :question_amount questions", [
                        'kit' => $name,
                        'question_amount' => $this->getAttribute('question_amount'),
                    ]));
                }

                $quiz = $this->quizzes()->updateOrcreate([
                    'id' => $id,
                ], [
                    'name' => $name,
                    'duration' => $duration,
                ]);
                $quiz->questions()->detach();

                // handle quiz generation based on quiz_kit
                foreach ($kits as $kit) {
                    $topic_name = $kit->get(0);
                    if ($topic_name === '') {
                        return;
                    }

                    $topic = Topic::where('name', $topic_name)->first();
                    if (! $topic) {
                        throw new AppException(__("In the kit ':kit', the topic ':topic' only :count_question questions", [
                            'kit' => $name,
                            'topic' => $topic_name,
                            'count_question' => 0,
                        ]));
                    }

                    $selected_questions_count = $kit->get(1);
                    $question_ids = $topic->questions()->select('questions.id')->get();

                    // selected more than actual count questions in topic
                    // 0 questions in topic
                    $count_question = $question_ids->count();
                    if ($selected_questions_count > $count_question || $count_question === 0) {
                        throw new AppException(__("In the kit ':kit', the topic ':topic' only :count_question questions", [
                            'kit' => $name,
                            'topic' => $topic_name,
                            'count_question' => $count_question,
                        ]));
                    }

                    $quiz->questions()->attach($question_ids->shuffle()->random($selected_questions_count));
                }
            });
        });
    }
}
