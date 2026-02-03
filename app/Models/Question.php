<?php

namespace App\Models;

use App\Exceptions\AppException;
use Harishdurga\LaravelQuiz\Database\Factories\QuestionFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Question extends \Harishdurga\LaravelQuiz\Models\Question
{
    public function quizzes(): BelongsToMany
    {
        return $this->belongsToMany(Quiz::class, 'quiz_questions');
    }

    public function mock_quizzes(): BelongsToMany
    {
        return $this->belongsToMany(MockQuiz::class, 'quiz_questions', 'question_id','quiz_id');
    }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'pivot',
        'is_active',
        'media_type',
        'media_url',
        'question_type_id'
    ];

    /**
     * Indicates if the model is currently force deleting.
     *
     * @var bool
     */
    protected $forceDeleting = true;

    public function topic()
    {
        $instance = $this->newRelatedInstance(Topic::class);

        return $this->newHasOne($instance->newQuery(), $this, 'topicables.topicable_id', $this->getKeyName())
            ->join('topicables', 'topics.id', '=', 'topicables.topic_id')
            ->where('topicables.topicable_type', Question::class)
            ->select('topics.*', 'topicables.topicable_id', 'topicables.topic_id', 'topicables.topic_id as id');
    }

    protected static function newFactory(): QuestionFactory
    {
        return \Database\Factories\QuestionFactory::new();
    }

    public function lessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class)->using(LessonQuestion::class);
    }

    protected function answers(): Attribute
    {
        return Attribute::make(
            get: function () {
                $answers = $this->options()->get();

                if ($answers->count() === 0) {
                    return [];
                }

                return $answers->map(
                    fn ($answer) => [
                        'name' => $answer->getAttribute('name'),
                        'id' => $answer->getAttribute('id'),
                        'is_correct' => $answer->getAttribute('is_correct'),
                    ]
                );
            },
        );
    }

    public function users () {
        return $this->belongsToMany(User::class, 'question_user')->using(QuestionUser::class)->withPivot(['question_option_id', 'is_correct']);
    }

    /**
     * @throws AppException
     */
    public function saveAnswers($answers): void
    {
        if ($answers->count() === 0) {
            throw new AppException(__('The question must have at least one answer'));
        }

        $answers = $answers->map(fn ($answer) => collect($answer));

        if (! $answers->contains('is_correct', true)) {
            throw new AppException(__('The question must have at least one correct answer'));
        }

        if ($answers->where('is_correct', true)->count() > 1) {
            throw new AppException(__('This type of question cannot have more than one correct answer'));
        }

        $this->options()
            ->whereIn('id', $this->options()->pluck('id')->diff($answers->pluck('id')->filter())->values())
            ->delete();

        $answers->each(function ($answer, $index) {
            if ($answer->get('id')) {
                $option = $this->options()->where('id', $answer->get('id'))->first();

                if (! $option) {
                    return;
                }

                if ($answer->get('name') === '') {
                    throw new AppException(__('The answer :index must have value', ['index' => $index + 1]));
                }

                $option->setAttribute('name', $answer->get('name'));
                $option->setAttribute('is_correct', $answer->get('is_correct'));
                $option->save();

                return;
            }

            $this->options()->create([
                'name' => $answer->get('name'),
                'is_correct' => $answer->get('is_correct'),
            ]);
        });
    }
}
